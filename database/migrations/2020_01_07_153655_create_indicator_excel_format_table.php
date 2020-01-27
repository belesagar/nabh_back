<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorExcelFormatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_excel_format', function (Blueprint $table) {
            $table->bigIncrements('indicator_excel_format_id');
            $table->integer('indicator_id');
            $table->string('excel_name',100);
            $table->longText('indicator_field_ids');
            $table->longText('calculation_fields')->nullable();
            $table->string('graph_list',100)->nullable();
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
        // Schema::dropIfExists('indicator_excel_format');
    }
}
