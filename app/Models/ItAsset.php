<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assets'; 
    
    protected $fillable = [
        'asset_number',
        'serial_number',
        'asset_category_id',
        'asset_type_id',
        'brand_id',
        'model',
        'start_date', 
        'end_date',   
        'location_id',
        'employee_id',
        'status_id',
        'notes',
        'image_path',
    ];

    protected $casts = [
        'start_date' => 'date', // <-- [แก้ไข] เปลี่ยนกลับเป็น start_date
        'end_date' => 'date',   // <-- [แก้ไข] เปลี่ยนกลับเป็น end_date
    ];

    public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function status()
    {
        return $this->belongsTo(AssetStatus::class, 'status_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
