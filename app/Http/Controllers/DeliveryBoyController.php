<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryBoy;
use App\Models\User;
use Hash;

class DeliveryBoyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $delivery_boys = DeliveryBoy::latest();
        if ($request->has('search')){
            $sort_search = $request->search;
            $user_ids = User::where('user_type', 'delivery_boy')->where(function($user) use ($sort_search){
                $user->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%');
            })->pluck('id');
            $delivery_boys = $delivery_boys->whereIn('user_id', $user_ids);
        }
        $delivery_boys = $delivery_boys->paginate(15);
        return view('backend.delivery_boy.index', compact('delivery_boys', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.delivery_boy.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|unique:users',
            'phone'     => 'required|unique:users',
            'password'  => 'required|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->user_type = 'delivery_boy';
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->save();

        $delivery_boy = new DeliveryBoy();
        $delivery_boy->user_id = $user->id;
        $delivery_boy->status = 1; // Admin created are auto-approved
        $delivery_boy->save();

        flash(translate('Delivery boy has been inserted successfully'))->success();
        return redirect()->route('delivery-boys.index');
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
        $delivery_boy = DeliveryBoy::findOrFail($id);
        return view('backend.delivery_boy.edit', compact('delivery_boy'));
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
        $delivery_boy = DeliveryBoy::findOrFail($id);
        $user = $delivery_boy->user;
        
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|unique:users,email,'.$user->id,
            'phone'     => 'required|unique:users,phone,'.$user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if($request->password != null){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        flash(translate('Delivery boy has been updated successfully'))->success();
        return redirect()->route('delivery-boys.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delivery_boy = DeliveryBoy::findOrFail($id);
        $user = $delivery_boy->user;
        if($user){
            $user->delete();
        }
        $delivery_boy->delete();

        flash(translate('Delivery boy has been deleted successfully'))->success();
        return redirect()->route('delivery-boys.index');
    }

    public function update_status(Request $request)
    {
        $delivery_boy = DeliveryBoy::findOrFail($request->id);
        $delivery_boy->status = $request->status;
        if($delivery_boy->save()){
            return 1;
        }
        return 0;
    }

    public function ban($id)
    {
        $delivery_boy = DeliveryBoy::findOrFail($id);
        $user = $delivery_boy->user;
        $user->banned = !$user->banned;
        $user->save();
        flash(translate('Status has been updated successfully'))->success();
        return back();
    }

    public function delivery_boy_configure()
    {
        return view('backend.delivery_boy.configure');
    }

    public function delivery_boys_payment_histories()
    {
        return view('backend.delivery_boy.payment_histories');
    }

    public function delivery_boys_collection_histories()
    {
        return view('backend.delivery_boy.collection_histories');
    }

    public function cancel_request_list()
    {
        return view('backend.delivery_boy.cancel_request_list');
    }

    public function order_collection_form(Request $request)
    {
        // Implementation for order collection
    }

    public function collection_from_delivery_boy(Request $request)
    {
        // Implementation for collection
    }

    public function delivery_earning_form(Request $request)
    {
        // Implementation for earning form
    }

    public function paid_to_delivery_boy(Request $request)
    {
        // Implementation for payment
    }
}
