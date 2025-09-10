<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItAsset extends Model
{
    use HasFactory, SoftDeletes;

    // ส่วนนี้ถูกต้องแล้วครับ
    protected $table = 'assets';

    protected $fillable = [
        'asset_number',
        'employee_id',
        'asset_category_id',
        'asset_type_id',
        'brand_id',
        'status_id',
        'location_id',
        'specifications',
        'image_path',
        'start_date',
        'end_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function assetCategory()
    {
        return $this->belongsTo(AssetCategory::class);
    }

    // [แก้ไข] เปลี่ยนชื่อฟังก์ชันจาก assetType เป็น type
    // เพื่อให้สอดคล้องกับการเรียกใช้ในส่วนอื่นๆ
    public function type()
    {
        // ต้องมั่นใจว่ามี Model 'AssetType' อยู่จริง
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // [แก้ไข] เปลี่ยนชื่อฟังก์ชันจาก assetStatus เป็น status
    // เพื่อให้แก้ปัญหา Error `undefined relationship [status]`
    public function status()
    {
        // ต้องมั่นใจว่ามี Model 'AssetStatus' อยู่จริง
        return $this->belongsTo(AssetStatus::class, 'status_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class, 'asset_number', 'asset_number');
    }
}
