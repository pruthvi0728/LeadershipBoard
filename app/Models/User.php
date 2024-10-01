<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rank',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function activityPoints()
    {
        return $this->hasMany(UserActivityPoint::class);
    }

    public function totalPoints(): int
    {
        return $this->activityPoints()->sum('points');
    }

    public function pointsToday(): int
    {
        return $this->activityPoints()
            ->whereDate('created_at', now()->toDateString())
            ->sum('points');
    }

    public function pointsThisMonth(): int
    {
        return $this->activityPoints()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('points');
    }

    public function pointsThisYear(): int
    {
        return $this->activityPoints()
            ->whereYear('created_at', now()->year)
            ->sum('points');
    }
}
