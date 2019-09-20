<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_transaction_table', function (Blueprint $table) {
            $table->bigIncrements('temp_transaction_id');
            $table->string('temp_transaction_unique_id',100)->unique();
            $table->integer('hospital_id');
            $table->integer('user_id');
            $table->integer('package_id');
            $table->text('package_details');
            $table->float('amount_of_order',10,2);
            $table->integer('offer_id')->nullable();
            $table->float('offer_amount',10,2)->nullable();
            $table->float('total_amount',10,2)->nullable();
            $table->string('transaction_error_msg',50)->nullable();
            $table->enum('status',['INITIATE','PENDING','CANCELLED','SUCCESS'])->default('INITIATE');
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
        //Schema::dropIfExists('temp_transaction_table');
    }
}
