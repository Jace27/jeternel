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
            $table->string('1c_id')->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('second_name')->nullable();
            $table->string('photo')->nullable();
            $table->longText('presentation');
            $table->integer('type_id')->unsigned();
            $table->string('working_hours')->nullable();
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
        Schema::table('performers_branches', function (Blueprint $table){
            $table->dropForeign('performers_branches_performer_id_foreign');
        });
        Schema::table('performers_performers_statuses', function (Blueprint $table){
            $table->dropForeign('performers_performers_statuses_performer_id_foreign');
        });
        Schema::dropIfExists('performers');
    }
}
