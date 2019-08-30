<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInHospitalUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_users', function (Blueprint $table) {
            $table->string('password',100);
            $table->text('indicators_ids')->nullable();
            $table->string('address',200)->nullable();
        });

        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('city',50)->nullable();
            $table->string('state',50)->nullable();
            $table->string('address',200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
