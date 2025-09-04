<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Models\User;
use App\Notifications\NewRepairRequest;
use App\Notifications\RepairStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\AssetType;
use App\Models\Location;
use App\Models\AssetCategory; // <-- [สำคัญ] เพิ่ม Model นี้

class RepairRequestController extends Controller
{
    /**
     * Display a listing of the resource for Admin.
     */
    public function index()
    {
        $requests = RepairRequest::with('user')->latest()->paginate(10);
        return view('repair_requests.admin.index', compact('requests'));
    }

    /**
     * Display a listing of the resource for the current user.
     */
    public function userIndex()
    {
        $requests = RepairRequest::where('user_id', Auth::id())->latest()->paginate(10);
        return view('repair_requests.user.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ดึงข้อมูลทั้งหมดที่จำเป็นสำหรับ Dropdown (ทั้ง id และ name)
        $categories = AssetCategory::orderBy('name')->get(['id', 'name']);
        $locations = Location::orderBy('name')->get(['id', 'name']);

        // ส่งข้อมูลไปให้ View
        return view('repair_requests.user.create', compact('categories', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     * ▼▼▼ [แก้ไข] ส่วนนี้คือส่วนที่แก้ไขทั้งหมด ▼▼▼
     */
    public function store(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่ส่งมา (ใช้ _id)
        $validatedData = $request->validate([
            'asset_category_id' => 'required|exists:asset_categories,id',
            'asset_type_id' => 'required|exists:asset_types,id',
            'location_id' => 'required|exists:locations,id',
            'asset_number' => 'nullable|string|max:255',
            'problem_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'Pending';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('repairs', 'public');
            $validatedData['image_path'] = $path;
        }

        $newRepairRequest = RepairRequest::create($validatedData);

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewRepairRequest($newRepairRequest));
        }

        return redirect()->route('repair_requests.my')->with('success', 'ส่งใบแจ้งซ่อมเรียบร้อยแล้ว');
    }

    /**
     * Show the form for editing the specified resource for Admin.
     */
    public function edit(RepairRequest $repairRequest)
    {
        $statuses = ['Pending', 'In Progress', 'Resolved', 'Rejected'];
        return view('repair_requests.admin.edit', compact('repairRequest', 'statuses'));
    }

    /**
     * Update the specified resource in storage for Admin.
     */
    public function update(Request $request, RepairRequest $repairRequest)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,In Progress,Resolved,Rejected',
        ]);

        $repairRequest->update(['status' => $request->status]);

        // Send notification to the user
        $user = $repairRequest->user;
        if ($user) {
            $user->notify(new RepairStatusUpdated($repairRequest));
        }

        return redirect()->route('repair_requests.index')->with('success', 'อัปเดตสถานะใบแจ้งซ่อมเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage for Admin.
     */
    public function destroy(RepairRequest $repairRequest)
    {
        $repairRequest->delete();
        return redirect()->route('repair_requests.index')->with('success', 'ลบใบแจ้งซ่อมเรียบร้อยแล้ว');
    }
}
