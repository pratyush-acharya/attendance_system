<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('group_subject_teacher_id');
            $table->integer('present');
            $table->integer('absent');
            $table->integer('leave');
            // $table->enum('status',['present', 'absent', 'leave']);
            $table->date('date')->default(date('Y-m-d'));
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('group_subject_teacher_id')->references('id')->on('group_subject_teacher')->restrictOnDelete()->cascadeOnUpdate();
        });
        // Schema::table('attendances', function (Blueprint $table) {
        //     $table->dropColumn('status');
        //     $table->integer('present')->after('group_subject_teacher_id');
        //     $table->integer('absent')->after('present');
        //     $table->integer('leave')->after('absent');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
