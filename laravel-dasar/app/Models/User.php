<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Cast atribut ke tipe yang tepat.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Satu User memiliki satu Profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Satu User bisa menulis banyak Post.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Satu User bisa membuat banyak Komentar.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // =========================================================
    // SCOPE QUERIES
    // =========================================================

    /**
     * Scope: hanya user yang sudah memverifikasi email.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // =========================================================
    // HELPER METHODS
    // =========================================================

    /**
     * Apakah user ini sudah memverifikasi email?
     */
    public function isVerified(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Dapatkan jumlah total post yang sudah dipublikasikan oleh user ini.
     */
    public function publishedPostsCount(): int
    {
        return $this->posts()->published()->count();
    }
}
