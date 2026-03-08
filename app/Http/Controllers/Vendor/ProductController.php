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
        if ($request->hasFile('thumbnail_img_file')) {
            $thumbnail_img = $this->upload_thumbnail_img($request->file('thumbnail_img_file'));
            $request->merge(['thumbnail_img' => $thumbnail_img]);
        }

        if ($request->hasFile('photos_file')) {
            $photos = [];
            foreach ($request->file('photos_file') as $file) {
                $photos[] = $this->upload_thumbnail_img($file);
            }
            $request->merge(['photos' => implode(',', array_filter($photos))]);
        }

        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'thumbnail_img_file', 'photos_file'
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

        if ($request->hasFile('thumbnail_img_file')) {
            $thumbnail_img = $this->upload_thumbnail_img($request->file('thumbnail_img_file'));
            $request->merge(['thumbnail_img' => $thumbnail_img]);
        }

        if ($request->hasFile('photos_file')) {
            $photos = [];
            foreach ($request->file('photos_file') as $file) {
                $photos[] = $this->upload_thumbnail_img($file);
            }
            $request->merge(['photos' => implode(',', array_filter($photos))]);
        }

        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'thumbnail_img_file', 'photos_file'
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

    private function upload_thumbnail_img($file)
    {
        $type = [
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
        ];
        $extension = strtolower($file->getClientOriginalExtension());
        if (isset($type[$extension])) {
            $filename = str_replace(' ', '_', $file->getClientOriginalName());
            $filename = time() . '_' . $filename;

            $upload = new \App\Models\Upload;
            $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $upload->extension = $extension;
            $upload->file_name = 'uploads/all/' . $filename;
            $upload->user_id = Auth::user()->id;
            $upload->type = $type[$extension];
            $upload->file_size = $file->getSize();
            $upload->save();

            $file->move(public_path('uploads/all'), $filename);

            return $upload->id;
        }
        return null;
    }
}
