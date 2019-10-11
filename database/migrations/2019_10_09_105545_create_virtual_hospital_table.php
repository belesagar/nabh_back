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
            $table->string('total_no_of_beds', 30)->nullable();
            $table->string('no_of_opd_patient_per_day', 30)->nullable();
            $table->string('no_of_old_follow_up_patient_per_day', 30)->nullable();
            $table->string('no_of_new_follow_up_patient_per_day', 30)->nullable();
            $table->string('occupancy_rate_in_hospital', 30)->nullable();
            $table->string('total_no_staff', 30)->nullable();
            $table->string('how_many_number_of_wards_in_ipd', 30)->nullable();
            $table->string('have_hmis_software', 30)->nullable();
            $table->string('user_count_in_hmis_software', 30)->nullable();
            $table->string('departments_connect_in_lan', 30)->nullable();
            $table->string('internet_connection_speed', 30)->nullable();
            $table->string('backup_line_available', 30)->nullable();
            $table->string('computer_count_in_hospital', 30)->nullable();
            $table->string('ot_count_in_hospital', 30)->nullable();
            $table->enum('pathology_lab_available_or_not', ['Yes', 'No', 'Outsourced'])->nullable();
            $table->enum('radiology_lab_available_or_not', ['Yes', 'No', 'Outsourced'])->nullable();
            $table->enum('pharmacy_lab_available_or_not', ['Yes', 'No', 'Outsourced'])->nullable();
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
        Schema::dropIfExists('virtual_hospital');
    }
}
