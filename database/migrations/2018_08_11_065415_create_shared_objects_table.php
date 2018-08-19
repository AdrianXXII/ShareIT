<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_objects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('designation', 50)->nullable();
            $table->text('description')->nullable();
            $table->time('created_at');
            $table->unsignedInteger('created_by')->length(10);
            $table->time('updated_at');
            $table->integer('updated_by',false,true)->length(10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_objects');
    }
}
