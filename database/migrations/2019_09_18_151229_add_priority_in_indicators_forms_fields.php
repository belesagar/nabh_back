<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriorityInIndicatorsFormsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators_forms_fields', function (Blueprint $table) {
            $table->string('priority',5)->nullable();
        });

        Schema::table('indicators_data', function (Blueprint $table) {
            $table->string('type_of_anaesthesia',100)->nullable();
            $table->string('ot_name',100)->nullable();
            $table->integer('form_filled_by')->nullable();
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
