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
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Si el destinatario es un usuario registrado
            $table->string('session_id')->nullable(); // Si el destinatario es un usuario no autenticado
            $table->string('name');
            $table->string('last_name');
            $table->string('document_number');
            $table->string('email');
            $table->string('phone');
            $table->boolean('default')->default(false);     //Este campo es para saber si el destinatario es el predeterminado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivers');
    }
};
