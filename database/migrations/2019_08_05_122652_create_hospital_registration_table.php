<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_registration', function (Blueprint $table) {
            $table->bigIncrements('hospital_id');
            $table->string('hospital_unique_id')->unique();
            $table->string('hospital_name');
            $table->string('spoc_name');
            $table->string('spoc_designation');
            $table->string('email')->unique();
            $table->string('mobile',15);
            $table->string('password',15);
            $table->string('city',40);
            $table->string('state',40);
            $table->integer('pincode');
            $table->integer('number_of_bed');
            $table->integer('nabh_group_id');
            $table->string('offer_code');
            $table->float('offer_amount',10,2);
            $table->float('total_amount',10,2);
            $table->enum('payment_status', ['PENDING', 'SUCCESS','CANCELLED'])->default('PENDING');
            $table->enum('status', ['ACTIVE', 'INACTIVE','SUSPENDED'])->default('ACTIVE');
            $table->integer('created_by');
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
        //Schema::dropIfExists('hospital_registration');
    }
}
