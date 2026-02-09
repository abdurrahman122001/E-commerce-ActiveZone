<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $authUserId = auth()->user()->id;
        
        $data['total_products'] = Product::where('user_id', $authUserId)->count();
        $data['total_sales'] = OrderDetail::where('seller_id', $authUserId)->where('delivery_status', 'delivered')->sum('price');
        $data['pending_orders'] = OrderDetail::where('seller_id', $authUserId)->where('delivery_status', 'pending')->count();
        $data['delivered_orders'] = OrderDetail::where('seller_id', $authUserId)->where('delivery_status', 'delivered')->count();
        
        $data['this_month_pending_orders'] = OrderDetail::where('seller_id', $authUserId)
                                    ->whereDeliveryStatus('pending')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_cancelled_orders'] = OrderDetail::where('seller_id', $authUserId)
                                    ->whereDeliveryStatus('cancelled')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_on_the_way_orders'] = OrderDetail::where('seller_id', $authUserId)
                                    ->whereDeliveryStatus('on_the_way')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_delivered_orders'] = OrderDetail::where('seller_id', $authUserId)
                                    ->whereDeliveryStatus('delivered')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
                                    
        $data['this_month_sold_amount'] = Order::where('seller_id', Auth::user()->id)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->sum('grand_total');
        $data['previous_month_sold_amount'] = Order::where('seller_id', Auth::user()->id)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', (Carbon::now()->month-1))
                                    ->sum('grand_total');
        
        $data['products'] = Product::where('user_id', $authUserId)->orderBy('num_of_sale', 'desc')->limit(12)->get();
        $data['last_7_days_sales'] = Order::where('created_at', '>=', Carbon::now()->subDays(7))
                                ->where('seller_id', '=', Auth::user()->id)
                                ->where('delivery_status', '=', 'delivered')
                                ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->get()->pluck('total', 'date');

        // Additional stats
        $data['total_categories'] = \App\Models\Category::count();
        $data['total_brands'] = \App\Models\Brand::count();
        
        $data['sale_this_month'] = OrderDetail::where('seller_id', $authUserId)
                                        ->where('delivery_status', 'delivered')
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->sum('price');

        $data['top_categories'] = Product::select('categories.name', 'categories.id', DB::raw('SUM(order_details.price) as total'))
            ->leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.user_id', $authUserId)
            ->where('order_details.delivery_status', 'delivered')
            ->groupBy('categories.id')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();

        return view('franchise.dashboard', $data);
    }
}
