<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Add Creation and Update Relationhip to User
        Schema::table('shared_objects', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });

        //Add User, SharedObject and ReservationTemplate Relationhip to User
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreign('shared_object_id')->references('id')->on('shared_objects');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('recurring_resservation_id')->references('id')->on('reservation_templates');
        });

        //Add User, SharedObject and ReservationTemplate Relationhip to User
        Schema::table('reservation_templates', function (Blueprint $table) {
            $table->foreign('shared_object_id')->references('id')->on('shared_objects');
            $table->foreign('user_id')->references('id')->on('users');
        });

        //Add User, SharedObject and ReservationTemplate Relationhip to User
        Schema::table('shared_object_user', function (Blueprint $table) {
            $table->foreign('shared_object_id')->references('id')->on('shared_objects');
            $table->foreign('user_id')->references('id')->on('users');
        });

        //Add User, SharedObject and ReservationTemplate Relationhip to User
        Schema::table('conflicting_reservations', function (Blueprint $table) {
            $table->foreign('reservation_1_id')->references('id')->on('reservations');
            $table->foreign('reservation_2_id')->references('id')->on('reservations');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('shared_objects', function (Blueprint $table) {
            $table->dropForeign('shared_objects_created_by_foreign');
            $table->dropForeign('shared_objects_updated_by_foreign');
        });

        //
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign('reservations_recurring_resservation_id_foreign');
            $table->dropForeign('reservations_shared_object_id_foreign');
            $table->dropForeign('reservations_user_id_foreign');
        });

        //
        Schema::table('reservation_templates', function (Blueprint $table) {
            $table->dropForeign('reservation_templates_shared_object_id_foreign');
            $table->dropForeign('reservation_templates_user_id_foreign');
        });

        //
        Schema::table('shared_object_user', function (Blueprint $table) {
            $table->dropForeign('shared_object_user_shared_object_id_foreign');
            $table->dropForeign('shared_object_user_user_id_foreign');
        });

        //
        Schema::table('conflicting_reservations', function (Blueprint $table) {
            $table->dropForeign('conflicting_reservations_reservation_1_id_foreign');
            $table->dropForeign('conflicting_reservations_reservation_2_id_foreign');
        });
    }
}
