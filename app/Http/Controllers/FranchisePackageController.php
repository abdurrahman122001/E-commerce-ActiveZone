<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FranchisePackage;
use App\Models\FranchisePackageTranslation;
use App\Models\Category;

class FranchisePackageController extends Controller
{
    /**
     * Valid package types.
     */
    protected $validTypes = ['state_franchise', 'franchise', 'sub_franchise', 'vendor'];

    /**
     * Display a listing of packages filtered by type.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'franchise');
        if (!in_array($type, $this->validTypes)) {
            $type = 'franchise';
        }
        $franchise_packages = FranchisePackage::where('package_type', $type)->paginate(10);
        return view('backend.franchise.packages.index', compact('franchise_packages', 'type'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'franchise');
        if (!in_array($type, $this->validTypes)) {
            $type = 'franchise';
        }
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        return view('backend.franchise.packages.create', compact('categories', 'type'));
    }

    public function store(Request $request)
    {
        $type = $request->get('package_type', 'franchise');
        if (!in_array($type, $this->validTypes)) {
            $type = 'franchise';
        }

        $franchise_package = new FranchisePackage();
        $franchise_package->name = $request->name;
        $franchise_package->package_type = $type;
        $franchise_package->product_limit = $request->product_limit ?? 0;
        $franchise_package->duration = $request->duration;
        $franchise_package->price = $request->price;
        $franchise_package->logo = $request->logo;
        $franchise_package->features = $request->features;
        $franchise_package->save();

        $franchise_package_translation = new FranchisePackageTranslation();
        $franchise_package_translation->franchise_package_id = $franchise_package->id;
        $franchise_package_translation->name = $request->name;
        $franchise_package_translation->lang = $request->lang ?? 'en';
        $franchise_package_translation->save();

        flash(translate('Package has been inserted successfully'))->success();
        return redirect()->route('franchise_packages.index', ['type' => $type]);
    }

    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $franchise_package = FranchisePackage::findOrFail($id);
        $type = $franchise_package->package_type ?? 'franchise';
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        return view('backend.franchise.packages.edit', compact('franchise_package', 'categories', 'lang', 'type'));
    }

    public function update(Request $request, $id)
    {
        $franchise_package = FranchisePackage::findOrFail($id);
        $type = $franchise_package->package_type ?? 'franchise';

        if ($request->lang == 'en') {
            $franchise_package->name = $request->name;
            if ($request->has('package_type')) {
                $franchise_package->package_type = $request->package_type;
            }
        }
        $franchise_package->product_limit = $request->product_limit ?? 0;
        $franchise_package->duration = $request->duration;
        $franchise_package->price = $request->price;
        $franchise_package->logo = $request->logo;
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
        return redirect()->route('franchise_packages.index', ['type' => $type]);
    }

    public function destroy($id)
    {
        $franchise_package = FranchisePackage::findOrFail($id);
        $type = $franchise_package->package_type ?? 'franchise';
        FranchisePackage::destroy($id);
        flash(translate('Franchise Package has been deleted successfully'))->success();
        return redirect()->route('franchise_packages.index', ['type' => $type]);
    }
}
