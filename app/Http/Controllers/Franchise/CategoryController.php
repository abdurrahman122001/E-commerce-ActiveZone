<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Str;
use Auth;
use Cache;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::user()->id)->paginate(15);
        return view('franchise.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('franchise.category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->user_id = Auth::user()->id; // Track who created the category
        $category->order_level = 0;
        $category->digital = $request->digital ?? 0;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;
            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        }

        if ($request->slug != null) {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        } else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5);
        }

        $category->save();

        $category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('franchise.categories.index');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        if ($category->user_id != Auth::user()->id) {
            abort(403);
        }
        $categories = Category::where('parent_id', 0)
            ->where('digital', $category->digital)
            ->with('childrenCategories')
            ->get();
        return view('franchise.category.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($category->user_id != Auth::user()->id) {
            abort(403);
        }

        $category->name = $request->name;
        $category->digital = $request->digital ?? 0;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;
            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        } else {
            $category->parent_id = 0;
            $category->level = 0;
        }

        if ($request->slug != null) {
            $category->slug = strtolower($request->slug);
        } else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5);
        }

        $category->save();

        $category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Category has been updated successfully'))->success();
        return redirect()->route('franchise.categories.index');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->user_id != Auth::user()->id) {
            abort(403);
        }
        
        // Check if products exist in this category
        if (count($category->products) > 0) {
            flash(translate('Category cannot be deleted because it has products'))->error();
            return back();
        }

        $category->delete();
        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('franchise.categories.index');
    }
}
