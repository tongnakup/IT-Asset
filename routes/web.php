<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItAssetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\RepairRequestController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EmployeeController;
use App\Models\AssetType;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route สำหรับหน้าแรก ให้ไปที่หน้า Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes ที่ต้องมีการล็อกอินก่อน
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (สำหรับทุกคน)
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (สำหรับทุกคน)
    Route::view('profile', 'profile')->name('profile');

    // Repair Requests (สำหรับ User ทั่วไป)
    Route::get('my-repair-requests', [RepairRequestController::class, 'userIndex'])->name('repair_requests.my');
    Route::get('repair-requests/create', [RepairRequestController::class, 'create'])->name('repair_requests.create');
    Route::post('repair-requests', [RepairRequestController::class, 'store'])->name('repair_requests.store');

    // Routes ที่ต้องเป็น Admin เท่านั้น
    Route::middleware([AdminMiddleware::class])->group(function () {

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        // ▼▼▼ เพิ่ม Route สำหรับ Category ที่นี่ ▼▼▼
        Route::post('settings/categories', [SettingController::class, 'storeCategory'])->name('settings.categories.store');
        Route::delete('settings/categories/{assetCategory}', [SettingController::class, 'destroyCategory'])->name('settings.categories.destroy');
        // ^^^ จบส่วนที่เพิ่ม ^^^
        Route::post('settings/types', [SettingController::class, 'storeType'])->name('settings.types.store');
        Route::delete('settings/types/{assetType}', [SettingController::class, 'destroyType'])->name('settings.types.destroy'); // แก้ไขให้ใช้ Route Model Binding
        Route::post('settings/statuses', [SettingController::class, 'storeStatus'])->name('settings.statuses.store');
        Route::delete('settings/statuses/{assetStatus}', [SettingController::class, 'destroyStatus'])->name('settings.statuses.destroy'); // แก้ไขให้ใช้ Route Model Binding

        //ส่วนของ Positions, Departments, และ Locations
        Route::post('settings/positions', [SettingController::class, 'storePosition'])->name('settings.positions.store');
        Route::delete('settings/positions/{position}', [SettingController::class, 'destroyPosition'])->name('settings.positions.destroy');

        Route::post('settings/departments', [SettingController::class, 'storeDepartment'])->name('settings.departments.store');
        Route::delete('settings/departments/{department}', [SettingController::class, 'destroyDepartment'])->name('settings.departments.destroy');

        Route::post('settings/locations', [SettingController::class, 'storeLocation'])->name('settings.locations.store');
        Route::delete('settings/locations/{location}', [SettingController::class, 'destroyLocation'])->name('settings.locations.destroy');

        Route::get('settings/assign-brands', [App\Http\Controllers\AssetTypeBrandController::class, 'index'])->name('settings.assign_brands.index');
Route::post('settings/assign-brands', [App\Http\Controllers\AssetTypeBrandController::class, 'store'])->name('settings.assign_brands.store');
        
        Route::post('settings/brands', [SettingController::class, 'storeBrand'])->name('settings.brands.store');
        Route::delete('settings/brands/{brand}', [SettingController::class, 'destroyBrand'])->name('settings.brands.destroy');

        // IT Assets
        Route::get('it_assets/trash', [ItAssetController::class, 'trash'])->name('it_assets.trash');
        Route::post('it_assets/{id}/restore', [ItAssetController::class, 'restore'])->name('it_assets.restore');
        Route::delete('it_assets/{id}/force-delete', [ItAssetController::class, 'forceDelete'])->name('it_assets.forceDelete');
        Route::get('it_assets/export', [ItAssetController::class, 'export'])->name('it_assets.export');
        Route::get('it_assets/{itAsset}/label', [ItAssetController::class, 'label'])->name('it_assets.label');
        Route::get('it_assets/{itAsset}/edit-data', [ItAssetController::class, 'getEditData'])->name('it_assets.get_edit_data');
        Route::get('/api/types/{type}/brands', [App\Http\Controllers\ItAssetController::class, 'getBrandsForType'])->name('api.types.brands');
        Route::get('/employees/search', [App\Http\Controllers\ItAssetController::class, 'searchEmployees'])->name('employees.search');
        Route::resource('it_assets', ItAssetController::class);


        // User Management
        Route::get('/users/create-data', [UserController::class, 'getCreateData'])->name('users.get_create_data');
        Route::get('/users/{user}/edit-data', [UserController::class, 'getEditData'])->name('users.get_edit_data');
        Route::resource('users', UserController::class);

        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // Activity Log
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');

        // Repair Request Management
        Route::get('repair-requests', [RepairRequestController::class, 'index'])->name('repair_requests.index');
        Route::get('repair-requests/{repairRequest}/edit', [RepairRequestController::class, 'edit'])->name('repair_requests.edit');
        Route::put('repair-requests/{repairRequest}', [RepairRequestController::class, 'update'])->name('repair_requests.update');
        Route::delete('repair-requests/{repairRequest}', [RepairRequestController::class, 'destroy'])->name('repair_requests.destroy');

        // Announcements Management
        Route::resource('announcements', AnnouncementController::class);
    });
});

// Auth Routes
require __DIR__ . '/auth.php';

// ▼▼▼ เพิ่ม Route ใหม่นี้เข้าไปที่ท้ายไฟล์ ▼▼▼
Route::get('/api/types/{category}', function ($categoryId) {
    // เพิ่มเงื่อนไขการตรวจสอบสิทธิ์ เพื่อความปลอดภัย
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
    return AssetType::where('asset_category_id', $categoryId)->orderBy('name')->get();
})->middleware('auth'); // กำหนดให้ต้อง Login ก่อนถึงจะเรียกใช้ได้
