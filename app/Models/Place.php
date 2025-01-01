<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    /** @use HasFactory<\Database\Factories\PlaceFactory> */
    use HasFactory;

    protected $table = 'places';
    protected $fillable = [
        'name', 'slug', 'photo', 'description', 'address', 'latitude', 'longitude', 'view_count'
    ];
    protected $casts = [
        'view_count' => 'integer'
    ];
    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class,'place_category','place_id','category_id');
    }
    public function usersBookmark():BelongsToMany
    {
        return $this->belongsToMany(User::class,'bookmarks','place_id','user_id');
    }
    public function reviews():HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function reports():HasMany
    {
        return $this->hasMany(Report::class);
    }
}
