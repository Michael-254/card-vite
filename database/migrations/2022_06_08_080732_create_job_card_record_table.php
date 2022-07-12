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
        Schema::create('job_card_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained();
            $table->foreignId('record_id')->constrained();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('child_activity_id');
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
        Schema::dropIfExists('job_card_record');
    }
};
