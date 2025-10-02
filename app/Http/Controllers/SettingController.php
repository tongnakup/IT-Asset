<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetType;
use App\Models\AssetStatus;
use App\Models\AssetCategory;
use App\Models\Position;
use App\Models\Department;
use App\Models\Location;
use App\Models\Brand;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $assetTypes = AssetType::with('assetCategory', 'brands')->orderBy('name')->get();
        $assetStatuses = AssetStatus::orderBy('name')->get();
        $assetCategories = AssetCategory::orderBy('name')->get();
        $positions = Position::with('department')->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('settings.index', compact(
            'assetTypes',
            'assetStatuses',
            'assetCategories',
            'positions',
            'departments',
            'locations',
            'brands'
        ));
    }

    // --- Asset Category Methods ---
    public function storeCategory(Request $request)
    {
        $request->merge(['name' => trim($request->name)]);
        $request->validate(
            ['name' => 'required|string|max:255|unique:asset_categories,name'],
            ['name.unique' => 'Category นี้มีอยู่ในระบบแล้ว']
        );
        AssetCategory::create($request->all());
        Cache::forget('asset_categories');
        return back()->with('success', 'Asset Category added successfully.');
    }

    // --- Asset Type Methods ---
    public function storeType(Request $request)
    {
        $request->merge(['name' => trim($request->name)]);
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:asset_types,name',
                'asset_category_id' => 'required|exists:asset_categories,id'
            ],
            ['name.unique' => 'Type นี้มีอยู่ในระบบแล้ว']
        );
        AssetType::create($request->all());
        Cache::forget('asset_types');
        return back()->with('success', 'Asset Type added successfully.');
    }

    // --- Asset Status Methods ---
    public function storeStatus(Request $request)
    {
        $request->merge(['name' => trim($request->name)]);
        $request->validate(
            ['name' => 'required|string|max:255|unique:asset_statuses,name'],
            ['name.unique' => 'Status นี้มีอยู่ในระบบแล้ว']
        );
        AssetStatus::create($request->all());
        Cache::forget('asset_statuses');
        return back()->with('success', 'Asset Status added successfully.');
    }

    // --- Position Methods ---
    public function storePosition(Request $request)
    {
        $request->merge(['name' => trim($request->name)]);
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:positions,name',
                'department_id' => 'required|exists:departments,id'
            ],
            ['name.unique' => 'Position นี้มีอยู่ในระบบแล้ว']
        );
        Position::create($request->all());
        Cache::forget('positions');
        return back()->with('success', 'Position added successfully.');
    }

    // --- Department Methods ---
    public function storeDepartment(Request $request)
    {
        $request->merge(['name' => trim($request->name)]);
        $request->validate(
            ['name' => 'required|string|max:255|unique:departments,name'],
            ['name.unique' => 'Department นี้มีอยู่ในระบบแล้ว']
        );
        Department::create($request->all());
        Cache::forget('departments');
        return back()->with('success', 'Department added successfully.');
    }

    // --- Location Methods ---
    public function storeLocation(Request $request)
    {
        $request->merge(['name' => trim($request->name)]);
        $request->validate(
            ['name' => 'required|string|max:255|unique:locations,name'],
            ['name.unique' => 'Location นี้มีอยู่ในระบบแล้ว']
        );
        Location::create($request->all());
        Cache::forget('locations_collection');
        return back()->with('success', 'Location added successfully.');
    }

    // --- Brand Methods ---
    public function storeBrand(Request $request)
    {
        $request->merge(['name' => trim($request->name)]);
        $request->validate(
            ['name' => 'required|string|max:255|unique:brands,name'],
            ['name.unique' => 'Brand นี้มีอยู่ในระบบแล้ว']
        );
        Brand::create($request->all());
        Cache::forget('brands');
        return back()->with('success', 'Brand added successfully.');
    }


    public function destroyCategory(AssetCategory $assetCategory)
    {
        $assetCategory->delete();
        Cache::forget('asset_categories');
        return back()->with('success', 'Asset Category deleted successfully.');
    }
    public function destroyType(AssetType $assetType)
    {
        $assetType->delete();
        Cache::forget('asset_types');
        return back()->with('success', 'Asset Type deleted successfully.');
    }
    public function destroyStatus(AssetStatus $assetStatus)
    {
        $assetStatus->delete();
        Cache::forget('asset_statuses');
        return back()->with('success', 'Asset Status deleted successfully.');
    }
    public function destroyPosition(Position $position)
    {
        $position->delete();
        Cache::forget('positions');
        return back()->with('success', 'Position deleted successfully.');
    }
    public function destroyDepartment(Department $department)
    {
        $department->delete();
        Cache::forget('departments');
        return back()->with('success', 'Department deleted successfully.');
    }
    public function destroyLocation(Location $location)
    {
        $location->delete();
        Cache::forget('locations_collection');
        return back()->with('success', 'Location deleted successfully.');
    }
    public function destroyBrand(Brand $brand)
    {
        $brand->delete();
        Cache::forget('brands');
        return back()->with('success', 'Brand deleted successfully.');
    }
}
