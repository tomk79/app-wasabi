<?php

use Illuminate\Database\Seeder;

class DummyUserSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('users')->insert(array(
            'name' => 'TESTER 1',
            'email' => 'tester1@example.com',
            'password' => bcrypt('tester'),
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));
        DB::table('users')->insert(array(
            'name' => 'TESTER 2',
            'email' => 'tester2@example.com',
            'password' => bcrypt('tester'),
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));
    }
}
