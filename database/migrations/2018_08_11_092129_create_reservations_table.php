<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shared_object_id',false,true)->length(10)->nullable(false);
            $table->integer('user_id',false,true)->length(10)->nullable(false);
            $table->integer('recurring_resservation_id',false,true)->length(10)->nullable(true);
            $table->integer('type',false,true)->nullable(false);
            $table->integer('priority',false,true)->nullable(false);
            $table->timestamp('date')->nullable(false);
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->string('reason',250)->nullable(true);
            $table->boolean('manuel')->nullable(false);
            $table->boolean('deleted')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
