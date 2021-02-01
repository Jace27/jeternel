<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCategoriesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_categories_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        \App\Models\service_categories_types::insert([
            [ 'name' => 'По классу' ],
            [ 'name' => 'По проблемам клиента' ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_categories', function (Blueprint $table){
            $table->dropForeign('service_categories_type_id_foreign');
        });
        Schema::dropIfExists('service_categories_types');
    }
}
