<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalReviewMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_review_meeting', function (Blueprint $table) {
            $table->bigIncrements('hospital_review_meeting_id');
            $table->integer('hospital_id');
            $table->integer('hospital_user_id');
            $table->string('review_meeting_reference_number',60)->nullable();
            $table->string('review_meeting_type',60)->nullable();
            $table->string('review_meeting_title',200)->nullable();
            $table->text('purpose_review_meeting')->nullable();
            $table->date('review_meeting_date')->nullable();
            $table->string('location',50)->nullable();
            $table->dateTime('review_meeting_start_date')->nullable();
            $table->dateTime('review_meeting_end_date')->nullable();
            $table->integer('updated_by')->nullable();
            $table->enum('meeting_status', ['SCHEDULE', 'STARTED', 'COMPLETED', 'CANCELED'])->default('SCHEDULE');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
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
        //Schema::dropIfExists('hospital_review_meeting');
    }
}
