<?php

namespace App\Models;

use Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $table = 'reviews';
    protected $fillable = [
        'review', 'service_rating', 'quality_rating', 'cleanliness_rating', 'price_rating', 'place_id', 'user_id', 'created_at'
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function usersLike(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'review_id', 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
