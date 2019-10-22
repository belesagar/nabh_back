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
            $table->string('name_of_staff', 50)->nullable();
            $table->string('summary_of_incidence', 100)->nullable();
            $table->date('date_of_incidence')->nullable();
            $table->string('time_of_incidence',10)->nullable();
            $table->string('patient_is_seropositive',30)->nullable();
            $table->string('site_of_injury',100)->nullable();
            $table->string('gloves',30)->nullable();
            $table->string('location_of_incidence',50)->nullable();
            $table->string('cause_of_injury',100)->nullable();
            $table->string('incidence_reported',20)->nullable();
            $table->string('treatment_given',100)->nullable();
            $table->string('type_of_injury',50)->nullable();
            $table->string('testing_for',100)->nullable();
            $table->string('test_conducted',50)->nullable();
            $table->string('reporting_error',20)->nullable();
            $table->string('description_of_error',100)->nullable();
            $table->string('error_noted_by',50)->nullable();
            $table->string('corrective_prevention_action',100)->nullable();
            $table->string('action_taken_by',50)->nullable();
            $table->string('test_advice',50)->nullable();
            $table->string('advice_to_redo_test',50)->nullable();
            $table->string('advice_by',50)->nullable();
            $table->string('reason_for_redo',50)->nullable();
            $table->string('root_cause_analysis',50)->nullable();
            $table->string('no_of_employee_for_safty_preacaution',50)->nullable();
            $table->string('no_of_employee_for_sampled',50)->nullable();
            $table->string('percentage_a_b',50)->nullable();
            $table->string('x_ray_study',50)->nullable();
            $table->string('no_of_shoots',50)->nullable();
            $table->string('x_ray_shoot_time',20)->nullable();
            $table->string('reporting_time',20)->nullable();
            $table->string('redo_x_ray',20)->nullable();
            $table->string('technician_name',50)->nullable();
            $table->string('employee_id',10)->nullable();
            $table->string('time_period_of_work_with_hospital',50)->nullable();
            $table->string('rate_for_location',10)->nullable();
            $table->string('rate_for_cleanliness',10)->nullable();
            $table->string('rate_for_safety',10)->nullable();
            $table->string('rate_for_working_satisfaction',10)->nullable();
            $table->string('rate_for_training',10)->nullable();
            $table->string('rate_for_salary_scale',10)->nullable();
            $table->string('rate_for_hr_department',10)->nullable();
            $table->string('rate_for_respect',10)->nullable();
            $table->string('rate_for_salary_on_time',10)->nullable();
            $table->string('rate_for_happy_with_us',10)->nullable();
            $table->string('utilize_medical_service',10)->nullable();
            $table->string('rights_and_responsibility_of_patient',10)->nullable();
            $table->string('time',10)->nullable();
            $table->string('cleaning_done_by',30)->nullable();
            $table->string('adequete_light',50)->nullable();
            $table->string('electric_supply',50)->nullable();
            $table->string('filter_cleaning',50)->nullable();
            $table->string('any_damage',50)->nullable();
            $table->string('any_abnormal_noise',50)->nullable();
            $table->string('outdoor_ac_unit_checking',15)->nullable();
            $table->string('door_lock',10)->nullable();
            $table->string('temperature_of_room',30)->nullable();
            $table->string('dusting_and_cleaning',10)->nullable();
            $table->string('voltage',30)->nullable();
            $table->string('charging',10)->nullable();
            $table->string('server_function',10)->nullable();
            $table->string('ups_supply_checking',10)->nullable();
            $table->string('back_up_date',15)->nullable();
            $table->string('cleaning_dusting',50)->nullable();
            $table->string('checking_of_belt',50)->nullable();
            $table->string('air_pressure',50)->nullable();
            $table->string('working_testing',50)->nullable();
            $table->string('medical_air',50)->nullable();
            $table->string('equipment_on',50)->nullable();
            $table->string('o2_cylinder_pressure',30)->nullable();
            $table->string('number_of_full_cylinder',30)->nullable();
            $table->string('no_of_on_cylinder',30)->nullable();
            $table->string('no_of_empty_cylinder',30)->nullable();
            $table->string('check_by',30)->nullable();
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
