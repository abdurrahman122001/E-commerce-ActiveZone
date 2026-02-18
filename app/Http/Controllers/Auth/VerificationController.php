<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\OTPVerificationController;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->user()->email != null) {
            if ($request->user()->hasVerifiedEmail()) {
                 if($request->user()->user_type == 'seller') {
                    return redirect()->route('seller.dashboard');
                } elseif ($request->user()->user_type == 'delivery_boy') {
                    if ($request->user()->delivery_boy && $request->user()->delivery_boy->status == 1) {
                        return redirect()->route('delivery_boy.dashboard');
                    } else {
                        return redirect()->route('delivery_boy.pending');
                    }
                } elseif (in_array($request->user()->user_type, ['franchise', 'sub_franchise'])) {
                     return redirect()->route('franchise.dashboard');
                }
                return redirect($this->redirectPath());
            }
            return view('auth.'.get_setting('authentication_layout_select').'.verify_email');
        }
        else {
            $otpController = new OTPVerificationController;
            $otpController->send_code($request->user());
            return redirect()->route('verification');
        }
    }


    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }

    public function verification_confirmation($code){
        $user = User::where('verification_code', $code)->first();
        if($user != null){
            $user->email_verified_at = Carbon::now();
            $user->save();
            auth()->login($user, true);
            offerUserWelcomeCoupon();
            flash(translate('Your email has been verified successfully'))->success();
        }
        else {
            flash(translate('Sorry, we could not verifiy you. Please try again'))->error();
        }

        if($user->user_type == 'seller') {
            return redirect()->route('seller.dashboard');
        } elseif ($user->user_type == 'delivery_boy') {
            if ($user->delivery_boy && $user->delivery_boy->status == 1) {
                return redirect()->route('delivery_boy.dashboard');
            } else {
                return redirect()->route('delivery_boy.pending');
            }
        } elseif (in_array($user->user_type, ['franchise', 'sub_franchise'])) {
             return redirect()->route('franchise.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
