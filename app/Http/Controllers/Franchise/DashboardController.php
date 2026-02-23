<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseEmployee;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\SubFranchise;
use Auth;
use Carbon\Carbon;
use DB;
use App\Models\Vendor;
use App\Models\DeliveryBoy;
use App\Models\VendorCommissionHistory;

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

        // New Metrics for Franchise Dashboard Request
        $data['total_subfranchises'] = 0;
        $data['approved_subfranchises'] = 0;
        $data['unapproved_subfranchises'] = 0;
        $data['total_vendors'] = 0;
        $data['total_delivery_boys'] = 0;
        $data['subfranchise_earnings_daily'] = 0;
        $data['subfranchise_earnings_weekly'] = 0;
        $data['subfranchise_earnings_monthly'] = 0;
        $data['subfranchise_earnings_yearly'] = 0;

        if ($user->user_type == 'franchise' && $user->franchise) {
            $data['balance'] = $user->franchise->balance;
            $franchise_id = $user->franchise->id;

            // Subfranchise Counts
            $data['total_subfranchises'] = SubFranchise::where('franchise_id', $franchise_id)->count();
            $data['approved_subfranchises'] = SubFranchise::where('franchise_id', $franchise_id)->where('status', 'approved')->count();
            $data['unapproved_subfranchises'] = SubFranchise::where('franchise_id', $franchise_id)->where('status', '!=', 'approved')->count();

            // Total Vendors (Direct + Subfranchise)
            $direct_vendors = Vendor::where('franchise_id', $franchise_id)->count();
            $sub_vendors = Vendor::whereIn('sub_franchise_id', function($q) use ($franchise_id){
                $q->select('id')->from('sub_franchises')->where('franchise_id', $franchise_id);
            })->count();
            $data['total_vendors'] = $direct_vendors + $sub_vendors;

            // Total Delivery Boys
            $data['total_delivery_boys'] = DeliveryBoy::where('franchise_id', $franchise_id)->count();

            // Earnings from Subfranchise
            $earnings_query = VendorCommissionHistory::where('franchise_id', $franchise_id)
                                ->whereNotNull('sub_franchise_id');

            $data['subfranchise_earnings_daily'] = (clone $earnings_query)->whereDate('created_at', Carbon::today())->sum('franchise_commission_amount');
            $data['subfranchise_earnings_weekly'] = (clone $earnings_query)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('franchise_commission_amount');
            $data['subfranchise_earnings_monthly'] = (clone $earnings_query)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('franchise_commission_amount');
            $data['subfranchise_earnings_yearly'] = (clone $earnings_query)->whereYear('created_at', Carbon::now()->year)->sum('franchise_commission_amount');
            
            $data['recent_earnings'] = VendorCommissionHistory::where('franchise_id', $franchise_id)
                                        ->latest()
                                        ->limit(10)
                                        ->get();
        } elseif ($user->user_type == 'sub_franchise' && $user->sub_franchise) {
            $data['balance'] = $user->sub_franchise->balance;
            $sub_franchise_id = $user->sub_franchise->id;

            $earnings_query = VendorCommissionHistory::where('sub_franchise_id', $sub_franchise_id);

            $data['subfranchise_earnings_daily'] = (clone $earnings_query)->whereDate('created_at', Carbon::today())->sum('sub_franchise_commission_amount');
            $data['subfranchise_earnings_weekly'] = (clone $earnings_query)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sub_franchise_commission_amount');
            $data['subfranchise_earnings_monthly'] = (clone $earnings_query)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('sub_franchise_commission_amount');
            $data['subfranchise_earnings_yearly'] = (clone $earnings_query)->whereYear('created_at', Carbon::now()->year)->sum('sub_franchise_commission_amount');

            $data['recent_earnings'] = VendorCommissionHistory::where('sub_franchise_id', $sub_franchise_id)
                                        ->latest()
                                        ->limit(10)
                                        ->get();
        }

        return view('franchise.dashboard', $data);
    }

    public function sales_report(Request $request)
    {
        $user = auth()->user();
        $date_range = $request->date_range;

        $histories = \App\Models\VendorCommissionHistory::query();

        if ($user->user_type == 'franchise' && $user->franchise) {
            $histories = $histories->where('franchise_id', $user->franchise->id);
        } elseif ($user->user_type == 'sub_franchise' && $user->sub_franchise) {
            $histories = $histories->where('sub_franchise_id', $user->sub_franchise->id);
        }

        if ($date_range) {
            $dates = explode(' to ', $date_range);
            $start_date = date('Y-m-d 00:00:00', strtotime($dates[0]));
            if (isset($dates[1])) {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));
            } else {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[0]));
            }
            $histories = $histories->whereBetween('created_at', [$start_date, $end_date]);
        }

        $histories = $histories->latest()->paginate(15);

        return view('franchise.sales_report', compact('histories', 'date_range'));
    }
}
