<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('offer_id');
            $table->string('offer_code',50);
            $table->text('details');
            $table->text('message');
            $table->enum('amount_type', ['AMOUNT', 'PERCENT'])->default('AMOUNT');
            $table->float('amount',10,2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            // $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
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
        //Schema::dropIfExists('offers');
    }
}
