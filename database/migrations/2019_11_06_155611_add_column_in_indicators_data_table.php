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
            $table->string('name_of_drug', 60)->nullable();
            $table->string('rout_of_drug_administration', 60)->nullable();
            $table->string('dose_of_drug', 60)->nullable();
            $table->string('along_with_suspected_drug', 50)->nullable();
            $table->string('treatment_given_for_adr', 50)->nullable();
            $table->string('reaction_after_stop_drug', 50)->nullable();
            $table->string('did_you_restart_suspected_drug', 50)->nullable();
            $table->string('reappear_after_start_suspected_drug', 50)->nullable();
            $table->string('final_outcome_of_adr', 150)->nullable();
            $table->string('clinical_diagnosis', 150)->nullable();
            $table->string('root_cause', 150)->nullable();
            $table->string('dod', 150)->nullable();
            $table->string('any_previously_known_allergies', 60)->nullable();
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
