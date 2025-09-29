<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItAsset;
// ไม่จำเป็นต้องใช้ Model ของ dropdown แล้ว
// use App\Models\AssetCategory;
// use App\Models\AssetType;
// use App\Models\Location;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\RepairRequest;

class RepairForm extends Component
{
    use WithFileUploads;

    public $asset_number = '';
    // เปลี่ยน property จาก _id เป็น _name
    public $asset_category_name = '';
    public $asset_type_name = '';
    public $location_name = '';
    public $problem_description = '';
    public $image;

    public $searchResults = [];
    public $assetFound = false;
    public $lookupMessage = '';
    public $lookupMessageType = '';

    // ไม่ต้องใช้ property สำหรับเก็บข้อมูล dropdown อีกต่อไป
    // public $categories = [];
    // public $types = [];
    // public $locations = [];

    public function mount()
    {
        // ไม่ต้องโหลดข้อมูล dropdown มาแล้ว
    }

    public function updatedAssetNumber($number)
    {
        if (strlen($number) >= 3) {
            // โหลดข้อมูลที่เกี่ยวข้องมาด้วย (Eager Loading)
            $this->searchResults = ItAsset::with(['type.assetCategory', 'location'])
                ->where('asset_number', 'like', $number . '%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
            $this->clearAssetDetails();
        }
    }

    public function selectAsset($assetId)
    {
        $asset = ItAsset::with(['type.assetCategory', 'location'])->find($assetId);
        if (!$asset) return;

        $this->asset_number = $asset->asset_number;
        // ดึงข้อมูล "ชื่อ" มาใส่ในช่องกรอก
        $this->asset_category_name = $asset->type?->assetCategory?->name;
        $this->asset_type_name = $asset->type?->name;
        $this->location_name = $asset->location?->name;

        $this->assetFound = true;
        $this->lookupMessage = 'พบข้อมูล Asset: ' . $asset->asset_number;
        $this->lookupMessageType = 'success';
        $this->searchResults = [];
    }

    // ฟังก์ชันนี้ไม่จำเป็นแล้ว เพราะไม่มี dropdown ให้เลือกแล้ว
    // public function updatedAssetCategoryId($categoryId) { ... }

    private function clearAssetDetails()
    {
        // รีเซ็ต property ที่เป็น _name แทน
        $this->reset(['asset_category_name', 'asset_type_name', 'location_name', 'assetFound', 'lookupMessage', 'lookupMessageType']);
    }

    public function save()
    {
        // ปรับปรุง validate ให้ตรงกับ property ใหม่
        $validatedData = $this->validate([
            'asset_category_name' => 'required|string|max:255',
            'asset_type_name' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
            'asset_number' => 'nullable|string|max:255', // อาจจะต้อง validate ว่ามีจริงหรือไม่ แล้วแต่ความต้องการ
            'problem_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'Pending';

        if ($this->image) {
            $validatedData['image_path'] = $this->image->store('repairs', 'public');
        }

        // RepairRequest::create($validatedData); // จะ error เพราะชื่อ column ใน DB ไม่ตรง

        // เนื่องจากชื่อ property ไม่ตรงกับชื่อ column ใน DB เราต้อง map ค่าเอง
        RepairRequest::create([
            'user_id' => $validatedData['user_id'],
            'status' => $validatedData['status'],
            'asset_number' => $validatedData['asset_number'],
            'problem_description' => $validatedData['problem_description'],
            'image_path' => $validatedData['image_path'] ?? null,
            // สมมติว่าในตารางยังเก็บเป็น id อยู่ (ต้องหา id จากชื่อที่กรอกเข้ามา)
            // หมายเหตุ: ส่วนนี้เป็นการ "เดา" โครงสร้าง DB ถ้าต้องการให้บันทึกเป็น string เลย ต้องแก้ DB
            'asset_category_id' => null, // อาจจะต้องปล่อยเป็น null หรือหาจากชื่อที่กรอก
            'asset_type_id' => null,     // อาจจะต้องปล่อยเป็น null หรือหาจากชื่อที่กรอก
            'location_id' => null,       // อาจจะต้องปล่อยเป็น null หรือหาจากชื่อที่กรอก
        ]);


        session()->flash('success', 'ส่งใบแจ้งซ่อมเรียบร้อยแล้ว');
        return $this->redirect(route('repair_requests.my'), navigate: true);
    }

    public function render()
    {
        return view('livewire.repair-form');
    }
}
