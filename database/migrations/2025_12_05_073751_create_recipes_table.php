<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            // belongs to user
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic info
            $table->string('name');
            $table->string('image')->default('food/default.jpg');

            // Filters
            $table->string('cuisine')->nullable();
            $table->string('difficulty')->nullable();

            // ⭐ NEW: Default description
            $table->text('description')->default('A delicious and healthy recipe prepared with fresh ingredients.');

            // Metrics
            $table->integer('time_minutes')->nullable();
            $table->integer('calories')->nullable();

            // Nutrition
            $table->integer('protein')->nullable();
            $table->integer('fat')->nullable();
            $table->integer('carbs')->nullable();

            // Rating
            $table->decimal('rating', 3, 1)->default(0);

            $table->longText('ingredients')->nullable();
            $table->longText('instructions')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
