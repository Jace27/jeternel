<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description');
            $table->longText('preparation');
            $table->longText('rehabilitation');
            $table->longText('contraindications');
            $table->text('duration');
            $table->text('course');
            $table->integer('cost');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_other_names', function (Blueprint $table){
            $table->dropForeign('service_other_names_service_id_foreign');
        });
        Schema::table('service_performers', function (Blueprint $table){
            $table->dropForeign('service_performers_service_id_foreign');
        });
        Schema::table('service_categories', function (Blueprint $table){
            $table->dropForeign('service_categories_service_id_foreign');
        });
        Schema::table('service_drugs', function (Blueprint $table){
            $table->dropForeign('service_drugs_service_id_foreign');
        });
        Schema::dropIfExists('services');
    }
}
