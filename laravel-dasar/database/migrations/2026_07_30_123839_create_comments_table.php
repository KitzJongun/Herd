<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();         // komentar untuk post mana
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();         // siapa yang berkomentar
            $table->foreignId('parent_id')->nullable()->constrained('comments')     // untuk fitur reply
                  ->nullOnDelete();
            $table->text('body');                                                    // isi komentar
            $table->boolean('is_approved')->default(false);                         // moderasi komentar
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
