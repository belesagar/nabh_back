<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_menu', function (Blueprint $table) {
            $table->bigIncrements('menu_id');
            $table->string('menu_name',50);
            $table->string('menu_details',50)->nullable();
            $table->string('menu_icon_name',100)->nullable();
            $table->string('menu_url',100)->nullable();
            $table->string('menu_priority',100)->nullable();
            $table->string('view_key',50)->nullable();
            $table->string('add_key',50)->nullable();
            $table->string('edit_key',50)->nullable();
            $table->string('export_key',50)->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
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
        //Schema::dropIfExists('hospital_menu');
    }
}
