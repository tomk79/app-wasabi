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
        DB::table('users')->insert([
            'name' => 'TESTER 1',
            'email' => 'tester1@example.com',
            'password' => bcrypt('tester'),
        ]);
        DB::table('users')->insert([
            'name' => 'TESTER 2',
            'email' => 'tester2@example.com',
            'password' => bcrypt('tester'),
        ]);
    }
}
