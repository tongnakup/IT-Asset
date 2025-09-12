<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\Brand;
use App\Models\AssetStatus;
use App\Models\Location;
use App\Models\ItAsset;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CreateAssetForm extends Component
{
    use WithFileUploads;

    // Assignment Details
    public $employee_search = '';
    public $employee_id = null;
    public $first_name = '';
    public $last_name = '';
    public $position = '';
    public $searchResults = [];

    // Asset Details
    public $asset_number;
    public $asset_category_id = null;
    public $asset_type_id = null; // <-- จุดที่แก้ไขจาก 'asset_type_id' เป็น $asset_type_id
    public $brand_id = null;
    public $status_id = null;
    public $location_id = null;
    public $start_date;
    public $end_date;
    public $image;

    // Dropdown Data
    public $categories = [];
    public $types = [];
    public $brands = [];
    public $statuses = [];
    public $locations = [];

    public function mount()
    {
        $this->asset_number = $this->generateNextAssetNumber();
        $this->categories = AssetCategory::orderBy('name')->get();
        $this->statuses = AssetStatus::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
    }

    public function updatedEmployeeSearch()
    {
        if (strlen($this->employee_search) >= 1) {
            $this->searchResults = Employee::where('employee_id', 'like', $this->employee_search . '%')
                ->orWhere('first_name', 'like', '%' . $this->employee_search . '%')
                ->orWhere('last_name', 'like', '%' . $this->employee_search . '%')
                ->limit(5)->get();
        } else {
            $this->resetEmployeeFields();
        }
    }

    public function selectEmployee($employeeId)
    {
        $employee = Employee::find($employeeId);
        if ($employee) {
            $this->employee_id = $employee->id;
            $this->employee_search = $employee->employee_id;
            $this->first_name = $employee->first_name;
            $this->last_name = $employee->last_name;
            $this->position = $employee->position;
            $this->searchResults = [];
        }
    }

    private function resetEmployeeFields()
    {
        $this->employee_id = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->position = '';
        $this->searchResults = [];
    }

    public function updatedAssetCategoryId($categoryId)
    {
        if (!is_null($categoryId) && $categoryId !== '') {
            $this->types = AssetType::where('asset_category_id', $categoryId)->orderBy('name')->get();
        } else {
            $this->types = collect();
        }
        $this->reset(['asset_type_id', 'brand_id']);
        $this->brands = collect();
    }

    public function updatedAssetTypeId($typeId)
    {
        if (!is_null($typeId) && $typeId !== '') {
            $type = AssetType::find($typeId);
            if ($type) {
                $this->brands = $type->brands()->orderBy('name')->get();
            }
        } else {
            $this->brands = collect();
        }
        $this->reset('brand_id');
    }

    public function save()
    {
        $validatedData = $this->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'asset_number' => ['required', 'string', 'max:255', Rule::unique('assets', 'asset_number')],
            'asset_category_id' => 'required|exists:asset_categories,id',
            'asset_type_id' => 'required|exists:asset_types,id',
            'brand_id' => 'required|exists:brands,id',
            'status_id' => 'required|exists:asset_statuses,id',
            'location_id' => 'required|exists:locations,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($this->image) {
            $validatedData['image_path'] = $this->image->store('assets', 'public');
        }

        ItAsset::create($validatedData);

        return redirect()->route('it_assets.index')->with('success', 'เพิ่มข้อมูล Asset เรียบร้อยแล้ว');
    }

    private function generateNextAssetNumber(): string
    {
        $prefix = 'A';
        $today = Carbon::now()->format('dmy');
        $lastAsset = ItAsset::orderBy('id', 'desc')->first();
        $newRunningNumber = 1;

        if ($lastAsset) {

            if (strlen($lastAsset->asset_number) >= 9) {
                $lastRunningNumber = (int)substr($lastAsset->asset_number, -3);
                $newRunningNumber = $lastRunningNumber + 1;
            }
        }

        return $prefix . $today . str_pad($newRunningNumber, 3, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.create-asset-form');
    }
}
