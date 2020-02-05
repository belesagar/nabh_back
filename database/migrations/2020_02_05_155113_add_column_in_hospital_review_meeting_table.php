<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInHospitalReviewMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_review_meeting', function (Blueprint $table) {
            $table->time('review_meeting_start_time')->after('review_meeting_start_date')->nullable();
            $table->time('review_meeting_end_time')->after('review_meeting_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('hospital_review_meeting', function (Blueprint $table) {
        //     //
        // });
    }
}
