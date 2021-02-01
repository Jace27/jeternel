<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_other_names', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services');
        });
        Schema::table('service_performers', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('performer_id')->references('id')->on('performers');
        });
        Schema::table('performers', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches');
        });
        Schema::table('service_service_categories', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('category_id')->references('id')->on('service_categories');
        });
        Schema::table('service_categories', function (Blueprint $table) {
            $table->foreign('parent_category_id')->references('id')->on('service_categories');
            $table->foreign('type_id')->references('id')->on('service_categories_types');
        });
        Schema::table('service_drugs', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('drug_id')->references('id')->on('drugs');
        });
        /*Schema::table('right_roles', function (Blueprint $table) {
            $table->foreign('right_id')->references('id')->on('rights');
            $table->foreign('role_id')->references('id')->on('roles');
        });*/
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
        });
        Schema::table('articles', function (Blueprint $table){
            $table->foreign('section_id')->references('id')->on('articles_sections');
        });
        Schema::table('articles_sections', function (Blueprint $table){
            $table->foreign('parent_section_id')->references('id')->on('articles_sections');
        });
        Schema::table('promotions', function (Blueprint $table){
            $table->foreign('type_id')->references('id')->on('promotions_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
