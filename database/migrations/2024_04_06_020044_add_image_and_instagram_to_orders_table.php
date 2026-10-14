<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageAndInstagramToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('orders', 'instapay')) {
                $table->string('instapay')->nullable();
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
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('orders', 'instapay')) {
                $table->dropColumn('instapay');
            }
        });
    }
}
