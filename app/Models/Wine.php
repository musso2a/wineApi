<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed user_id
 * @property mixed provenance
 * @property mixed trade
 * @property mixed color
 * @property mixed images
 * @property mixed description
 * @property mixed condition
 * @property mixed price
 * @property mixed year
 * @property mixed name
 * @property mixed id
 */
class Wine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'price',
        'condition',
        'description',
        'images',
        'color',
        'trade',
        'provenance',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'favorite_wine_id');
    }


}
