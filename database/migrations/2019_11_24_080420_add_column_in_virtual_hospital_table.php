<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInVirtualHospitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtual_hospital', function (Blueprint $table) {
            $table->string('address',100)->nullable()->after('official_email');
            $table->string('state',50)->nullable()->after('address');
            $table->string('city',100)->nullable()->after('state');
            $table->string('latitude',50)->nullable()->after('city');
            $table->string('longitude',50)->nullable()->after('latitude');
        });

        Schema::table('virtual_hospital_data', function (Blueprint $table) {
            $table->longText('floor_data')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('virtual_hospital', function (Blueprint $table) {
            //
        });
    }
}
