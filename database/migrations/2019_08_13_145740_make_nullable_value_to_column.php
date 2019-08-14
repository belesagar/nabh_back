<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeNullableValueToColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_registration', function (Blueprint $table) {
             $table->string('password', 100)->nullable()->change();
             $table->integer('nabh_group_id')->nullable()->change();
             $table->string('offer_code',50)->nullable()->change();
             $table->float('offer_amount',10,2)->nullable()->change();
             $table->float('total_amount',10,2)->nullable()->change();
             $table->integer('created_by')->nullable()->change();
        });

        Schema::table('admin_users', function (Blueprint $table) {
             $table->string('reset_id', 50)->nullable()->change();
             $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
        });

        Schema::table('nabh_indicators', function (Blueprint $table) {
             $table->text('formula')->nullable()->change();
             $table->text('remark')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
