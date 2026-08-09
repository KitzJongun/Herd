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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('client_name');
            $table->string('client_email');
            $table->string('title');
            $table->text('description');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('attachment')->nullable();
            
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open');
            $table->enum('priority', ['Rendah', 'Sedang', 'Tinggi'])->nullable();
            
            // AI Suggestion columns
            $table->text('ai_summary')->nullable();
            $table->foreignId('ai_suggested_category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->enum('ai_suggested_priority', ['Rendah', 'Sedang', 'Tinggi'])->nullable();
            
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
