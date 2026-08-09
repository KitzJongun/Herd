<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'avatar', 'bio', 'website', 'social_links'])]
class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    /**
     * Cast atribut ke tipe yang tepat.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'social_links' => 'array', // JSON kolom di-cast ke PHP array otomatis
        ];
    }

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Profile ini milik satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================
    // ACCESSOR (fitur modern Laravel: get{Name}Attribute)
    // =========================================================

    /**
     * Dapatkan URL avatar, atau avatar default jika kosong.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&background=6366f1&color=fff';
    }

    /**
     * Apakah profile ini memiliki link media sosial?
     */
    public function hasSocialLinks(): bool
    {
        return ! empty($this->social_links);
    }
}
