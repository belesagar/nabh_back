<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityStateColumnInHospitalPatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_patient_table', function (Blueprint $table) {
            $table->string('city', 30)->after('mobile')->nullable();
            $table->string('state', 30)->after('mobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_patient_table', function (Blueprint $table) {
            //
        });
    }
}
