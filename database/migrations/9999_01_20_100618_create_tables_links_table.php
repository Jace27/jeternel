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
        Schema::table('services', function (Blueprint $table) {
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('service_other_names', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('service_performers', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('performer_id')->references('id')->on('performers')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('performers_branches', function (Blueprint $table) {
            $table->foreign('performer_id')->references('id')->on('performers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('performers_performers_statuses', function (Blueprint $table) {
            $table->foreign('performer_id')->references('id')->on('performers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('status_id')->references('id')->on('performers_statuses')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('service_service_categories', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('service_categories')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('service_categories', function (Blueprint $table) {
            $table->foreign('parent_category_id')->references('id')->on('service_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('service_categories_types')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('service_drugs', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('service_promotions', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade')->onUpdate('cascade');
        });
        /*Schema::table('right_roles', function (Blueprint $table) {
            $table->foreign('right_id')->references('id')->on('rights')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });*/
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('performers', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('performers_types')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('articles', function (Blueprint $table){
            $table->foreign('section_id')->references('id')->on('articles_sections')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('articles_sections', function (Blueprint $table){
            $table->foreign('parent_section_id')->references('id')->on('articles_sections')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('signin_logs', function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
