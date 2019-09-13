<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorsFormsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators_forms_fields', function (Blueprint $table) {
            $table->bigIncrements('form_id');
            $table->json('indicators_ids')->nullable();
            $table->string('form_type',50);
            $table->string('form_name',50);
            $table->string('label',150);
            $table->string('placeholder',150)->nullable();
            $table->string('id',50)->nullable();
            $table->string('class',100)->nullable();
            $table->string('data_show_type',50)->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('indicators_forms_fields');
    }
}
