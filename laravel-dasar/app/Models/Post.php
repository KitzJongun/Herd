<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable(['user_id', 'title', 'slug', 'body', 'excerpt', 'status', 'published_at', 'metadata', 'view_count'])]
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Cast atribut ke tipe yang tepat.
     * Menggunakan casts() method (cara modern Laravel, bukan $casts array).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status'       => PostStatus::class,   // Otomatis cast ke PHP Enum
            'published_at' => 'datetime',
            'metadata'     => 'array',              // JSON kolom di-cast ke PHP array
            'deleted_at'   => 'datetime',
            'view_count'   => 'integer',
        ];
    }

    // =========================================================
    // BOOT — logic otomatis saat model dibuat/diupdate
    // =========================================================

    protected static function booted(): void
    {
        // Auto-generate slug dari title sebelum disimpan
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        // Auto-set published_at ketika status berubah ke Published
        static::saving(function (Post $post) {
            if ($post->status === PostStatus::Published && is_null($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Post ini ditulis oleh satu User (penulis/author).
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Post ini memiliki banyak Komentar.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Post ini memiliki banyak komentar yang sudah disetujui.
     */
    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('is_approved', true);
    }

    /**
     * Post ini memiliki banyak Tag (many-to-many, via pivot table post_tag).
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    // =========================================================
    // SCOPE QUERIES
    // =========================================================

    /**
     * Scope: hanya post yang berstatus Published.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::Published)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope: hanya post yang berstatus Draft.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeDraft($query)
    {
        return $query->where('status', PostStatus::Draft);
    }

    /**
     * Scope: filter berdasarkan penulis tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeByAuthor($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: post paling populer (view terbanyak).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePopular($query, int $limit = 5)
    {
        return $query->orderByDesc('view_count')->limit($limit);
    }

    // =========================================================
    // ACCESSOR — properti virtual yang bisa diakses seperti atribut biasa
    // =========================================================

    /**
     * Buat ringkasan otomatis dari body jika excerpt kosong.
     */
    public function getExcerptAttribute(?string $value): string
    {
        return $value ?? Str::limit(strip_tags($this->body), 150);
    }

    /**
     * Estimasi waktu baca artikel (rata-rata 200 kata/menit).
     */
    public function getReadingTimeAttribute(): string
    {
        $wordCount  = str_word_count(strip_tags($this->body));
        $minutes    = (int) ceil($wordCount / 200);

        return $minutes <= 1 ? '1 menit baca' : "{$minutes} menit baca";
    }

    // =========================================================
    // HELPER METHODS
    // =========================================================

    /**
     * Tambah jumlah view post ini.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Apakah post ini sudah dipublikasikan?
     */
    public function isPublished(): bool
    {
        return $this->status === PostStatus::Published;
    }
}
