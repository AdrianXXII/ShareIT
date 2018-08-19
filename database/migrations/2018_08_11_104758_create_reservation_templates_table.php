<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->length(10)->nullable(false);
            $table->unsignedInteger('shared_object_id')->length(10)->nullable(false);
            $table->integer('weekly_frequency')->nullable();
            $table->integer('monthly_frequency')->nullable();
            $table->integer('yearly_frequency')->nullable();
            $table->boolean('is_day_based')->nullable();
            $table->boolean('monday')->nullable();
            $table->boolean('tueday')->nullable();
            $table->boolean('wednesday')->nullable();
            $table->boolean('thursday')->nullable();
            $table->boolean('friday')->nullable();
            $table->boolean('saturday')->nullable();
            $table->boolean('sunday')->nullable();
            $table->integer('priority',false,true)->nullable();
            $table->integer('date')->nullable();
            $table->integer('month')->nullable();
            $table->string('reason',250)->nullable(true);
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_templates');
    }
}
