<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Models\User;
use App\Notifications\NewRepairRequest;
use App\Notifications\RepairStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
// ไม่จำเป็นต้องใช้ Model เหล่านี้ใน Controller อีกต่อไป
// use App\Models\AssetType;
// use App\Models\Location;
// use App\Models\AssetCategory;

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
        $requests = RepairRequest::with('asset.type')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('repair_requests.user.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ถูกต้องแล้ว! แค่แสดง View ก็พอ
        return view('repair_requests.user.create');
    }

    /**
     * Store a newly created resource in storage.
     * (ฟังก์ชันนี้ไม่ได้ถูกเรียกใช้อีกต่อไปเมื่อใช้ Livewire)
     */
    public function store(Request $request)
    {
        // เนื่องจากฟอร์มถูกจัดการโดย Livewire ฟังก์ชันนี้จึงไม่ถูกเรียก
        // เราสามารถปล่อยให้มันว่างไว้ หรือ redirect กลับไปเฉยๆ ก็ได้
        return redirect()->route('repair_requests.create');
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
