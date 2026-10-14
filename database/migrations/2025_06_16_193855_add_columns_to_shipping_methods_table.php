<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('shipping_methods')) {
            // Table does not exist yet: create it with the full column set.
            Schema::create('shipping_methods', function (Blueprint $t) {
                $t->id();
                $t->string('name');
                $t->enum('type', ['post','home','branch']);
                $t->unsignedBigInteger('government')->nullable();
                $t->string('address')->nullable();
                $t->json('phones')->nullable();
                $t->decimal('fee', 8, 2)->default(0);
                $t->timestamps();
            });

            return;
        }

        // Table already exists (created by an earlier migration): add any missing columns instead.
        Schema::table('shipping_methods', function (Blueprint $t) {
            if (!Schema::hasColumn('shipping_methods', 'type')) {
                $t->enum('type', ['post', 'home', 'branch'])->default('branch')->after('name');
            }
            if (!Schema::hasColumn('shipping_methods', 'fee')) {
                $t->decimal('fee', 8, 2)->default(0);
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
        Schema::dropIfExists('shipping_methods');
    }
}
