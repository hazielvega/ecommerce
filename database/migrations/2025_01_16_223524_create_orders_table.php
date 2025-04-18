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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); 
            $table->foreignId('receiver_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->onDelete('cascade');
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->onDelete('cascade');
            $table->string('billing_document')->nullable(); 
            $table->string('pdf_path')->nullable();
            $table->integer('payment_method')->default(1);
            $table->string('payment_id');
            $table->string('card_number');
            $table->float('total');
            $table->integer('status')->default(1);          # Por ejemplo: 1: PENDIENTE, 2: EN PROCESO, 3: COMPLETADO
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
