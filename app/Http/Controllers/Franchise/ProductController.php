<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTranslation;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Color;
use App\Models\User;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductStockService;
use App\Services\FrequentlyBoughtProductService;
use Auth;
use Artisan;

class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productStockService;
    protected $frequentlyBoughtProductService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductStockService $productStockService,
        FrequentlyBoughtProductService $frequentlyBoughtProductService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productStockService = $productStockService;
        $this->frequentlyBoughtProductService = $frequentlyBoughtProductService;
    }

    public function index(Request $request)
    {
        $search = null;
        $user_id = $this->getUserId();
        
        $products = Product::where('user_id', $user_id)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(15);
        $route_prefix = $this->getRoutePrefix();
        return view('franchise.product.index', compact('products', 'search', 'route_prefix'));
    }

    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        $route_prefix = $this->getRoutePrefix();
        return view('franchise.product.create', compact('categories', 'route_prefix'));
    }

    public function store(ProductRequest $request)
    {
        if ($this->getGuard() == 'franchise_employee') {
            $request->merge(['user_id' => $this->getUserId()]);
        }

        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type'
        ]));
        
        $request->merge(['product_id' => $product->id]);

        // Product categories
        $product->categories()->attach($request->category_ids);

        // Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang', 'name', 'unit', 'description', 'product_id'
        ]));

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route($this->getRoutePrefix() . '.products');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        if ($this->getUserId() != $product->user_id) {
            abort(403);
        }
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        $tags = json_decode($product->tags);
        $route_prefix = $this->getRoutePrefix();
        return view('franchise.product.edit', compact('product', 'categories', 'tags', 'route_prefix'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        if ($this->getUserId() != $product->user_id) {
            abort(403);
        }

        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type'
        ]), $product);

        $product->categories()->sync($request->category_ids);

        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return back();
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($this->getUserId() != $product->user_id) {
            abort(403);
        }

        $product->categories()->detach();
        $product->stocks()->delete();
        Product::destroy($id);

        flash(translate('Product has been deleted successfully'))->success();
        return back();
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        if ($this->getUserId() != $product->user_id) {
            return 0;
        }
        $product->published = $request->status;
        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    // Helper methods
    private function getGuard()
    {
        if (Auth::guard('franchise_employee')->check()) {
            return 'franchise_employee';
        }
        return 'web'; 
    }

    private function getRoutePrefix()
    {
        return $this->getGuard() == 'franchise_employee' ? 'franchise.employee' : 'franchise';
    }

    private function getUserId()
    {
        if ($this->getGuard() == 'franchise_employee') {
            $employee = Auth::guard('franchise_employee')->user();
            return $employee->franchise_level == 'SUB' ? $employee->subFranchise->user_id : $employee->franchise->user_id;
        }
        return Auth::user()->id;
    }
}
