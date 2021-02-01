<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_section_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles_sections', function (Blueprint $table){
            $table->dropForeign('articles_sections_parent_section_id_foreign');
        });
        Schema::table('articles', function (Blueprint $table){
            $table->dropForeign('articles_section_id_foreign');
        });
        Schema::dropIfExists('articles_sections');
    }
}
