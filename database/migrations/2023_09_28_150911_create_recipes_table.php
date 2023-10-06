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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string("titre");
            $table->string("description");
            $table->foreignId("ingredient_id")->constrained("ingredients");
            $table->timestamps();
        });

        schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   schema::table("recipes", function(Blueprint $table){
        $table->dropConstrainedForeignId("ingredient_id"); 
    });
        Schema::dropIfExists('recipes');
    }
};
