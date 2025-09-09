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

            $stats = [
                'total' => ItAsset::count(),
                'pending_requests' => RepairRequest::where('status', 'Pending')->count(),
            ];
            $categories = AssetCategory::with('assetTypes.itAssets')->get();
            $categoryStats = $categories->map(function ($category) {
                $typesWithCounts = $category->assetTypes->map(function ($type) {
                    return [
                        'name' => $type->name,
                        'count' => $type->itAssets->count()
                    ];
                });
                return [
                    'name' => $category->name,
                    'types' => $typesWithCounts
                ];
            });
            $cardColors = ['bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500'];
            $assetsByType = ItAsset::join('asset_types', 'assets.asset_type_id', '=', 'asset_types.id')->select('asset_types.name', DB::raw('count(*) as total'))->groupBy('asset_types.name')->pluck('total', 'name');
            $pieChartData['labels'] = $assetsByType->keys();
            $pieChartData['data'] = $assetsByType->values();
            $assetsByBrand = ItAsset::join('brands', 'assets.brand_id', '=', 'brands.id')->select('brands.name', DB::raw('count(*) as total'))->groupBy('brands.name')->orderBy('total', 'desc')->pluck('total', 'name');
            $barChartData['labels'] = $assetsByBrand->keys();
            $barChartData['data'] = $assetsByBrand->values();
            $assetsByStatus = ItAsset::join('asset_statuses', 'assets.status_id', '=', 'asset_statuses.id')->select('asset_statuses.name', DB::raw('count(*) as total'))->groupBy('asset_statuses.name')->pluck('total', 'name');
            $statusChartData['labels'] = $assetsByStatus->keys();
            $statusChartData['data'] = $assetsByStatus->values();
            return view('dashboard', compact('stats', 'categoryStats', 'pieChartData', 'barChartData', 'statusChartData', 'latestAnnouncement', 'cardColors'));
        } else {
            // --- ▼▼▼ [แก้ไข] ส่วนของ User ทั่วไปให้ดึงข้อมูลครบถ้วน ▼▼▼ ---

            $employeeId = $user->employee_id;

            // ใช้ eager loading (`with`) เพื่อดึงข้อมูลที่เกี่ยวข้องมาพร้อมกัน
            $userAssets = ItAsset::where('employee_id', $employeeId)
                ->with(['assetType', 'brand', 'status']) // <-- จุดสำคัญ
                ->get();

            $userRequestsQuery = RepairRequest::where('user_id', $user->id);

            $userStats = [
                'totalAssets' => $userAssets->count(),
                'pendingRequests' => (clone $userRequestsQuery)->where('status', 'Pending')->count(),
                'resolvedRequests' => (clone $userRequestsQuery)->where('status', 'Resolved')->count(),
            ];

            // ดึงข้อมูล request พร้อมกับ assetType เพื่อแก้ปัญหาหน้า Dashboard
            $recentRequests = $userRequestsQuery->with('assetType')->latest()->take(5)->get();

            return view('dashboard', [
                'userStats' => $userStats,
                'userAssets' => $userAssets,
                'userRequests' => $recentRequests,
                'latestAnnouncement' => $latestAnnouncement,
            ]);
        }
    }
}
