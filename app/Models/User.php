<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable  implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function reviewsLike():BelongsToMany
    {
        return $this->belongsToMany(Review::class, 'likes', 'review_id', 'user_id');
    }
    public function bookmarks():BelongsToMany
    {
        return $this->belongsToMany(Place::class, 'bookmarks', 'user_id', 'place_id');
    }
    public function reviews():HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function reports():HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->canAccessToFilamentPanel();
    }
    public function canAccessToFilamentPanel() : bool {
        return $this->hasRole(['administrator','manager']);
    }
    public function getFilamentAvatarUrl(): string|null
    {
        return $this->profile_photo_url;
    }
}
