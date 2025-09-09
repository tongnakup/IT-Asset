<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\ItAsset;
use Livewire\WithFileUploads;
use App\Models\AssetCategory;
use App\Models\Location;
use App\Models\AssetType;
use App\Models\RepairRequest;

class RepairRequestForm extends Component
{
    use WithFileUploads;

    // --- Properties ---
    public $requester_name, $requester_email;
    public $asset_number = '', $searchResults, $selectedAsset, $assetFound = false, $lookupMessage, $lookupMessageType;
    public $asset_category_id, $asset_type_id, $location_id, $problem_description, $image;
    public $types, $categories, $locations;

    public function mount()
    {
        $user = Auth::user();
        $this->requester_name = $user->name;
        $this->requester_email = $user->email;
        $this->searchResults = collect();
        $this->types = collect();
        $this->categories = AssetCategory::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
    }

    public function updatedAssetNumber($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResults = ItAsset::where('asset_number', 'like', '%' . $value . '%')->with(['assetType', 'brand'])->limit(5)->get();
        } else {
            $this->searchResults = collect();
        }
        $this->assetFound = false;
        $this->lookupMessage = null;
    }

    public function selectAsset($assetId)
    {
        $asset = ItAsset::find($assetId);
        if ($asset) {
            $this->selectedAsset = $asset;
            $this->asset_number = $asset->asset_number;
            $this->asset_category_id = $asset->assetType?->asset_category_id;
            $this->asset_type_id = $asset->asset_type_id;
            $this->location_id = $asset->location_id;
            if ($this->asset_category_id) {
                $this->types = AssetType::where('asset_category_id', $this->asset_category_id)->get();
            }
            $this->assetFound = true;
            $this->lookupMessage = 'พบข้อมูลทรัพย์สิน และได้ดึงข้อมูลมาใส่ในฟอร์มเรียบร้อยแล้ว';
            $this->lookupMessageType = 'success';
        } else {
            $this->assetFound = false;
            $this->lookupMessage = 'ไม่พบข้อมูลทรัพย์สิน';
            $this->lookupMessageType = 'error';
        }
        $this->searchResults = collect();
    }

    public function updatedAssetCategoryId($value)
    {
        if ($value) {
            $this->types = AssetType::where('asset_category_id', $value)->orderBy('name')->get();
        } else {
            $this->types = collect();
        }
        $this->asset_type_id = null;
    }

    // ในไฟล์ app/Livewire/RepairRequestForm.php
    public function save()
    {
        $validatedData = $this->validate([
            'problem_description' => 'required|min:10',
            'asset_type_id' => 'required|exists:asset_types,id',
            'location_id' => 'required|exists:locations,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $this->image ? $this->image->store('repair-requests', 'public') : null;

        // ▼▼▼ [แก้ไข] สร้าง Array ข้อมูลสำหรับบันทึกให้เหลือเฉพาะ ID ▼▼▼
        $dataToSave = [
            'user_id' => Auth::id(),
            'problem_description' => $this->problem_description,
            'image_path' => $imagePath,
            'status' => 'pending',
            'asset_type_id' => $this->asset_type_id, // ใช้ ID โดยตรง
            'location_id' => $this->location_id,   // ใช้ ID โดยตรง
        ];

        if ($this->assetFound && $this->selectedAsset) {
            // 'it_asset_id' ควรจะอยู่ใน $fillable ของ Model ด้วย
            $dataToSave['it_asset_id'] = $this->selectedAsset->id;
            $dataToSave['asset_number'] = $this->selectedAsset->asset_number;
        }

        // บันทึกข้อมูลลงฐานข้อมูล
        RepairRequest::create($dataToSave);

        session()->flash('success', 'ส่งใบแจ้งซ่อมเรียบร้อยแล้ว!');
        return redirect()->route('repair_requests.my');
    }

    public function render()
    {
        return view('livewire.repair-request-form');
    }
}
