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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->foreignId('field_type_id')->constrained()->onDelete('restrict');
            $table->string('label');
            $table->string('name');                     // HTML name / slug
            $table->integer('order')->default(0);
            $table->json('settings')->nullable();       // placeholder, options, etc.
            $table->json('validation')->nullable();     // required, min, max, pattern, etc.
            $table->json('styles')->nullable();         // width, classes, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
