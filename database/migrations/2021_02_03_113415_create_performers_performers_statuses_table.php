<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformersPerformersStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performers_performers_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('performer_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performers_performers_statuses');
    }
}
