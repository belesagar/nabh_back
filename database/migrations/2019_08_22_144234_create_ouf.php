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
            $table->integer('hospital_id');
            $table->integer('indicators_id');
            $table->string('indicators_unique_id',50)->unique();
            $table->string('name_of_patient',100)->nullable();
            $table->string('pid',50)->nullable();
            $table->date('date')->nullable();
            $table->string('name_of_surgery',100)->nullable();
            $table->string('name_of_surgeon',100)->nullable();
            $table->string('charges_of_surgeon',50)->nullable(); 
            $table->string('charges_of_anaesthesiologist',100)->nullable();
            $table->integer('anaesthesia_id')->nullable();
            $table->string('modification_of_plan_anaesthesia',10)->nullable();
            $table->text('reason_for_modification_of_plan_anaesthesia')->nullable();
            $table->string('adverse_anesthesia_reaction',10)->nullable();
            $table->text('description_of_adverse_anesthesia_reaction')->nullable();
            $table->integer('ot_id')->nullable();
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->string('original_scheduled_time',10)->nullable();
            $table->string('cleaning_time',10)->nullable();
            $table->string('rescheduling_of_surgeries',10)->nullable();
            $table->text('reason_for_reschedule')->nullable();
            $table->string('utilization_time',100)->nullable();
            $table->string('re-exploration_procedure',10)->nullable();
            $table->string('reason_for_re-exploration',200)->nullable();
            $table->string('surgical_site_infection',10)->nullable();
            $table->string('reason_for_surgical_site_infection',200)->nullable();
            $table->string('sample_for_culture_and_sensitivity',10)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('indicators_data');
    }
}
