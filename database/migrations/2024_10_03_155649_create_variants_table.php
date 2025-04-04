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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->float('purchase_price')->default(0); // Precio de compra
            $table->float('sale_price')->default(0);     // Precio de venta
            $table->integer('stock')->unsigned()->default(0);
            $table->integer('min_stock')->unsigned()->default(5); // Nivel mínimo de stock por defecto
            $table->boolean('is_enabled');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
