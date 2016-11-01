<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
            'user_id' => 1,
            'name' => 'TEST PROJECT 1',
            'account' => 'test_project_1',
        ]);
        DB::table('projects')->insert([
            'user_id' => 2,
            'name' => 'TEST PROJECT 2',
            'account' => 'test_project_2',
        ]);
    }
}
