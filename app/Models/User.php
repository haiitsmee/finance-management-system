<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'slug',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_users', 'user_id', 'business_id')
            ->withTimestamps()
            ->withPivot('user_id', 'business_id')
            ->select('businesses.id', 'businesses.slug', 'businesses.name');
    }

    public function activityLogs(): HasMany
    {
        return $this->HasMany(ActivityLog::class);
    }
}
