<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInIndicatorsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators_data', function (Blueprint $table) {
            $table->string('source_of_information', 50)->nullable();
            $table->string('contact_number', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->text('dear_patient_info')->nullable();
            $table->string('appointment_process',30)->nullable();
            $table->string('rmo_and_consultant',30)->nullable();
            $table->string('welcome_desk',30)->nullable();
            $table->string('opd_reception',30)->nullable();
            $table->string('billing_section',30)->nullable();
            $table->string('pharmacy',30)->nullable();
            $table->string('nurses_behaviaur',30)->nullable();
            $table->string('pathology_lab',30)->nullable();
            $table->string('canteen',30)->nullable();
            $table->string('sonography',30)->nullable();
            $table->string('any_suggestion',100)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicators_data', function (Blueprint $table) {
            //
        });
    }
}
