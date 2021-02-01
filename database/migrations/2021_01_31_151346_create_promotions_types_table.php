<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        \App\Models\promotions_types::insert([
            [ 'name' => 'Для клиентов категории A' ],
            [ 'name' => 'Для клиентов категории B' ],
            [ 'name' => 'Для клиентов категории C' ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotions', function (Blueprint $table){
            $table->dropForeign('promotions_type_id_foreign');
        });
        Schema::dropIfExists('promotions_types');
    }
}
