<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOuf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('hospital_id')->unique();
            $table->string('name_of_patient',100);
            $table->string('pid',50);
            $table->date('date');
            $table->string('name_of_surgery',100);
            $table->string('name_of_surgeon',100);
            $table->string('charges_of_surgeon',50); 
            $table->string('charges_of_anaesthesiologist',100);
            $table->integer('anaesthesia_id');
            $table->string('modification_of_plan_anaesthesia',10);
            $table->text('reason_for_modification_of_plan_anaesthesia');
            $table->string('adverse_anesthesia_reaction',10);
            $table->text('description_of_adverse_anesthesia_reaction');
            $table->integer('ot_id');
            $table->time('in_time');
            $table->time('out_time');
            $table->string('original_scheduled_time',10);
            $table->string('cleaning_time',10);
            $table->string('rescheduling_of_surgeries',10);
            $table->text('reason_for_reschedule');
            $table->string('utilization_time',100);
            $table->string('re-exploration_procedure',10);
            $table->string('reason_for_re-exploration',200);
            $table->string('surgical_site_infection',10);
            $table->string('reason_for_surgical_site_infection',200);
            $table->string('sample_for_culture_and_sensitivity',10);
            
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
        Schema::dropIfExists('indicators_data');
    }
}
