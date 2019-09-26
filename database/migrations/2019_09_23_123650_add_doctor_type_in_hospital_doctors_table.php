<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoctorTypeInHospitalDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_doctors', function (Blueprint $table) {
            $table->string('doctor_type',60)->nullable();
            $table->dropColumn('doctor_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_doctors', function (Blueprint $table) {
            $table->integer('doctor_type_id')->nullable();
        });
    }
}
