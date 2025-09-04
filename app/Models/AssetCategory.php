<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssetType;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AssetType> $assetTypes
 * @property-read int|null $asset_types_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssetCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
    ];

    public function assetTypes()
    {
        return $this->hasMany(AssetType::class);
    }
}
