<?php

use Illuminate\Database\Seeder;

class DummyUserSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        for( $i = 1; $i <= 20; $i ++ ){
            DB::table('users')->insert(array(
                'name' => 'TESTER '.$i,
                'account' => 'tester'.$i,
                'email' => 'tester'.$i.'@example.com',
                'password' => bcrypt('tester'),
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ));
        }
    }
}
