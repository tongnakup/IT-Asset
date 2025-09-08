<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\Brand;
use App\Models\ItAsset; // เพิ่ม Model ItAsset เข้ามา

class AssetForm extends Component
{
    // Properties สำหรับ Dropdowns
    public $categories = [];
    public $types = [];
    public $brands = [];

    // Properties สำหรับเก็บค่าที่เลือก
    public $selectedCategory = null;
    public $selectedType = null;
    public $selectedBrand = null; // ใช้ชื่อนี้เพื่อให้ตรงกับ Blade

    // Property สำหรับเก็บข้อมูล Asset
    public ItAsset $asset;

    // เราจะใช้ $assetId ที่รับเข้ามาเพื่อค้นหาข้อมูล
    public function mount($assetId)
    {
        // โหลดข้อมูล Asset ที่จะแก้ไข พร้อมข้อมูลที่เกี่ยวข้อง
        $this->asset = ItAsset::with('assetType.assetCategory')->findOrFail($assetId);

        // 1. โหลด Category ทั้งหมดมาเตรียมไว้
        $this->categories = AssetCategory::orderBy('name')->get();

        // 2. ตั้งค่า Dropdown เริ่มต้นจากข้อมูลของ Asset
        if ($this->asset->assetType) {
            $this->selectedCategory = $this->asset->assetType->asset_category_id;
            $this->selectedType = $this->asset->asset_type_id;
            $this->selectedBrand = $this->asset->brand_id;

            // 3. โหลดรายการ Types และ Brands ที่สอดคล้องกับข้อมูลเริ่มต้น
            $this->types = AssetType::where('asset_category_id', $this->selectedCategory)->get();
            $this->brands = AssetType::find($this->selectedType)->brands;
        }
    }

    // ทำงานเมื่อ User เลือก Category
    public function updatedSelectedCategory($categoryId)
    {
        if (!is_null($categoryId) && $categoryId !== '') {
            $this->types = AssetType::where('asset_category_id', $categoryId)->orderBy('name')->get();
        } else {
            $this->types = collect();
        }
        // รีเซ็ตค่า Type และ Brand ที่เลือกไว้
        $this->reset(['selectedType', 'selectedBrand']);
        $this->brands = collect();
    }

    // ---- นี่คือฟังก์ชันที่ขาดไป เรายกมาจาก CreateAssetForm ----
    // ทำงานเมื่อ User เลือก Type
    public function updatedSelectedType($typeId)
    {
        if (!is_null($typeId) && $typeId !== '') {
            $type = AssetType::find($typeId);
            if ($type) {
                $this->brands = $type->brands()->orderBy('name')->get();
            }
        } else {
            $this->brands = collect();
        }
        // รีเซ็ตเฉพาะ Brand ที่เลือกไว้
        $this->reset('selectedBrand');
    }

    public function render()
    {
        return view('livewire.asset-form');
    }
}
