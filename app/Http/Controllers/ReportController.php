<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CommissionHistory;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Search;
use App\Models\Shop;
use Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:in_house_product_sale_report'])->only('in_house_sale_report');
        $this->middleware(['permission:seller_products_sale_report'])->only('seller_sale_report');
        $this->middleware(['permission:products_stock_report'])->only('stock_report');
        $this->middleware(['permission:product_wishlist_report'])->only('wish_report');
        $this->middleware(['permission:user_search_report'])->only('user_search_report');
        $this->middleware(['permission:commission_history_report'])->only('commission_history');
        $this->middleware(['permission:wallet_transaction_report'])->only('wallet_transaction_history');
        $this->middleware(['permission:vendor_referral_history_report'])->only('vendor_referral_history');
    }

    public function vendor_referral_history(Request $request)
    {
        $referrer_id = null;
        $date_range = null;

        $referral_history = \App\Models\VendorReferralCommissionHistory::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range_parts = explode(" / ", $request->date_range);
            $referral_history = $referral_history->whereDate('created_at', '>=', $date_range_parts[0]);
            $referral_history = $referral_history->whereDate('created_at', '<=', $date_range_parts[1]);
        }
        
        if ($request->referrer_id) {
            $referrer_id = $request->referrer_id;
            $referral_history = $referral_history->where('referrer_vendor_id', $referrer_id);
        }

        $referral_history = $referral_history->paginate(15);
        $referrers = \App\Models\Vendor::whereIn('id', \App\Models\VendorReferralCommissionHistory::pluck('referrer_vendor_id'))->get();

        return view('backend.reports.vendor_referral_history_report', compact('referral_history', 'referrer_id', 'date_range', 'referrers'));
    }

    public function stock_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products', 'sort_by'));
    }

    public function in_house_sale_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('num_of_sale', 'desc')->where('added_by', 'admin');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.in_house_sale_report', compact('products', 'sort_by'));
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by = null;
        // $sellers = User::where('user_type', 'seller')->orderBy('created_at', 'desc');
        $sellers = Shop::with('user')->orderBy('created_at', 'desc');
        if ($request->has('verification_status')) {
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers', 'sort_by'));
    }

    public function wish_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(10);
        return view('backend.reports.wish_report', compact('products', 'sort_by'));
    }

    public function user_search_report(Request $request)
    {
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }

    public function commission_history(Request $request)
    {
        $seller_id = null;
        $date_range = null;

        if (Auth::user()->user_type == 'seller') {
            $seller_id = Auth::user()->id;
        }
        if ($request->seller_id) {
            $seller_id = $request->seller_id;
        }

        $commission_history = CommissionHistory::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id) {

            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }

        $commission_history = $commission_history->paginate(10);
        if (Auth::user()->user_type == 'seller') {
            return view('seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }

    public function wallet_transaction_history(Request $request)
    {
        $user_id = null;
        $date_range = null;

        if ($request->user_id) {
            $user_id = $request->user_id;
        }

        $users_with_wallet = User::whereIn('id', function ($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($user_id) {
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }

        $wallets = $wallet_history->paginate(10);

        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }

}
