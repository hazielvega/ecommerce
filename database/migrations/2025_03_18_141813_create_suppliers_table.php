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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del proveedor
            $table->string('contact_name')->nullable(); // Nombre del contacto principal
            $table->string('email')->unique(); // Correo electrónico del proveedor
            $table->string('phone')->nullable(); // Teléfono de contacto
            $table->string('address')->nullable(); // Dirección del proveedor
            $table->string('city')->nullable(); // Ciudad
            $table->string('province')->nullable(); // Estado/Provincia
            $table->string('country')->nullable(); // País
            $table->text('notes')->nullable(); // Notas adicionales
            $table->boolean('is_active')->default(true); // Estado del proveedor
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
