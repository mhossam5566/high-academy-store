<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFeeFromShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_methods', 'fee')) {
                $table->dropColumn('fee');
            }
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_methods', 'fee')) {
                $table->decimal('fee', 8, 2)->default(0);
            }
        });
    }
}
