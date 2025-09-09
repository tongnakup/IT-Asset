<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    use HasFactory;

    /**
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
     * ▼▼▼ [แก้ไข] ทำให้ความสัมพันธ์นี้ถูกต้องสมบูรณ์ ▼▼▼
     * Get the asset type associated with the repair request.
     */
    public function assetType()
    {

        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }
}
