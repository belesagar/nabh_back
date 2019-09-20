<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageReferenceNumberInNabhPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nabh_packages', function (Blueprint $table) {
            $table->string('package_reference_number',50)->after('nabh_packages_id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nabh_packages', function (Blueprint $table) {
            //
        });
    }
}
