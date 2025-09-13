<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetType;
use App\Models\Brand;

class AssetTypeBrandController extends Controller
{
    /**
     * Display the assignment page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ดึงข้อมูล Asset Types ทั้งหมด พร้อมกับ Brands ที่ผูกอยู่แล้ว
        $assetTypes = AssetType::with('brands')->orderBy('name')->get();

        // ดึงข้อมูล Brands ทั้งหมดเพื่อใช้สร้าง Checkbox
        $brands = Brand::orderBy('name')->get();

        return view('settings.assign_brands', compact('assetTypes', 'brands'));
    }

    /**
     * Store the updated brand assignments for a specific asset type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'brands' => 'nullable|array', // brands ที่ส่งมาต้องเป็น array
            'brands.*' => 'exists:brands,id', // ตรวจสอบว่า brand id ทุกตัวมีอยู่จริง
        ]);

        $assetType = AssetType::find($request->asset_type_id);

        // ใช้ sync() เพื่ออัปเดตความสัมพันธ์ในตาราง pivot
        // มันจะลบของเก่าที่ไม่ถูกเลือกออก และเพิ่มของใหม่ที่ถูกเลือกเข้ามาให้โดยอัตโนมัติ
        $assetType->brands()->sync($request->brands ?? []);

        return back()->with('success', 'Brand assignments have been updated successfully!');
    }
}
