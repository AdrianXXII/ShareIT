<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConflictingReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conflicting_reservations', function (Blueprint $table) {
            $table->integer('reservation_1_id',false,true)->length(10);
            $table->integer('reservation_2_id',false,true)->length(10);

            $table->primary(['reservation_1_id','reservation_2_id'],'conflicting_reservations_primary_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conflicting_reservations');
    }
}
