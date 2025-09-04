<?php

namespace App\Http\Controllers;

use App\Models\ItAsset;
use App\Models\RepairRequest;
use App\Models\Announcement;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $latestAnnouncement = Announcement::where('is_active', true)->latest()->first();
        $user = Auth::user();

        if ($user->role == 'admin') {
            // --- เริ่มส่วนแก้ไข ---
            
            // 1. ดึงข้อมูลสรุปทั่วไป
            $stats = [
                'total' => ItAsset::count(),
                'pending_requests' => RepairRequest::where('status', 'Pending')->count(), 
            ];

            // 2. ดึงข้อมูลหมวดหมู่ทั้งหมดพร้อมกับประเภทและจำนวนสินทรัพย์ที่อยู่ข้างใน (Eager Loading)
            $categories = AssetCategory::with('assetTypes.itAssets')->get();

            // 3. จัดกลุ่มข้อมูลใหม่เพื่อส่งไปให้ View
            $categoryStats = $categories->map(function ($category) {
                $typesWithCounts = $category->assetTypes->map(function ($type) {
                    return [
                        'name' => $type->name,
                        'count' => $type->itAssets->count() // นับจำนวนจาก relationship ที่โหลดมา
                    ];
                });

                return [
                    'name' => $category->name,
                    'types' => $typesWithCounts
                ];
            });

            $cardColors = [
                'bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 
                'bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500'
            ];

            // 4. ดึงข้อมูลสำหรับกราฟ โดยใช้ join กับตารางที่เกี่ยวข้อง
            $assetsByType = ItAsset::join('asset_types', 'assets.asset_type_id', '=', 'asset_types.id')
                ->select('asset_types.name', DB::raw('count(*) as total'))
                ->groupBy('asset_types.name')
                ->pluck('total', 'name');
            
            $pieChartData['labels'] = $assetsByType->keys();
            $pieChartData['data'] = $assetsByType->values();
            
            $assetsByBrand = ItAsset::join('brands', 'assets.brand_id', '=', 'brands.id')
                ->select('brands.name', DB::raw('count(*) as total'))
                ->groupBy('brands.name')
                ->orderBy('total', 'desc')
                ->pluck('total', 'name');

            $barChartData['labels'] = $assetsByBrand->keys();
            $barChartData['data'] = $assetsByBrand->values();

            $assetsByStatus = ItAsset::join('asset_statuses', 'assets.status_id', '=', 'asset_statuses.id')
                ->select('asset_statuses.name', DB::raw('count(*) as total'))
                ->groupBy('asset_statuses.name')
                ->pluck('total', 'name');

            $statusChartData['labels'] = $assetsByStatus->keys();
            $statusChartData['data'] = $assetsByStatus->values();

            return view('dashboard', compact('stats', 'categoryStats', 'pieChartData', 'barChartData', 'statusChartData', 'latestAnnouncement', 'cardColors'));
            // --- จบส่วนแก้ไข ---
        } 
        else {
            // ส่วนของ User ทั่วไป (ควรตรวจสอบว่า employee relationship มีอยู่จริง)
            $employeeId = optional(Auth::user()->employee)->id;
            $userAssets = ItAsset::where('employee_id', $employeeId)->get();
            $userRequestsQuery = RepairRequest::where('user_id', $user->id);

            $userStats = [
                'totalAssets' => $userAssets->count(),
                'pendingRequests' => (clone $userRequestsQuery)->where('status', 'Pending')->count(),
                'resolvedRequests' => (clone $userRequestsQuery)->where('status', 'Resolved')->count(),
            ];

            $recentRequests = $userRequestsQuery->latest()->take(5)->get();

            return view('dashboard', [
                'userStats' => $userStats,
                'userAssets' => $userAssets,
                'userRequests' => $recentRequests,
                'latestAnnouncement' => $latestAnnouncement,
            ]);
        }
    }
}
