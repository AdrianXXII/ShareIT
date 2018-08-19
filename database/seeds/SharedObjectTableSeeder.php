<?php

use Illuminate\Database\Seeder;

class SharedObjectTableSeeder extends Seeder
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

        // Shared Object Shower
        DB::table('shared_objects')->insert([
            'designation' => 'Shower',
            'description' => 'Family shower in the family home.',
            'created_at' => new Carbon\Carbon(),
            'updated_at' => new Carbon\Carbon(),
            'created_by' => 1,
            'updated_by' => 1
        ]);

        // Share Object with Users
        DB::table('shared_object_user')->insert([
            'user_id' => $toni->id,
            'shared_object_id' => 1
        ]);
        DB::table('shared_object_user')->insert([
            'user_id' => $julia->id,
            'shared_object_id' => 1
        ]);
        DB::table('shared_object_user')->insert([
            'user_id' => $mable->id,
            'shared_object_id' => 1
        ]);

        // Create Crosstrainer
        DB::table('shared_objects')->insert([
            'designation' => 'Crosstrainer',
            'description' => 'Crosstrainer in the Living Room',
            'created_at' => new Carbon\Carbon(),
            'updated_at' => new Carbon\Carbon(),
            'created_by' => 2,
            'updated_by' => 2
        ]);

        // Share Object with Users
        DB::table('shared_object_user')->insert([
            'user_id' => $toni->id,
            'shared_object_id' => 2
        ]);

        DB::table('shared_object_user')->insert([
            'user_id' => $julia->id,
            'shared_object_id' => 2
        ]);
    }
}
