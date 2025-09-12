<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'it_asset_id',
        'asset_number',
        'asset_type_id',
        'location_id',
        'problem_description',
        'image_path',
        'status',
    ];

    /**
     * Get the user that owns the repair request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the asset type associated with the repair request.
     */
    public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    /**
     * ▼▼▼ [เพิ่มฟังก์ชันนี้เข้าไป] ▼▼▼
     * Get the IT asset associated with the repair request.
     */
    public function asset()
    {
        // เชื่อม RepairRequest กับ ItAsset โดยใช้คอลัมน์ 'asset_number'
        return $this->belongsTo(ItAsset::class, 'asset_number', 'asset_number');
    }
}
