<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCreatedAtAndUpdatedAtInAdminUserTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            DB::statement('ALTER TABLE admin_users CHANGE created_at created_at DATETIME DEFAULT NULL;');
            DB::statement('ALTER TABLE admin_users CHANGE COLUMN updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('admin_users', function (Blueprint $table) {
        //     $table->timestamps()->change();
        // });
    }
}
