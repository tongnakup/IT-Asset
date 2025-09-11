<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItAsset;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\Location;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\RepairRequest;
use App\Models\User;
use App\Notifications\NewRepairRequest;
use Illuminate\Support\Facades\Notification;

class RepairRequestForm extends Component
{
    use WithFileUploads;

    // Form properties
    public $asset_number = '';
    public $asset_category_id = '';
    public $asset_type_id = '';
    public $location_id = '';
    public $problem_description = '';
    public $image;

    // Properties for search functionality
    public $searchResults = [];
    public $assetFound = false;
    public $lookupMessage = '';
    public $lookupMessageType = '';

    // Data for dropdowns
    public $categories = [];
    public $types = [];
    public $locations = [];

    public function mount()
    {
        $this->categories = AssetCategory::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
        $this->types = collect(); // เริ่มต้นด้วย Type ว่างๆ
    }

    public function updatedAssetNumber($number)
    {
        if (strlen($number) >= 3) {
            $this->searchResults = ItAsset::with(['type', 'brand'])
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
        $this->clearAssetDetails();

        $asset = ItAsset::with('type')->find($assetId);

        if ($asset) {
            $this->asset_number = $asset->asset_number;
            $this->asset_category_id = $asset->type?->asset_category_id;

            // โหลดรายการ Type ที่ถูกต้องมาใส่ Dropdown
            $this->loadTypesForCategory($this->asset_category_id);

            // [แก้ไข] ตั้งค่า Type เริ่มต้นเป็นค่าว่าง เพื่อให้ผู้ใช้เลือกเอง
            $this->asset_type_id = null;

            $this->location_id = $asset->location_id;

            $this->assetFound = true;
            $this->lookupMessage = 'พบข้อมูล Asset: ' . $asset->asset_number;
            $this->lookupMessageType = 'success';
        }

        $this->searchResults = [];
    }

    private function clearAssetDetails()
    {
        $this->reset(['asset_category_id', 'asset_type_id', 'location_id', 'assetFound', 'lookupMessage', 'lookupMessageType']);
        $this->types = collect();
    }

    // [นำกลับมา] ฟังก์ชันนี้จะทำงานเมื่อมีการเลือก Category
    public function updatedAssetCategoryId($categoryId)
    {
        $this->loadTypesForCategory($categoryId);

        // รีเซ็ตค่า Type ที่เลือกไว้ (เพราะตัวเลือกเปลี่ยนไปแล้ว)
        $this->reset('asset_type_id');
    }

    // [นำกลับมา] ฟังก์ชันสำหรับโหลด Type ตาม Category ที่เลือก
    private function loadTypesForCategory($categoryId)
    {
        if ($categoryId) {
            $this->types = AssetType::where('asset_category_id', $categoryId)->get();
        } else {
            $this->types = collect();
        }
    }

    public function save()
    {
        $validatedData = $this->validate([
            'asset_category_id' => 'required|exists:asset_categories,id',
            'asset_type_id' => 'required|exists:asset_types,id',
            'location_id' => 'required|exists:locations,id',
            'asset_number' => 'nullable|string|max:255|exists:assets,asset_number',
            'problem_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'Pending';

        if ($this->image) {
            $path = $this->image->store('repairs', 'public');
            $validatedData['image_path'] = $path;
        }

        $newRepairRequest = RepairRequest::create($validatedData);

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewRepairRequest($newRepairRequest));
        }

        return redirect()->route('repair_requests.my')->with('success', 'ส่งใบแจ้งซ่อมเรียบร้อยแล้ว');
    }

    public function render()
    {
        return view('livewire.repair-request-form');
    }
}
