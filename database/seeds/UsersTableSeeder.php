<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'JohnDoe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('johndoe'),
            'confirmed' => true
        ]);
    }
}
