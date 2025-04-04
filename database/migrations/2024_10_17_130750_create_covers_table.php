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
        Schema::create('covers', function (Blueprint $table) {
            $table->id();

            $table->string('image_path');
            $table->string('title');

            $table->datetime('start_at');   //En este campo se guarda la fecha en que se va a mostrar la portada
            $table->datetime('end_at')->nullable();     //En este campo se guarda la fecha en que se va a ocultar la portada

            $table->boolean('is_active')->default(true); //En este campo se indica si la portada se muestra o no

            $table->integer('order')->default(0);    //En este campo se indica el orden de la portada

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covers');
    }
};
