<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers_orders', 'user_name')) {
                $table->string('user_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('vouchers_orders', 'user_email')) {
                $table->string('user_email')->nullable()->after('user_name');
            }
            if (!Schema::hasColumn('vouchers_orders', 'user_phone')) {
                $table->string('user_phone')->nullable()->after('user_email');
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
        Schema::table('vouchers_orders', function (Blueprint $table) {
            foreach (['user_name', 'user_email', 'user_phone'] as $column) {
                if (Schema::hasColumn('vouchers_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
