<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_role_permission', function (Blueprint $table) {
            $table->bigIncrements('role_permission_id');
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->integer('hospital_id');
            $table->integer('hospital_user_id');
            $table->enum('view', ['0', '1'])->default('0');
            $table->enum('add', ['0', '1'])->default('0');
            $table->enum('edit', ['0', '1'])->default('0');
            $table->enum('export', ['0', '1'])->default('0');
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
        //Schema::dropIfExists('hospital_role_permission');
    }
}
