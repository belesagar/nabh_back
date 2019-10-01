<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInIndicatorsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators_data', function (Blueprint $table) {
            $table->string('mediclaim_dept', 50)->nullable();
            $table->string('patient_coordinator', 50)->nullable();
            $table->string('housekeeping', 50)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicators_data', function (Blueprint $table) {
            //
        });
    }
}
