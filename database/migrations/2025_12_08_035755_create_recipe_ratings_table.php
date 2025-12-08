<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipeRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('recipe_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->timestamps();

            $table->unique(['recipe_id', 'user_id']); // ⭐ Prevent multiple ratings
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipe_ratings');
    }
}
