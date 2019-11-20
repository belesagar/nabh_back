<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedIndexOnColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtual_hospital_data', function (Blueprint $table) {
            $table->index(['hospital_id','virtual_hospital_id'])->change();
            
        });

        Schema::table('virtual_hospital_assets_data', function (Blueprint $table) {
            $table->index('hospital_id')->change();
            
        });

        Schema::table('virtual_hospital', function (Blueprint $table) {
            $table->index('hospital_id')->change();
            
        });

        Schema::table('virtual_hospital_assets_data', function (Blueprint $table) {
            $table->index('virtual_hospital_data_id')->change();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
