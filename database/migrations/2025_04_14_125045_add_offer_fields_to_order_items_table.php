<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('offer_id')->nullable()->after('variant_id')
                ->constrained('offers')->onDelete('set null');
                
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('price'); // Cambiado a porcentaje
            $table->decimal('original_price', 10, 2)->after('price');
            
            // Actualizar el tipo de datos para consistencia
            $table->decimal('price', 10, 2)->change();
            $table->decimal('subtotal', 10, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->dropColumn(['offer_id', 'discount_percentage', 'original_price']);
            // No revertimos el cambio de tipo de datos por seguridad
        });
    }
};