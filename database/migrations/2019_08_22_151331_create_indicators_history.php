<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators_history', function (Blueprint $table) {
            $table->bigIncrements('indicators_history_id');
            $table->integer('hospital_id');
            $table->integer('indicator_id');
            $table->integer('indicator_data_id');
            $table->integer('updated_by_id');
            $table->json('updated_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators_history');
    }
}
