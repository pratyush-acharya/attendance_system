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
        Schema::create('group_subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_subject_id');
            $table->unsignedBigInteger('user_id');
            $table->string('days');
            $table->integer('max_class_per_day')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('group_subject_id')->references('id')->on('group_subject')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_subject_teacher');
    }
};
