<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('1c_id')->unique()->nullable();
            $table->string('name');
            $table->string('manufacturer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_drugs', function (Blueprint $table){
            $table->dropForeign('service_drugs_drug_id_foreign');
        });
        Schema::dropIfExists('drugs');
    }
}
