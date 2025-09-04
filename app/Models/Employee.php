<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'position',
        'department',
        'location', 
        'phone_number',
        'start_date',
    ];

    /**
     * Get the user that owns the employee record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
