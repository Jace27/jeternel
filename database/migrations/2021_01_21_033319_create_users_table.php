<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone')->unique();
            $table->string('password')->nullable();
            $table->integer('role_id')->unsigned()->default(2);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('second_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signin_logs', function (Blueprint $table){
            $table->dropForeign('signin_logs_user_id_foreign');
        });
        Schema::dropIfExists('users');
    }
}
