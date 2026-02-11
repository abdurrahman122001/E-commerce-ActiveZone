<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FranchisePackage;
use App\Models\FranchisePackageTranslation;
use App\Models\Category;

class FranchisePackageController extends Controller
{
    public function index()
    {
        $franchise_packages = FranchisePackage::paginate(10);
        return view('backend.franchise.packages.index', compact('franchise_packages'));
    }

    public function create()
    {
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        return view('backend.franchise.packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $franchise_package = new FranchisePackage();
        $franchise_package->name = $request->name;
        $franchise_package->product_limit = $request->product_limit;
        $franchise_package->duration = $request->duration;
        $franchise_package->price = $request->price;
        $franchise_package->logo = $request->logo;
        $franchise_package->category_id = $request->category_id;
        $franchise_package->features = $request->features;
        $franchise_package->save();

        $franchise_package_translation = new FranchisePackageTranslation();
        $franchise_package_translation->franchise_package_id = $franchise_package->id;
        $franchise_package_translation->name = $request->name;
        $franchise_package_translation->lang = $request->lang ?? 'en';
        $franchise_package_translation->save();

        flash(translate('Franchise Package has been inserted successfully'))->success();
        return redirect()->route('franchise_packages.index');
    }

    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $franchise_package = FranchisePackage::findOrFail($id);
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        return view('backend.franchise.packages.edit', compact('franchise_package', 'categories', 'lang'));
    }

    public function update(Request $request, $id)
    {
        $franchise_package = FranchisePackage::findOrFail($id);
        if ($request->lang == 'en') {
            $franchise_package->name = $request->name;
        }
        $franchise_package->product_limit = $request->product_limit;
        $franchise_package->duration = $request->duration;
        $franchise_package->price = $request->price;
        $franchise_package->logo = $request->logo;
        $franchise_package->category_id = $request->category_id;
        $franchise_package->features = $request->features;
        $franchise_package->save();

        $franchise_package_translation = FranchisePackageTranslation::where('franchise_package_id', $franchise_package->id)->where('lang', $request->lang)->first();
        if (!$franchise_package_translation) {
            $franchise_package_translation = new FranchisePackageTranslation();
            $franchise_package_translation->franchise_package_id = $franchise_package->id;
            $franchise_package_translation->lang = $request->lang;
        }
        $franchise_package_translation->name = $request->name;
        $franchise_package_translation->save();

        flash(translate('Franchise Package has been updated successfully'))->success();
        return redirect()->route('franchise_packages.index');
    }

    public function destroy($id)
    {
        FranchisePackage::destroy($id);
        flash(translate('Franchise Package has been deleted successfully'))->success();
        return redirect()->route('franchise_packages.index');
    }
}
