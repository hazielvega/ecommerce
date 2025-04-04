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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // Campo para guardar el identificador de sesión
            $table->string('calle');
            $table->string('numero');
            $table->string('ciudad');
            $table->string('provincia');
            $table->string('codigo_postal');
            $table->string('description')->nullable();
            $table->integer('type');
            $table->boolean('is_shipping')->default(false); // Indica si es dirección de envío
            $table->boolean('is_billing')->default(false);  // Indica si es dirección de facturación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
