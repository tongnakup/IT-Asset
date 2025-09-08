<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\ItAsset;
use Livewire\WithFileUploads;
use App\Models\AssetCategory;
use App\Models\Location;
use App\Models\AssetType;

class RepairRequestForm extends Component
{
    use WithFileUploads;

    // --- Properties ทั้งหมดเหมือนเดิม ---
    public $requester_name, $requester_email;
    public $asset_number = '', $searchResults, $selectedAsset, $assetFound = false, $lookupMessage, $lookupMessageType;
    public $asset_category_id, $asset_type_id, $location_id, $problem_description, $image;
    public $types, $categories, $locations;

    public function mount()
    {
        $this->requester_name = Auth::user()->name;
        $this->requester_email = Auth::user()->email;
        $this->searchResults = collect();
        $this->types = collect();

        // ▼▼▼ [แก้ไข] จะโหลดข้อมูลทั้งหมดโดยไม่มีการกรอง ▼▼▼
        $this->categories = AssetCategory::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
    }

    // --- ฟังก์ชัน updatedAssetNumber เหมือนเดิม ---
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

    // --- ฟังก์ชัน selectAsset เหมือนเดิม ---
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
            $this->lookupMessage = 'Asset found and details have been filled in.';
            $this->lookupMessageType = 'success';
        } else {
            $this->assetFound = false;
            $this->lookupMessage = 'Asset not found.';
            $this->lookupMessageType = 'error';
        }
        $this->searchResults = collect();
    }

    public function updatedAssetCategoryId($value)
    {
        if ($value) {
            // ▼▼▼ [แก้ไข] จะโหลด Type ทั้งหมดใน Category นั้นๆ โดยไม่มีการกรอง ▼▼▼
            $this->types = AssetType::where('asset_category_id', $value)
                ->orderBy('name')->get();
        } else {
            $this->types = collect();
        }
        $this->asset_type_id = null;
    }

    // --- ฟังก์ชัน save และ render เหมือนเดิม ---
    public function save()
    {
        $this->validate([
            'problem_description' => 'required|min:10',
            'asset_type_id' => 'required',
            'location_id' => 'required',
            'image' => 'nullable|image|max:1024',
        ]);
        session()->flash('success', 'Repair request submitted successfully!');
        return redirect()->to('/dashboard');
    }

    public function render()
    {
        return view('livewire.repair-request-form');
    }
}
