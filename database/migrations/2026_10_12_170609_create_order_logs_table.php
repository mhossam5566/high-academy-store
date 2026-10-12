<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->string('process_id')->nullable()->index(); // e.g. fawry_xxx_xxx
            $table->enum('level', ['info', 'success', 'warning', 'error'])->default('info');
            $table->string('step')->nullable();   // e.g. "STEP 1", "STEP 4"
            $table->text('message');
            $table->json('context')->nullable();  // extra JSON data
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
