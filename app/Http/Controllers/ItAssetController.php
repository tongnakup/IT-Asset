<?php

namespace App\Http\Controllers;

use App\Models\ItAsset;
use App\Models\Employee;
use App\Models\RepairRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\AssetType;
use App\Models\AssetStatus;
use App\Models\AssetCategory;
use App\Models\Location;
use App\Models\Brand;
use Illuminate\Validation\Rule;

class ItAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ใช้ชื่อ relationship ที่ถูกต้อง: 'type' และ 'status'
        $query = ItAsset::with(['employee.user', 'type', 'brand', 'status', 'location']);

        // --- Search Logic ---
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($subQ) use ($search) {
                $subQ->where('asset_number', 'like', "%{$search}%")
                    ->orWhereHas('brand', fn($brandQuery) => $brandQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('employee.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->where('employee_id', 'like', "%{$search}%");
                    });
            });
        });

        // --- Filter Logic ---
        // ใช้ชื่อ relationship ที่ถูกต้อง: 'type'
        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->whereHas('type', fn($typeQuery) => $typeQuery->where('name', 'like', "%{$request->type}%"));
        });

        // ใช้ชื่อ relationship ที่ถูกต้อง: 'status'
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->whereHas('status', fn($statusQuery) => $statusQuery->where('name', 'like', "%{$request->status}%"));
        });

        $assets = $query->latest()->paginate(10)->appends($request->query());

        // Get data for filter dropdowns
        $types = Cache::remember('asset_types_pluck', 60, fn() => AssetType::orderBy('name')->pluck('name'));
        $statuses = Cache::remember('asset_statuses_pluck', 60, fn() => AssetStatus::orderBy('name')->pluck('name'));

        return view('it_assets.index', compact('assets', 'types', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('it_assets.create');
    }

    /**
     * Provides data for the Edit Asset modal.
     */
    public function getEditData(ItAsset $itAsset)
    {
        $itAsset->load('employee');
        $locations = Cache::remember('locations_collection', 60, fn() => Location::orderBy('name')->get());
        $brands = Cache::remember('brands_collection', 60, fn() => Brand::orderBy('name')->get());
        $statuses = Cache::remember('asset_statuses_collection', 60, fn() => AssetStatus::orderBy('name')->get());
        $categories = Cache::remember('asset_categories_collection', 60, fn() => AssetCategory::orderBy('name')->get());

        return response()->json([
            'asset' => $itAsset,
            'employees' => Employee::orderBy('first_name')->get(),
            'categories' => $categories,
            'types' => AssetType::where('asset_category_id', $itAsset->asset_category_id)->orderBy('name')->get(),
            'statuses' => $statuses,
            'locations' => $locations,
            'brands' => $brands,
        ]);
    }

    /**
     * Get brands associated with a specific asset type for cascading dropdowns.
     */
    public function getBrandsForType(AssetType $type)
    {
        return response()->json($type->brands()->orderBy('name')->get());
    }

    /**
     * Search for employees for the assignment modal.
     */
    public function searchEmployees(Request $request)
    {
        $query = $request->input('query', '');
        if (strlen($query) < 1) return response()->json([]);

        $employees = Employee::where('employee_id', 'like', '%' . $query . '%')
            ->orWhere('first_name', 'like', '%' . $query . '%')
            ->orWhere('last_name', 'like', '%' . $query . '%')
            ->limit(10)->get();

        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('it_assets.index')->with('error', 'Please use the new asset form.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItAsset $itAsset)
    {
        $validatedData = $request->validate($this->getValidationRules($itAsset));

        if ($request->hasFile('image')) {
            if ($itAsset->image_path) {
                Storage::disk('public')->delete($itAsset->image_path);
            }
            $path = $request->file('image')->store('assets', 'public');
            $validatedData['image_path'] = $path;
        }

        $itAsset->update($validatedData);

        return response()->json(['success' => true, 'message' => 'Asset updated successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ItAsset $itAsset)
    {
        // ใช้ชื่อ relationship ที่ถูกต้อง: 'type.assetCategory'
        $itAsset->load(['employee', 'type.assetCategory', 'brand', 'status', 'location']);
        $repairHistory = RepairRequest::where('asset_number', $itAsset->asset_number)->with('user')->latest()->get();
        $updateHistory = ActivityLog::where('description', 'like', '%' . $itAsset->asset_number . '%')->with('user')->latest()->get();
        return view('it_assets.show', compact('itAsset', 'repairHistory', 'updateHistory'));
    }

    public function label(ItAsset $itAsset)
    {
        return view('it_assets.label', compact('itAsset'));
    }

    public function edit(ItAsset $itAsset)
    {
        return redirect()->route('it_assets.index');
    }

    public function destroy(ItAsset $itAsset)
    {
        if ($itAsset->image_path) {
            Storage::disk('public')->delete($itAsset->image_path);
        }
        $itAsset->delete();
        return redirect()->route('it_assets.index')->with('success', 'ลบข้อมูล Asset เรียบร้อยแล้ว');
    }

    public function trash()
    {
        $assets = ItAsset::onlyTrashed()->latest()->paginate(10);
        return view('it_assets.trash', compact('assets'));
    }

    public function restore($id)
    {
        ItAsset::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('it_assets.trash')->with('success', 'กู้คืนข้อมูล Asset เรียบร้อยแล้ว');
    }

    public function forceDelete($id)
    {
        $asset = ItAsset::onlyTrashed()->findOrFail($id);
        if ($asset->image_path) {
            Storage::disk('public')->delete($asset->image_path);
        }
        $asset->forceDelete();
        return redirect()->route('it_assets.trash')->with('success', 'ลบข้อมูล Asset ถาวรเรียบร้อยแล้ว');
    }

    public function lookupByAssetNumber($assetNumber)
    {
        // ใช้ชื่อ relationship ที่ถูกต้อง: 'type.assetCategory'
        $asset = ItAsset::where('asset_number', $assetNumber)
            ->with(['type.assetCategory', 'location'])
            ->first();

        if ($asset) {
            return response()->json([
                'success' => true,
                'data' => [
                    // ใช้ชื่อ relationship ที่ถูกต้อง: 'type'
                    'asset_category_id' => $asset->type->asset_category_id,
                    'asset_type_id'     => $asset->asset_type_id,
                    'location_id'       => $asset->location_id,
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบ Asset Number นี้ในระบบ'
            ], 404);
        }
    }

    private function getValidationRules(?ItAsset $itAsset = null): array
    {
        $assetNumberRule = Rule::unique('assets', 'asset_number');
        if ($itAsset) {
            $assetNumberRule->ignore($itAsset->id);
        }

        return [
            'asset_number' => ['required', 'string', 'max:255', $assetNumberRule],
            'serial_number' => 'nullable|string|max:255',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'asset_type_id' => 'required|exists:asset_types,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
            'status_id' => 'required|exists:asset_statuses,id',
            'location_id' => 'required|exists:locations,id',
            'employee_id' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    private function generateNextAssetNumber(): string
    {
        $prefix = 'A';
        $today = Carbon::now()->format('dmy');
        $lastAssetToday = ItAsset::where('asset_number', 'like', $prefix . $today . '%')
            ->orderBy('asset_number', 'desc')->first();
        if (!$lastAssetToday) {
            return $prefix . $today . '001';
        }
        $lastRunningNumber = (int)substr($lastAssetToday->asset_number, -3);
        $newRunningNumber = $lastRunningNumber + 1;
        return $prefix . $today . str_pad($newRunningNumber, 3, '0', STR_PAD_LEFT);
    }
}
