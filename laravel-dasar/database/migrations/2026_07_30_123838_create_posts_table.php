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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // penulis (author)
            $table->string('title');                                         // judul artikel
            $table->string('slug')->unique();                                // URL-friendly
            $table->longText('body');                                        // isi artikel
            $table->text('excerpt')->nullable();                             // ringkasan artikel
            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('draft');                                        // status publikasi
            $table->timestamp('published_at')->nullable();                  // waktu dipublikasikan
            $table->json('metadata')->nullable();                            // data tambahan (tag SEO, dll)
            $table->unsignedBigInteger('view_count')->default(0);           // jumlah dilihat
            $table->softDeletes();                                           // soft delete (tidak hapus permanen)
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
