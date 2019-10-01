<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoctorDegreeAndDesignationInHospitalDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_doctors', function (Blueprint $table) {
            $table->string('degree', 30)->after('mobile')->nullable();
            $table->string('designation', 30)->after('mobile')->nullable();

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
            //
        });
    }
}
