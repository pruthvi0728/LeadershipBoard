<?php

namespace App\Models;

use App\Enums\PhysicalActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'activity',
    ];

    protected $casts = [
        'activity' => PhysicalActivities::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
