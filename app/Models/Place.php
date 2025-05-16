<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
    private $reviewsAverage;
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
    public function reviewsAverage():array
    {
        if ($this->reviewsAverage) {
            return $this->reviewsAverage;
        }
        $this->reviewsAverage = $this->reviews()->select([
            DB::raw('AVG(service_rating) as service_average'),
            DB::raw('AVG(price_rating) as price_average'),
            DB::raw('AVG(cleanliness_rating) as cleanliness_average'),
            DB::raw('AVG(quality_rating) as quality_average'),
            DB::raw('count(quality_rating) as count'),
        ])->first()->toArray();
        $this->reviewsAverage = array_map(function($item){
            return round($item,1);
        },$this->reviewsAverage);
        return $this->reviewsAverage;
    }
    public function scopeAvgRating(Builder $query):Builder
    {
        $query->leftJoin('reviews', 'places.id', '=', 'reviews.place_id')
            ->select('places.*', DB::raw('(AVG(`reviews`.`service_rating`) + AVG(`reviews`.`quality_rating`) + AVG(`reviews`.`cleanliness_rating`) + AVG(`reviews`.`price_rating`)) / 4 AS `avg`'))
            ->groupBy('places.id');
        return $query;
    }
    public function reports():HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function getImageLinkAttribute():string
    {
        if (str_starts_with($this->photo,'http')){
            return $this->photo;
        }
        return Storage::disk('public')->url($this->photo);
    }
}
