<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\Brand; //  import Brand Model 

class AssetForm extends Component
{
    public $categories = [];
    public $types = [];
    public $brands = []; //  property สำหรับ Brands 

    public $selectedCategory = null;
    public $selectedType = null;

    public function mount()
    {
        $this->categories = AssetCategory::orderBy('name')->get();
        $this->types = collect();
        $this->brands = collect(); //  เริ่มต้นให้ Brands เป็นค่าว่าง 
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
        $this->reset('selectedType');
        $this->brands = collect();
    }

    // เพิ่มฟังก์ชันนี้ใหม่ทั้งหมด 
    // ทำงานเมื่อ User เลือก Type
    public function updatedSelectedType($typeId)
    {
        if (!is_null($typeId) && $typeId !== '') {
            // ค้นหา Brands ทั้งหมดที่ผูกอยู่กับ Type นี้
            $type = AssetType::find($typeId);
            if ($type) {
                $this->brands = $type->brands()->orderBy('name')->get();
            }
        } else {
            $this->brands = collect();
        }
    }

    public function render()
    {
        return view('livewire.asset-form');
    }
}
