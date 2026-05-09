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
         Schema::create('field_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // e.g. "Text Input"
            $table->string('type');                     // e.g. "text", "email", "select"
            $table->string('icon')->default('form');    // icon key for UI
            $table->json('default_settings')->nullable();
            $table->json('default_validation')->nullable();
            $table->json('default_styles')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_types');
    }
};
