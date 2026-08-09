<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['post_id', 'user_id', 'parent_id', 'body', 'is_approved'])]
class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Cast atribut ke tipe yang tepat.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'deleted_at'  => 'datetime',
        ];
    }

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Komentar ini ada di satu Post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Komentar ini ditulis oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Komentar ini adalah balasan dari komentar induk (self-referencing).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Komentar ini memiliki banyak balasan (self-referencing).
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // =========================================================
    // SCOPE QUERIES
    // =========================================================

    /**
     * Scope: hanya komentar yang sudah disetujui.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope: hanya komentar level atas (bukan balasan).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // =========================================================
    // HELPER METHODS
    // =========================================================

    /**
     * Apakah komentar ini merupakan balasan?
     */
    public function isReply(): bool
    {
        return ! is_null($this->parent_id);
    }
}
