<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['name','slug'];
    public function places() :BelongsToMany {
        return $this->belongsToMany(Place::class,'place_category','category_id','place_id');
    }
}
