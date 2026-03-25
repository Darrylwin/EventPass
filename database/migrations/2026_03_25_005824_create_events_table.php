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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->dateTime('starts_at');
            $table->string('location');
            $table->unsignedInteger('capacity');
            $table->decimal('price', 8, 2)->default(0);
            $table->string('image_path')->nullable();
            $table->enum('status', ['brouillon', 'publié', 'annulé', 'terminé'])->default('brouillon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
