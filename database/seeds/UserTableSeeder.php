<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'username' => 'JuhLea',
            'email' => 'adrianxxii@gmail.com',
            'firstname' => 'Julia',
            'lastname' => 'Locher',
            'password' => bcrypt('The-Big-Sis'),
        ]);

        DB::table('users')->insert([
            'username' => 'Toni',
            'email' => 'adrian.locher@live.com',
            'firstname' => 'Anton',
            'lastname' => 'Locher',
            'password' => bcrypt('Father-Of-2'),
        ]);

        DB::table('users')->insert([
            'username' => 'Maybeline',
            'email' => 'adrian.locher@informaticon.com',
            'firstname' => 'Mable',
            'lastname' => 'Locher',
            'password' => bcrypt('Mother-Of-2'),
        ]);
    }
}
