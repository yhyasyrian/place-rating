<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    public $timestamps = false;
    protected $table = 'reports';
    protected $fillable = ['user_id', 'place_id', 'created_at'];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
