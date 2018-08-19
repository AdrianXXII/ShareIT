<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedObjectUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_object_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->length(10);
            $table->unsignedInteger('shared_object_id')->length(10);

            $table->primary(['user_id','shared_object_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_object_user');
    }
}
