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
    /**
     * Display the settings page.
     */
    public function index()
    {
        $assetTypes = AssetType::with('assetCategory')->orderBy('name')->get();
        $assetStatuses = AssetStatus::orderBy('name')->get();
        $assetCategories = AssetCategory::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
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
        $request->validate(['name' => 'required|string|max:255|unique:asset_categories,name']);
        AssetCategory::create($request->all());
        Cache::forget('asset_categories'); 
        return back()->with('success', 'Asset Category added successfully.');
    }

    public function destroyCategory(AssetCategory $assetCategory)
    {
        $assetCategory->delete();
        Cache::forget('asset_categories'); 
        return back()->with('success', 'Asset Category deleted successfully.');
    }

    // --- Asset Type Methods ---
    public function storeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:asset_types,name',
            'asset_category_id' => 'required|exists:asset_categories,id'
        ]);
        AssetType::create($request->all());
        Cache::forget('asset_types'); 
        return back()->with('success', 'Asset Type added successfully.');
    }

    public function destroyType(AssetType $assetType)
    {
        $assetType->delete();
        Cache::forget('asset_types'); 
        return back()->with('success', 'Asset Type deleted successfully.');
    }

    // --- Asset Status Methods ---
    public function storeStatus(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:asset_statuses,name']);
        AssetStatus::create($request->all());
        Cache::forget('asset_statuses'); 
        return back()->with('success', 'Asset Status added successfully.');
    }

    public function destroyStatus(AssetStatus $assetStatus)
    {
        $assetStatus->delete();
        Cache::forget('asset_statuses'); 
        return back()->with('success', 'Asset Status deleted successfully.');
    }

    // --- Position Methods ---
    public function storePosition(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:positions,name']);
        Position::create($request->all());
        Cache::forget('positions'); 
        return back()->with('success', 'Position added successfully.');
    }

    public function destroyPosition(Position $position)
    {
        $position->delete();
        Cache::forget('positions'); 
        return back()->with('success', 'Position deleted successfully.');
    }

    // --- Department Methods ---
    public function storeDepartment(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:departments,name']);
        Department::create($request->all());
        Cache::forget('departments'); 
        return back()->with('success', 'Department added successfully.');
    }

    public function destroyDepartment(Department $department)
    {
        $department->delete();
        Cache::forget('departments'); 
        return back()->with('success', 'Department deleted successfully.');
    }

    // --- Location Methods ---
    public function storeLocation(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:locations,name']);
        Location::create($request->all());
        Cache::forget('locations'); 
        return back()->with('success', 'Location added successfully.');
    }

    public function destroyLocation(Location $location)
    {
        $location->delete();
        Cache::forget('locations'); 
        return back()->with('success', 'Location deleted successfully.');
    }

    // ▼▼▼ 4. เพิ่มฟังก์ชันสำหรับจัดการ Brands ใหม่ทั้งหมด ▼▼▼
    // --- Brand Methods ---
    public function storeBrand(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:brands,name']);
        Brand::create($request->all());
        Cache::forget('brands');
        return back()->with('success', 'Brand added successfully.');
    }

    public function destroyBrand(Brand $brand)
    {
        $brand->delete();
        Cache::forget('brands');
        return back()->with('success', 'Brand deleted successfully.');
    }
}
