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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('name');
            $table->string('description')->nullable();
            $table->json('image_path')->nullable();
            $table->float('purchase_price'); // Precio de compra
            $table->float('sale_price'); // Precio de venta
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->boolean('is_enabled')->default(false); // Deshabilitado por defecto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
