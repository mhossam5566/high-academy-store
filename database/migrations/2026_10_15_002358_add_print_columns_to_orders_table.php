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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('printed_pdf')->default(false)->after('status');
            $table->boolean('printed_excel')->default(false)->after('printed_pdf');
            $table->boolean('printed_completed')->default(false)->after('printed_excel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['printed_pdf', 'printed_excel', 'printed_completed']);
        });
    }
};
