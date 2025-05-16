<?php

namespace App\Models;

use Spatie\Feed\{Feedable, FeedItem};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

class Category extends Model implements Feedable, Sitemapable
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['name', 'slug'];
    public function places(): BelongsToMany
    {
        return $this->belongsToMany(Place::class, 'place_category', 'category_id', 'place_id');
    }
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->name,
            'summary' => '',
            'updated' => $this->updated_at,
            'link' => route('category.show', $this->slug),
            'authorName' => config('app.name'),
        ]);
    }
    public static function getAllFeedItems()
    {
        return static::all();
    }
    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('category.show', $this->slug))
            ->setLastModificationDate($this->updated_at)
            ->setPriority(0.8);
    }
}
