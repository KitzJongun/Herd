<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('title');
            $table->text('description');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->string('attachment')->nullable();
            
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open');
            $table->enum('priority', ['Rendah', 'Sedang', 'Tinggi'])->nullable();
            
            // AI Fields
            $table->string('ai_summary')->nullable();
            $table->foreignId('ai_suggested_category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->enum('ai_suggested_priority', ['Rendah', 'Sedang', 'Tinggi'])->nullable();
            $table->boolean('ai_processed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
