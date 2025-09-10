<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\Brand;
use App\Models\ItAsset;
use Livewire\Attributes\On; // เพิ่ม On attribute สำหรับ Event Listener

class AssetForm extends Component
{
    // Properties สำหรับ Dropdowns
    public $categories = [];
    public $types = [];
    public $brands = [];

    // Properties สำหรับเก็บค่าที่เลือก
    public $selectedCategory = null;
    public $selectedType = null;
    public $selectedBrand = null;

    // Property สำหรับเก็บข้อมูล Asset
    public ?ItAsset $asset = null; // ทำให้เป็น nullable เพราะตอนแรกอาจจะยังไม่มี

    // Property สำหรับควบคุมการแสดง Modal
    public $showModal = false;

    // ทำให้ assetId เป็น nullable เพราะเราจะรับค่าผ่าน event แทน
    public function mount($assetId = null)
    {
        // โหลด Category มาเตรียมไว้เสมอ
        $thisकर्मियों = AssetCategory::orderBy('name')->get();

        if ($assetId) {
            $this->setAsset($assetId);
        }
    }

    // สร้าง Listener เพื่อรอรับสัญญาณจากปุ่มกด
    #[On('edit-asset')]
    public function editAsset($assetId)
    {
        $this->setAsset($assetId);
        $this->showModal = true;
    }

    // ฟังก์ชันสำหรับตั้งค่าข้อมูล Asset
    public function setAsset($assetId)
    {
        $this->asset = ItAsset::with('type.assetCategory', 'brand')->findOrFail($assetId);

        if ($this->asset->type) {
            $this->selectedCategory = $this->asset->type->asset_category_id;
            $this->selectedType = $this->asset->asset_type_id;
            $this->selectedBrand = $this->asset->brand_id;

            $this->types = AssetType::where('asset_category_id', $this->selectedCategory)->get();
            if ($this->selectedType) {
                $type = AssetType::find($this->selectedType);
                $this->brands = $type ? $type->brands : collect();
            }
        }
    }

    // ฟังก์ชันสำหรับปิด Modal
    public function closeModal()
    {
        $this->showModal = false;
    }

    // ทำงานเมื่อ User เลือก Category
    public function updatedSelectedCategory($categoryId)
    {
        $this->types = $categoryId ? AssetType::where('asset_category_id', $categoryId)->orderBy('name')->get() : collect();
        $this->reset(['selectedType', 'selectedBrand']);
        $this->brands = collect();
    }

    // ทำงานเมื่อ User เลือก Type
    public function updatedSelectedType($typeId)
    {
        $this->brands = $typeId ? AssetType::find($typeId)->brands()->orderBy('name')->get() : collect();
        $this->reset('selectedBrand');
    }

    public function render()
    {
        return view('livewire.asset-form');
    }
}
