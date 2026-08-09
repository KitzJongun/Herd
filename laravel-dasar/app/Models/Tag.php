<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug'])]
class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    // =========================================================
    // BOOT — auto-generate slug dari name
    // =========================================================

    protected static function booted(): void
    {
        // Sebelum menyimpan, generate slug otomatis jika belum ada
        static::creating(function (Tag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Satu Tag bisa dimiliki banyak Post (many-to-many).
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    // =========================================================
    // SCOPE QUERIES
    // =========================================================

    /**
     * Scope: Tag yang paling banyak dipakai.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePopular($query, int $limit = 10)
    {
        return $query->withCount('posts')
                     ->orderByDesc('posts_count')
                     ->limit($limit);
    }
}
