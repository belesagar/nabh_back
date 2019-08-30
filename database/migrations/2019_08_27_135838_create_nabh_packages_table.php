<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNabhPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nabh_packages', function (Blueprint $table) {
            $table->bigIncrements('nabh_packages_id');
            $table->enum('package_name', ['SILVER', 'GOLD','PLATINUM']);
            $table->double('package_amount', 10, 2);
            $table->double('per_month_amount', 10, 2);
            $table->string('indicators_type',30);
            $table->string('no_of_indicators_allowed',30);
            $table->string('no_of_user_allowed',30);
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
        Schema::dropIfExists('nabh_packages');
    }
}
