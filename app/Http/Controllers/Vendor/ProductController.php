<?php

namespace App\Http\Controllers\Vendor;

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
        $products = Product::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(15);
        return view('vendors.product.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('vendors.product.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type'
        ]));
        
        $request->merge(['product_id' => $product->id]);

        // Product categories
        if ($request->has('category_ids')) {
            $product->categories()->attach($request->category_ids);
        }

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

        return redirect()->route('vendor.products');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        if (Auth::user()->id != $product->user_id) {
            abort(403);
        }
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        $tags = json_decode($product->tags);
        return view('vendors.product.edit', compact('product', 'categories', 'tags'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        if (Auth::user()->id != $product->user_id) {
            abort(403);
        }

        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type'
        ]), $product);

        if ($request->has('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }

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
        if (Auth::user()->id != $product->user_id) {
            abort(403);
        }

        $product->categories()->detach();
        $product->stocks()->delete();
        Product::destroy($id);

        flash(translate('Product has been deleted successfully'))->success();
        return back();
    }
}
