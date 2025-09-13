<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\Brand;
use App\Models\ItAsset;
use Livewire\Attributes\On;

class AssetForm extends Component
{
    // Properties for Dropdowns
    public $categories = [];
    public $types = [];
    public $brands = [];

    // Properties for keeping track of selections
    public $selectedCategory = null;
    public $selectedType = null;
    public $selectedBrand = null;

    // Property for the asset data
    public ?ItAsset $asset = null;

    // Property for controlling the modal's visibility
    public $showModal = false;

    // mount() runs only once, when the component is first created
    public function mount($assetId = null)
    {
        // Always pre-load all categories for the dropdown
        $this->categories = AssetCategory::orderBy('name')->get();

        if ($assetId) {
            $this->setAssetData($assetId);
        }
    }

    // Listener for the button click event
    #[On('edit-asset')]
    public function editAsset($assetId)
    {
        // ▼▼▼ [แก้ไข] ลบ setAsset() ของเก่าออก แล้วใช้ฟังก์ชันสำหรับดีบักนี้แทน ▼▼▼

        // ขั้นตอนที่ 1: รีเซ็ตค่าเก่าทั้งหมดเพื่อความแน่นอน
        $this->reset(['asset', 'selectedCategory', 'selectedType', 'selectedBrand', 'types', 'brands']);

        // ขั้นตอนที่ 2: หา Asset และดูข้อมูลเบื้องต้น
        $asset = ItAsset::find($assetId);
        if (!$asset) {
            dd('หา Asset ไม่เจอด้วย ID:', $assetId);
        }

        // ขั้นตอนที่ 3: โหลด Relationship ของ Type และ Brands ที่ผูกกับ Type นั้นๆ
        $asset->load('type.brands');

        // ขั้นตอนที่ 4: เตรียมข้อมูลสำหรับแสดงผล
        $this->asset = $asset;
        $this->selectedBrand = $asset->brand_id;

        // ดึงรายการ Brands จากข้อมูลที่โหลดมาแล้ว
        $brandsForDropdown = $asset->type?->brands()->orderBy('name')->get() ?? collect();

        // ขั้นตอนที่ 5: แสดงผลข้อมูลทั้งหมดก่อนจะไปที่ View
        dd([
            'Asset ที่โหลดได้ (พร้อม Type และ Brands ที่ผูกกับ Type)' => $this->asset->toArray(),
            'Brands ที่จะนำไปสร้าง Dropdown' => $brandsForDropdown->pluck('name', 'id')->toArray(),
            'Brand ID ที่ควรจะถูกเลือก' => $this->selectedBrand,
        ]);

        $this->showModal = true;
    }

    // ฟังก์ชันสำหรับตั้งค่าข้อมูล Asset (ฟังก์ชันนี้จะไม่ได้ถูกใช้ชั่วคราว)
    public function setAssetData($assetId)
    {
        $this->asset = ItAsset::with('type.brands', 'brand')->findOrFail($assetId);
        if ($this->asset->type) {
            $this->selectedCategory = $this->asset->type->asset_category_id;
            $this->selectedType = $this->asset->asset_type_id;
            $this->selectedBrand = $this->asset->brand_id;
            $this->types = AssetType::where('asset_category_id', $this->selectedCategory)->orderBy('name')->get();
            $this->brands = $this->asset->type->brands()->orderBy('name')->get();
        }
    }

    // ฟังก์ชันสำหรับปิด Modal
    public function closeModal()
    {
        $this->showModal = false;
    }

    // Runs when the user manually changes the Category
    public function updatedSelectedCategory($categoryId)
    {
        $this->types = $categoryId ? AssetType::where('asset_category_id', $categoryId)->orderBy('name')->get() : collect();
        $this->reset(['selectedType', 'selectedBrand']);
        $this->brands = collect();
    }

    // Runs when the user manually changes the Type
    public function updatedSelectedType($typeId)
    {
        $type = AssetType::find($typeId);
        $this->brands = $type ? $type->brands()->orderBy('name')->get() : collect();
        $this->reset('selectedBrand');
    }

    public function render()
    {
        return view('livewire.asset-form');
    }
}
