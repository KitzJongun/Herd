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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique(); // one-to-one
            $table->string('avatar')->nullable();         // path ke foto profil
            $table->text('bio')->nullable();              // biografi singkat
            $table->string('website')->nullable();        // URL website pribadi
            $table->json('social_links')->nullable();     // {'twitter': '...', 'instagram': '...'}
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
