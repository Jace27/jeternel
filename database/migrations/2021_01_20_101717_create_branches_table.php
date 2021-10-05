<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('1c_id')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('address');
            $table->boolean('isvip')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performers_branches', function (Blueprint $table){
            $table->dropForeign('performers_branches_branch_id_foreign');
        });
        Schema::dropIfExists('branches');
    }
}
