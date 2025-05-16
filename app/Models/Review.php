<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $table = 'reviews';
    protected $fillable = [
        'review',
        'service_rating',
        'quality_rating',
        'cleanliness_rating',
        'price_rating',
        'place_id',
        'user_id',
        'created_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
    ];
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function usersLike(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'review_id', 'user_id');
    }
    public function getCountLikeAttribute(): int
    {
        return $this->usersLike()->count();
    }
    public function scopeLikedByCurrentUser(Builder $query)
    {
        if (!auth()->check())
            return $query->addSelect(DB::raw('0 as is_liked'));
        return $query->addSelect([
            DB::raw('(SELECT EXISTS(SELECT `user_id` FROM likes WHERE likes.review_id = reviews.id AND likes.user_id = ' . auth()->id() . ' limit 1)) as is_liked')
        ]);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
