<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('third_name');
            $table->string('photo')->nullable();
            $table->text('specialization');
            $table->text('experience');
            $table->integer('branch_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_performers', function (Blueprint $table){
            $table->dropForeign('service_performers_performer_id_foreign');
        });
        Schema::dropIfExists('performers');
    }
}
