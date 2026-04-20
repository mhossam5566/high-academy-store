<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvailableShippingMethodsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'available_shipping_methods')) {
                $table->json('available_shipping_methods')->nullable()->after('weight');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'available_shipping_methods')) {
                $table->dropColumn('available_shipping_methods');
            }
        });
    }
}
