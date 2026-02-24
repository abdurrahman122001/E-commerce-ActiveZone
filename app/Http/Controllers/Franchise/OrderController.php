<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Auth;
use App\Utility\NotificationUtility;
use App\Utility\EmailUtility;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        
        $user = Auth::user();
        $all_seller_ids = [$user->id];
        
        if ($user->user_type == 'franchise' && $user->franchise) {
            $franchise_id = $user->franchise->id;
            $vendor_user_ids = Vendor::where('franchise_id', $franchise_id)->pluck('user_id')->toArray();
            $sub_franchise_user_ids = \App\Models\SubFranchise::where('franchise_id', $franchise_id)->pluck('user_id')->toArray();
            $sub_franchise_vendor_user_ids = Vendor::whereIn('sub_franchise_id', function($query) use ($franchise_id) {
                $query->select('id')->from('sub_franchises')->where('franchise_id', $franchise_id);
            })->pluck('user_id')->toArray();
            $all_seller_ids = array_unique(array_merge($all_seller_ids, $vendor_user_ids, $sub_franchise_user_ids, $sub_franchise_vendor_user_ids));
        } elseif ($user->user_type == 'sub_franchise' && $user->sub_franchise) {
            $sub_franchise_id = $user->sub_franchise->id;
            $vendor_user_ids = Vendor::where('sub_franchise_id', $sub_franchise_id)->pluck('user_id')->toArray();
            $all_seller_ids = array_unique(array_merge($all_seller_ids, $vendor_user_ids));
        }

        $orders = Order::whereIn('seller_id', $all_seller_ids)->orderBy('code', 'desc');

        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);
        
        return view('franchise.orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->delivery_viewed = 1;
        $order->save();
        return view('franchise.orders.show', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == 'delivered') {
            $order->delivered_date = date("Y-m-d H:i:s");
            if ($request->has('lat') && $request->has('long')) {
                $order->delivery_completed_lat = $request->lat;
                $order->delivery_completed_long = $request->long;
            }
            $order->save();
            
            processDeliveryEarnings($order);

            // Calculate Commission if not already calculated
            if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
                calculateCommissionAffilationClubPoint($order);
            }
        }

        if ($request->status == 'confirmed') {
            assign_nearest_rider($order);
        }

        // When vendor/franchise marks order as ready to pick, assign nearest delivery boy
        if ($request->status == 'ready_to_pick') {
            assign_nearest_rider($order);
        }

        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->delivery_status = $request->status;
            $orderDetail->save();
        }

        NotificationUtility::sendNotification($order, $request->status);
        EmailUtility::order_email($order, $request->status);

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->payment_status = $request->status;
        $order->save();

        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }

        if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
            calculateCommissionAffilationClubPoint($order);
        }

        NotificationUtility::sendNotification($order, $request->status);
        
        return 1;
    }
}
