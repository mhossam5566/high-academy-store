<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconImageToMainCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('main_categories', 'icon_image')) {
                $table->string('icon_image')->nullable();
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
        Schema::table('main_categories', function (Blueprint $table) {
            if (Schema::hasColumn('main_categories', 'icon_image')) {
                $table->dropColumn('icon_image');
            }
        });
    }
}
