<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_users', function (Blueprint $table) {
            $table->bigIncrements('hospital_user_id');
            $table->integer('hospital_id')->index();
            $table->string('user_unique_id',40)->unique();
            $table->string('name',50);
            $table->string('email',60)->unique();
            $table->string('mobile',15)->unique()->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',50)->nullable();
            $table->integer('role_id')->index();
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
        //Schema::dropIfExists('hospital_users');
    }
}
