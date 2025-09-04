<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'asset_category_id',
    ];

    /**
     * Get the category that owns the type.
     */
    public function assetCategory()
    {
        return $this->belongsTo(AssetCategory::class);
    }

    /**
     * The brands that belong to the asset type.
     */
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'asset_type_brand');
    }

    /**
     * Get all of the IT assets for the asset type.
     * ▼▼▼ [เพิ่มฟังก์ชันนี้] ▼▼▼
     * This defines the relationship needed by the DashboardController.
     */
    public function itAssets()
    {
        // An AssetType can have many ItAssets.
        // This assumes your 'assets' table has an 'asset_type_id' column.
        return $this->hasMany(ItAsset::class, 'asset_type_id');
    }
}
