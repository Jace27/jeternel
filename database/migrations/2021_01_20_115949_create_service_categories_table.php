<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('1c_id')->unique()->nullable();
            $table->string('name');
            $table->integer('parent_category_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_categories', function (Blueprint $table){
            $table->dropForeign('service_categories_parent_category_id_foreign');
        });
        Schema::table('service_service_categories', function (Blueprint $table){
            $table->dropForeign('service_service_categories_category_id_foreign');
        });
        Schema::dropIfExists('service_categories');
    }
}
