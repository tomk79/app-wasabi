<?php

use Illuminate\Database\Seeder;

class DummyProjectSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('projects')->insert(array(
            'user_id' => 1,
            'name' => 'TEST PROJECT 1',
            'account' => 'test_project_1',
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));
        DB::table('projects')->insert(array(
            'user_id' => 2,
            'name' => 'TEST PROJECT 2',
            'account' => 'test_project_2',
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));
    }
}
