<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Models\Upload;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductStockService;
use App\Services\FrequentlyBoughtProductService;
use AizPackages\CombinationGenerate\Services\CombinationService;
use Auth;
use Artisan;
use Str;

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

        if ($request->hasFile('pdf_file')) {
            $pdf = $this->upload_thumbnail_img($request->file('pdf_file'));
            $request->merge(['pdf' => $pdf]);
        }

        if ($request->hasFile('meta_img_file')) {
            $meta_img = $this->upload_thumbnail_img($request->file('meta_img_file'));
            $request->merge(['meta_img' => $meta_img]);
        }

        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'thumbnail_img_file', 'photos_file', 'pdf_file', 'meta_img_file'
        ]));
        
        $request->merge(['product_id' => $product->id]);

        // Product categories
        if ($request->has('category_ids')) {
            $product->categories()->attach($request->category_ids);
        }

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        // Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        // Frequently Bought Products
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id', 'frequently_bought_selection_type', 'fq_bought_product_ids', 'fq_bought_product_category_id'
        ]));

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

        if ($request->hasFile('pdf_file')) {
            $pdf = $this->upload_thumbnail_img($request->file('pdf_file'));
            $request->merge(['pdf' => $pdf]);
        }

        if ($request->hasFile('meta_img_file')) {
            $meta_img = $this->upload_thumbnail_img($request->file('meta_img_file'));
            $request->merge(['meta_img' => $meta_img]);
        }

        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'thumbnail_img_file', 'photos_file', 'pdf_file', 'meta_img_file'
        ]), $product);

        if ($request->has('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }

        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $request->merge(['product_id' => $product->id]);
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        // Frequently Bought Products
        $product->frequently_bought_products()->delete();
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id', 'frequently_bought_selection_type', 'fq_bought_product_ids', 'fq_bought_product_category_id'
        ]));

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
            "pdf" => "document",
            "mp4" => "video",
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
            $upload->type = $type[$extension] ?? "archive";
            $upload->file_size = $file->getSize();
            $upload->save();

            $file->move(public_path('uploads/all'), $filename);

            return $upload->id;
        }
        return null;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    public function product_search(Request $request)
    {
        $products = $this->productService->product_search($request->except(['_token']));
        return view('partials.product.product_search', compact('products'));
    }

    public function get_selected_products(Request $request){
        $products = Product::whereIn('id', $request->product_ids)->get();
        return view('partials.product.frequently_bought_selected_product', compact('products'));
    }
}
