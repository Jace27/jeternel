<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('nonvip_low', 12, 2, true)->unsigned();
            $table->decimal('nonvip_high', 12, 2, true)->unsigned();
            $table->decimal('vip_low', 12, 2, true)->unsigned();
            $table->decimal('vip_high', 12, 2, true)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
