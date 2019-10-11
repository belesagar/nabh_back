<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInIndicatorsFormsFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators_data', function (Blueprint $table) {
            $table->string('date_of_admission', 30)->nullable();
            $table->string('date_of_insertion_of_urinary_catheter', 30)->nullable();
            $table->string('date_of_removal_of_urinary_catheter', 30)->nullable();
            $table->string('total_device_days', 50)->nullable();
            $table->string('does_patient_develops_cauti', 50)->nullable();
            $table->string('symptoms_of_infection', 50)->nullable();
            $table->string('culture_report', 100)->nullable();
            $table->string('date_of_blood_transfusion', 50)->nullable();
            $table->string('blood_group', 50)->nullable();
            $table->string('blood_bag_number', 50)->nullable();
            $table->string('product', 50)->nullable();
            $table->string('b_t_start_time', 50)->nullable();
            $table->string('b_t_end_time', 50)->nullable();
            $table->string('description_of_reaction', 50)->nullable();
            $table->string('ward_name', 50)->nullable();
            $table->string('date_of_adr_occurrence', 30)->nullable();
            $table->string('time_of_adr_occurrence', 30)->nullable();
            $table->string('date_of_adr_reporting', 30)->nullable();
            $table->string('time_of_adr_reporting', 30)->nullable();
            $table->string('description_of_adr', 50)->nullable();
            $table->string('diagnosis', 50)->nullable();
            $table->string('date_of_Intubation', 30)->nullable();
            $table->string('reason_for_intubation', 50)->nullable();
            $table->string('date_of_extubation', 50)->nullable();
            $table->string('reason_for_extubation', 50)->nullable();
            $table->string('number_of_days_of_ventilation', 50)->nullable();
            $table->string('does_patient_develops_vap', 50)->nullable();
            $table->string('corrective_action', 50)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicators_forms_fields', function (Blueprint $table) {
            //
        });
    }
}
