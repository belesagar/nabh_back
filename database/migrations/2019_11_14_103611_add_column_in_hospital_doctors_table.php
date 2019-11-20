<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInHospitalDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_doctors', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('mobile');
        });

        Schema::table('hospital_patient_table', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('mobile');
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
