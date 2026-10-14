<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_logs', function (Blueprint $table) {
            // How the related status change was triggered: manual (admin action) or automatic (system/payment gateway)
            $table->string('confirmation_type')->nullable()->after('step');
        });
    }

    public function down(): void
    {
        Schema::table('order_logs', function (Blueprint $table) {
            $table->dropColumn('confirmation_type');
        });
    }
};
