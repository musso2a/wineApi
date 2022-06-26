<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed email
 * @property mixed avatar
 * @property mixed is_major
 * @property mixed note
 * @property mixed subscription
 * @property mixed favorite_wine_id
 * @property mixed created_at
 * @property mixed updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_major',
        'note',
        'subscription',
        'favorite_wine_id',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wines(): HasMany
    {
        return $this->hasMany(Wine::class);
    }

    public function favoriteWine(): BelongsTo
    {
        return $this->belongsTo(Wine::class, 'favorite_wine_id', 'id');
    }

    public function favoriteWineId(): int
    {
        return $this->favorite_wine_id;
    }
}
