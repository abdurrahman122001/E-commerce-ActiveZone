<?php

namespace App\Http\Controllers\DeliveryBoy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\DeliveryBoy;
use App\Models\DeliveryHistory;
use App\Models\User;
use Auth;

class DeliveryBoyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'delivery_boy']);
    }

    public function index()
    {
        $delivery_boy = Auth::user()->delivery_boy;
        if (!$delivery_boy) {
            // Create delivery boy record if it doesn't exist for this user
            $delivery_boy = new DeliveryBoy();
            $delivery_boy->user_id = Auth::id();
            $delivery_boy->save();
        }

        $total_completed = Order::where('assign_delivery_boy', Auth::id())->where('delivery_status', 'delivered')->count();
        $total_pending = Order::where('assign_delivery_boy', Auth::id())->where('delivery_status', '!=', 'delivered')->where('delivery_status', '!=', 'cancelled')->count();
        $total_cancelled = Order::where('assign_delivery_boy', Auth::id())->where('delivery_status', 'cancelled')->count();

        return view('delivery_boy.dashboard', compact('total_completed', 'total_pending', 'total_cancelled'));
    }

    public function assigned_delivery()
    {
        $orders = Order::where('assign_delivery_boy', Auth::id())
            ->whereIn('delivery_status', ['pending', 'confirmed', 'picked_up', 'on_the_way'])
            ->latest()
            ->paginate(10);
        return view('delivery_boy.assigned_deliveries', compact('orders'));
    }

    public function available_deliveries()
    {
        // Available deliveries could be those in the same city not yet assigned?
        // Or unassigned orders in general?
        $orders = Order::where('assign_delivery_boy', null)
            ->where('delivery_status', 'confirmed') // Only confirmed orders can be picked up
            ->where('payment_status', 'paid') // Usually only paid? (Except COD)
            ->latest()
            ->paginate(10);
        return view('delivery_boy.available_deliveries', compact('orders'));
    }

    public function pickup_delivery()
    {
        $orders = Order::where('assign_delivery_boy', Auth::id())
            ->where('delivery_status', 'picked_up')
            ->latest()
            ->paginate(10);
        return view('delivery_boy.pickup_deliveries', compact('orders'));
    }

    public function completed_delivery()
    {
        $orders = Order::where('assign_delivery_boy', Auth::id())
            ->where('delivery_status', 'delivered')
            ->latest()
            ->paginate(10);
        return view('delivery_boy.completed_deliveries', compact('orders'));
    }

    public function cancelled_delivery()
    {
        $orders = Order::where('assign_delivery_boy', Auth::id())
            ->where('delivery_status', 'cancelled')
            ->latest()
            ->paginate(10);
        return view('delivery_boy.cancelled_deliveries', compact('orders'));
    }

    public function on_the_way_deliveries()
    {
        $orders = Order::where('assign_delivery_boy', Auth::id())
            ->where('delivery_status', 'on_the_way')
            ->latest()
            ->paginate(10);
        return view('delivery_boy.on_the_way_deliveries', compact('orders'));
    }

    public function pending_delivery()
    {
        $orders = Order::where('assign_delivery_boy', Auth::id())
            ->where('delivery_status', 'pending')
            ->latest()
            ->paginate(10);
        return view('delivery_boy.pending_deliveries', compact('orders'));
    }

    public function total_collection()
    {
        return view('delivery_boy.total_collection');
    }

    public function total_earning()
    {
        return view('delivery_boy.total_earnings');
    }

    public function cancel_request($id)
    {
        $order = Order::findOrFail($id);
        $order->cancel_request = 1;
        $order->save();
        flash(translate('Cancel request has been sent.'))->success();
        return back();
    }

    public function delivery_boys_cancel_request_list()
    {
        $orders = Order::where('assign_delivery_boy', Auth::id())
            ->where('cancel_request', 1)
            ->latest()
            ->paginate(10);
        return view('delivery_boy.cancel_requests', compact('orders'));
    }

    public function order_detail($id)
    {
        $order = Order::findOrFail($id);
        return view('delivery_boy.order_detail', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('delivery_boy.profile', compact('user'));
    }

    public function wallet()
    {
        return view('delivery_boy.wallet');
    }
}
