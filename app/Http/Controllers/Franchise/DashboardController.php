<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\FranchiseEmployee;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\SubFranchise;
use Auth;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $authUserId = $user->id;
        
        $all_seller_ids = [$authUserId];
        if ($user->user_type == 'franchise' && $user->franchise) {
            $franchise_id = $user->franchise->id;
            $vendor_user_ids = \App\Models\Vendor::where('franchise_id', $franchise_id)->pluck('user_id')->toArray();
            $sub_franchise_user_ids = \App\Models\SubFranchise::where('franchise_id', $franchise_id)->pluck('user_id')->toArray();
            $sub_franchise_vendor_user_ids = \App\Models\Vendor::whereIn('sub_franchise_id', function($query) use ($franchise_id) {
                $query->select('id')->from('sub_franchises')->where('franchise_id', $franchise_id);
            })->pluck('user_id')->toArray();
            $all_seller_ids = array_unique(array_merge($all_seller_ids, $vendor_user_ids, $sub_franchise_user_ids, $sub_franchise_vendor_user_ids));
        } elseif ($user->user_type == 'sub_franchise' && $user->sub_franchise) {
            $sub_franchise_id = $user->sub_franchise->id;
            $vendor_user_ids = \App\Models\Vendor::where('sub_franchise_id', $sub_franchise_id)->pluck('user_id')->toArray();
            $all_seller_ids = array_unique(array_merge($all_seller_ids, $vendor_user_ids));
        }

        $data['total_products'] = Product::whereIn('user_id', $all_seller_ids)->count();
        $data['total_sales'] = OrderDetail::whereIn('seller_id', $all_seller_ids)->where('delivery_status', 'delivered')->sum('price');
        $data['pending_orders'] = OrderDetail::whereIn('seller_id', $all_seller_ids)->where('delivery_status', 'pending')->count();
        $data['delivered_orders'] = OrderDetail::whereIn('seller_id', $all_seller_ids)->where('delivery_status', 'delivered')->count();
        
        $data['this_month_pending_orders'] = OrderDetail::whereIn('seller_id', $all_seller_ids)
                                    ->whereDeliveryStatus('pending')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_cancelled_orders'] = OrderDetail::whereIn('seller_id', $all_seller_ids)
                                    ->whereDeliveryStatus('cancelled')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_on_the_way_orders'] = OrderDetail::whereIn('seller_id', $all_seller_ids)
                                    ->whereDeliveryStatus('on_the_way')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_delivered_orders'] = OrderDetail::whereIn('seller_id', $all_seller_ids)
                                    ->whereDeliveryStatus('delivered')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
                                    
        $data['this_month_sold_amount'] = Order::whereIn('seller_id', $all_seller_ids)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->sum('grand_total');
        $data['previous_month_sold_amount'] = Order::whereIn('seller_id', $all_seller_ids)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', (Carbon::now()->month-1))
                                    ->sum('grand_total');
        
        $data['products'] = Product::whereIn('user_id', $all_seller_ids)->orderBy('num_of_sale', 'desc')->limit(12)->get();
        $data['last_7_days_sales'] = Order::where('created_at', '>=', Carbon::now()->subDays(7))
                                ->whereIn('seller_id', $all_seller_ids)
                                ->where('delivery_status', '=', 'delivered')
                                ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->get()->pluck('total', 'date');

        // Additional stats
        $data['total_categories'] = \App\Models\Category::count();
        $data['total_brands'] = \App\Models\Brand::count();
        
        // Employee count
        $employeeQuery = FranchiseEmployee::query();
        if ($user->user_type == 'franchise' && $user->franchise) {
            $franchiseId = $user->franchise->id;
            $subFranchiseIds = SubFranchise::where('franchise_id', $franchiseId)->pluck('id')->toArray();
            $employeeQuery->where(function ($q) use ($user, $subFranchiseIds) {
                $q->where('created_by', $user->id)
                  ->orWhereIn('sub_franchise_id', $subFranchiseIds);
            });
        } elseif ($user->user_type == 'sub_franchise' && $user->sub_franchise) {
            $employeeQuery->where('sub_franchise_id', $user->sub_franchise->id);
        }
        $data['total_employees'] = $employeeQuery->count();
        
        $data['sale_this_month'] = OrderDetail::whereIn('seller_id', $all_seller_ids)
                                        ->where('delivery_status', 'delivered')
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->sum('price');

        $data['top_categories'] = Product::select('categories.name', 'categories.id', DB::raw('SUM(order_details.price) as total'))
            ->leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('products.user_id', $all_seller_ids)
            ->where('order_details.delivery_status', 'delivered')
            ->groupBy('categories.id')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();

        return view('franchise.dashboard', $data);
    }
}
