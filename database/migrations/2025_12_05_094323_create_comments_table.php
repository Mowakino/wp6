<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');

            // Self-referencing (parent comment)
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->text('content');

            // Only parent comments can have rating
            $table->tinyInteger('rating')->nullable();

            // moderation
            $table->boolean('is_approved')->default(false);

            $table->timestamps();
        });

        // Add foreign key for parent_id AFTER table creation
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')->on('comments')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
