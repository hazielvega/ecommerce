<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('total');
            
            // Recomendación: Actualizar el campo total para que sea calculado
            $table->decimal('total', 10, 2)->change(); // Asegurar mismo tipo de dato
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_cost');
            // No es necesario revertir el cambio de tipo de total
        });
    }
};