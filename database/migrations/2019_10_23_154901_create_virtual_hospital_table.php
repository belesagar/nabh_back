<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualHospitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_hospital', function (Blueprint $table) {
            $table->bigIncrements('virtual_hospital_id');
            $table->integer('hospital_id');
            $table->string('total_number_of_beds',50)->nullable();
            $table->string('no_of_patient_opd_in_per_day',50)->nullable();
            $table->string('no_of_old_follow_patient_per_day',50)->nullable();
            $table->string('no_of_new_follow_patient_per_day',50)->nullable();
            $table->string('no_of_ipd_admission_per_day',50)->nullable();
            $table->string('occupany_rate_in_hospital',50)->nullable();
            $table->string('total_no_staff',50)->nullable();
            $table->string('no_of_ward_in_ipd',50)->nullable();
            $table->string('hmis_software_in_place',50)->nullable();
            $table->string('no_of_user_in_hmis_software',50)->nullable();
            $table->string('all_department_connect_in_lan',50)->nullable();
            $table->string('internet_speed',50)->nullable();
            $table->string('backup_line_available',50)->nullable();
            $table->string('no_of_computer_in_hospital',50)->nullable();
            $table->string('no_of_ot_in_hospital',50)->nullable();
            $table->string('pathology_lab_avilable_or_not',20)->nullable();
            $table->string('radiology_lab_avilable_or_not',20)->nullable();
            $table->string('pharmacy_lab_avilable_or_not',20)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virtual_hospital');
    }
}
