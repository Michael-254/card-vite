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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained();
            $table->string('tree_number');
            $table->string('uom')->nullable();
            $table->string('fruit_collection_quantity')->nullable();
            $table->string('farm_collection_quantity')->nullable();
            $table->string('nursery_transport_quantity')->nullable();
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
        Schema::dropIfExists('trees');
    }
};
