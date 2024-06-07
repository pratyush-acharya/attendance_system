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
        Schema::create('feedback_emails', function (Blueprint $table) {
            $table->id();
            $table->longText('email_to');
            $table->longText('email_cc')->nullable();
            // $table->foreign('email_to')->references('id')->on('users');
            // $table->foreign('email_cc')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_emails');
    }
};
