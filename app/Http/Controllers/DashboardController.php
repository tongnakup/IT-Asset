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
    /**
     * Display the dashboard for both admin and user roles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $latestAnnouncement = Announcement::where('is_active', true)->latest()->first();
        $user = Auth::user();

        // ================== ADMIN DASHBOARD LOGIC ==================
        if ($user->role == 'admin') {

            $stats = [
                'total' => ItAsset::count(),
                'pending_requests' => RepairRequest::where('status', 'Pending')->count(),
                'resolved_requests' => RepairRequest::where('status', 'Resolved')->count(),
                'rejected_requests' => RepairRequest::where('status', 'Rejected')->count(),
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

            return view('dashboard', compact(
                'stats',
                'categoryStats',
                'pieChartData',
                'barChartData',
                'statusChartData',
                'latestAnnouncement'
            ));

            // ================== USER DASHBOARD LOGIC ==================
        } else {

            // Get the employee ID from the authenticated user's employee relationship
            $employeeId = $user->employee?->id;

            // Eager load relationships to prevent N+1 query issues
            $userAssets = ItAsset::where('employee_id', $employeeId)
                ->with(['type', 'brand', 'status'])
                ->get();

            // Prepare the base query for the user's repair requests
            $userRequestsQuery = RepairRequest::where('user_id', $user->id);

            // Prepare statistics for the user dashboard cards
            $userStats = [
                'totalAssets' => $userAssets->count(),
                'pendingRequests' => (clone $userRequestsQuery)->where('status', 'Pending')->count(),
                'resolvedRequests' => (clone $userRequestsQuery)->where('status', 'Resolved')->count(),
            ];

            // Get the 5 most recent repair requests
            $recentRequests = (clone $userRequestsQuery)->with('asset.type')->latest()->take(5)->get();

            // Return the view with all necessary data
            return view('dashboard', [
                'userStats' => $userStats,
                'userAssets' => $userAssets,
                'userRequests' => $recentRequests,
                'latestAnnouncement' => $latestAnnouncement,
            ]);
        }
    }
}
