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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade'); // Relación con compras
            $table->foreignId('variant_id')->constrained()->onDelete('cascade'); // Relación con productos
            $table->integer('quantity'); // Cantidad comprada
            $table->decimal('unit_price', 10, 2); // Precio unitario de compra
            $table->decimal('subtotal', 10, 2); // Subtotal (cantidad * precio unitario)
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
