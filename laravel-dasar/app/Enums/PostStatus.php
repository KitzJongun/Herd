<?php

namespace App\Enums;

enum PostStatus: string
{
    case Draft     = 'draft';
    case Published = 'published';
    case Archived  = 'archived';

    /**
     * Label yang ramah untuk ditampilkan di UI.
     */
    public function label(): string
    {
        return match($this) {
            PostStatus::Draft     => 'Draft',
            PostStatus::Published => 'Dipublikasikan',
            PostStatus::Archived  => 'Diarsipkan',
        };
    }

    /**
     * Warna badge untuk UI (misalnya class CSS).
     */
    public function color(): string
    {
        return match($this) {
            PostStatus::Draft     => 'gray',
            PostStatus::Published => 'green',
            PostStatus::Archived  => 'yellow',
        };
    }

    /**
     * Ikon yang sesuai untuk tiap status.
     */
    public function icon(): string
    {
        return match($this) {
            PostStatus::Draft     => '📝',
            PostStatus::Published => '✅',
            PostStatus::Archived  => '📦',
        };
    }

    /**
     * Apakah post ini bisa dilihat publik?
     */
    public function isVisible(): bool
    {
        return $this === PostStatus::Published;
    }

    /**
     * Daftar semua nilai sebagai array (berguna untuk validasi).
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
