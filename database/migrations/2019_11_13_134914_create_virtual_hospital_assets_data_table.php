<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualHospitalAssetsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_hospital_assets_data', function (Blueprint $table) {
            $table->bigIncrements('virtual_hospital_assets_data_id');
            $table->integer('hospital_id');
            $table->integer('virtual_hospital_data_id');
            $table->string('name',10)->nullable();
            $table->string('number_of_beds',10)->nullable();
            $table->string('no_of_patient_in_per_day',10)->nullable();
            $table->string('no_of_admission_per_day',50)->nullable();
            $table->enum('type',['OT', 'OPD', 'WARD'])->nullable();
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
        //Schema::dropIfExists('virtual_hospital_assets_data');
    }
}
