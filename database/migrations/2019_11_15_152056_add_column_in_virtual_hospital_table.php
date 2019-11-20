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
            $table->string('official_email',50)->nullable()->after('virtual_hospital_reference_number');
            $table->string('floor_count',10)->nullable()->after('official_email');

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
