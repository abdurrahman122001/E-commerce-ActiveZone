<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Artisan;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin_profile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }
        
        if ($request->hasFile('avatar')) {
            $type = [
                "jpg" => "image",
                "jpeg" => "image",
                "png" => "image",
                "svg" => "image",
                "webp" => "image",
                "gif" => "image",
            ];
            $file = $request->file('avatar');
            $extension = strtolower($file->getClientOriginalExtension());
            if (isset($type[$extension])) {
                $filename = str_replace(' ', '_', $file->getClientOriginalName());
                $filename = time().'_'.$filename;
                
                $upload = new \App\Models\Upload;
                $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $upload->extension = $extension;
                $upload->file_name = 'uploads/all/' . $filename;
                $upload->user_id = $user->id;
                $upload->type = $type[$extension];
                $upload->file_size = $file->getSize();
                $upload->save();

                $file->move(public_path('uploads/all'), $filename);
                
                $user->avatar_original = $upload->id;
            }
        } elseif ($request->has('avatar')) {
            $user->avatar_original = $request->avatar;
        }

        if ($user->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
