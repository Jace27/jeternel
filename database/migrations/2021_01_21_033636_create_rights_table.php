<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('rights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        \App\Models\rights::insert([
            [ 'name' => 'Добавлять услуги' ],
            [ 'name' => 'Редактировать услуги' ],
            [ 'name' => 'Удалять услуги' ],
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('right_roles', function (Blueprint $table){
            $table->dropForeign('right_roles_right_id_foreign');
        });*/
        Schema::dropIfExists('rights');
    }
}
