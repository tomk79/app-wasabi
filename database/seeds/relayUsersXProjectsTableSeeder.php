<?php

use Illuminate\Database\Seeder;

class relayUsersXProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('relay_users_x_projects')->insert(array(
            'user_id' => 1,
            'project_id' => 1,
            'authority' => 10,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));
        DB::table('relay_users_x_projects')->insert(array(
            'user_id' => 2,
            'project_id' => 2,
            'authority' => 10,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));
        DB::table('relay_users_x_projects')->insert(array(
            'user_id' => 2,
            'project_id' => 1,
            'authority' => 5,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));
    }
}
