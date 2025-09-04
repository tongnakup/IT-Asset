<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssetStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
