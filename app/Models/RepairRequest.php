<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $asset_type
 * @property string|null $asset_number
 * @property string $problem_description
 * @property string|null $image_path
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereAssetNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereAssetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereProblemDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RepairRequest whereUserId($value)
 * @mixin \Eloquent
 */
class RepairRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_type',
        'asset_number',
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
}
