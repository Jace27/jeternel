<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        \App\Models\roles::insert([
            [ 'name' => 'Администратор' ],
            [ 'name' => 'Оператор' ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropForeign('users_role_id_foreign');
        });
        /*Schema::table('right_roles', function (Blueprint $table){
            $table->dropForeign('right_roles_role_id_foreign');
        });*/
        Schema::dropIfExists('roles');
    }
}
