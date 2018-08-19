<?php

use Illuminate\Database\Seeder;

class ReservationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Users
        $toni = DB::table('users')->where('username', 'Toni')->first();
        $julia = DB::table('users')->where('username', 'JuhLea')->first();
        $mable = DB::table('users')->where('username', 'Maybeline')->first();

        $showers = DB::table('shared_objects')->where('designation','Shower')->first();
        $xtrainer = DB::table('shared_objects')->where('designation','Crosstrainer')->first();

        // Shared Object Shower
        DB::table('reservations')->insert([
            'shared_object_id' => $showers->id,
            'user_id' => $julia->id,
            'type' => 1,
            'priority' => 1,
            'date' => '2018-09-12',
            'from' => '14:00',
            'to' => '15:00',
            'reason' => 'Needs Shower',
            'manuel' => true,
            'deleted' => false
        ]);

        // Shared Object Shower
        DB::table('reservations')->insert([
            'shared_object_id' => $showers->id,
            'user_id' => $mable->id,
            'type' => 1,
            'priority' => 1,
            'date' => '2018-09-12',
            'from' => '09:00',
            'to' => '10:00',
            'reason' => 'Needs Shower',
            'manuel' => true,
            'deleted' => false
        ]);
    }
}
