<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\ItAsset;
use Illuminate\Support\Facades\Auth; // เพิ่ม use statement นี้

class ItAssetObserver
{
    /**
     * Handle the ItAsset "created" event.
     */
    public function created(ItAsset $itAsset): void
    {
        if (Auth::check()) { // ตรวจสอบว่ามีผู้ใช้ล็อกอินอยู่หรือไม่
            ActivityLog::create([
                'user_id' => Auth::id(), // ใช้วิธีนี้จะปลอดภัยกว่า
                'action' => 'Created',
                'description' => 'User ' . Auth::user()->name . ' created a new asset: ' . $itAsset->asset_number,
            ]);
        }
    }

    /**
     * Handle the ItAsset "updated" event.
     */
    public function updated(ItAsset $itAsset): void
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'description' => 'User ' . Auth::user()->name . ' updated asset: ' . $itAsset->asset_number,
            ]);
        }
    }

    /**
     * Handle the ItAsset "deleted" event.
     */
    public function deleted(ItAsset $itAsset): void
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'description' => 'User ' . Auth::user()->name . ' deleted asset: ' . $itAsset->asset_number,
            ]);
        }
    }
}
