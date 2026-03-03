<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayoutNotification;
use App\Models\SellerWithdrawRequest;
use App\Models\User;
use App\Utility\EmailUtility;
use Auth;

class SellerWithdrawRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller_withdraw_requests = SellerWithdrawRequest::where('user_id', Auth::user()->id)->latest()->paginate(9);
        return view('seller.money_withdraw_requests.index', compact('seller_withdraw_requests'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $withdraw_type = $request->withdraw_type ?? 'standard';
        
        // Validation based on balance type
        if ($withdraw_type == 'referral') {
            $vendor = $user->vendor;
            if (!$vendor || $request->amount > $vendor->referral_balance) {
                flash(translate('Insufficient referral balance'))->error();
                return back();
            }

            // Check if bank details are set
            if (!$user->shop || empty($user->shop->bank_acc_no)) {
                flash(translate('Please update your bank details in shop settings before requesting a withdrawal.'))->warning();
                return back();
            }
        } else {
            // Standard check (usually against shop->admin_to_pay)
            if ($user->shop && $request->amount > $user->shop->admin_to_pay) {
                // If it's a vendor, they might use vendor->balance instead of shop->admin_to_pay
                // But for now let's stick to the existing standard logic if it's not referral
                // Actually, let's check if the user is a vendor and handle accordingly
                if ($user->user_type == 'vendor') {
                    $vendor = $user->vendor;
                    if ($vendor && $request->amount > $vendor->balance) {
                        flash(translate('Insufficient balance'))->error();
                        return back();
                    }
                }
                // Standard sellers usually have this check in the UI, but let's be safe.
                // If it's a standard seller, shop->admin_to_pay is the source.
            }
        }

        $seller_withdraw_request = new SellerWithdrawRequest;
        $seller_withdraw_request->user_id = $user->id;
        $seller_withdraw_request->amount = $request->amount;
        $seller_withdraw_request->withdraw_type = $withdraw_type;
        $seller_withdraw_request->message = $request->message;
        $seller_withdraw_request->status = '0';
        $seller_withdraw_request->viewed = '0';
        if ($seller_withdraw_request->save()) {

            // Seller payout request web notification to admin
            $admin_user = User::where('user_type', 'admin')->first();
            if ($admin_user) {
                $users = User::findMany([$admin_user->id]);
                $data = array();
                $data['user'] = $user;
                $data['amount'] = $request->amount;
                $data['status'] = 'pending';
                $data['notification_type_id'] = get_notification_type('seller_payout_request', 'type')->id;
                Notification::send($users, new PayoutNotification($data));
            }

            // Seller payout request email to admin & seller
            $emailIdentifiers = ['seller_payout_request_email_to_admin','seller_payout_request_email_to_seller'];
            EmailUtility::seller_payout($emailIdentifiers, $user, $request->amount,  null);

            flash(translate('Request has been sent successfully'))->success();
            if ($withdraw_type == 'referral') {
                return redirect()->route('vendor.my_referrals');
            }
            return redirect()->route('seller.money_withdraw_requests.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
