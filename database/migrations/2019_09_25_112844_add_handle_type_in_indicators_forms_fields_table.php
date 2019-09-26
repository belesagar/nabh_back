<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHandleTypeInIndicatorsFormsFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators_forms_fields', function (Blueprint $table) {
            $table->enum('handle_type',['inside','outside'])->after('data_show_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicators_forms_fields', function (Blueprint $table) {
            //
        });
    }
}
