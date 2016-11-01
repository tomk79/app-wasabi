<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelayUsersXProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relay_users_x_projects', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('project_id');
            $table->integer('authority');
            $table->timestamps();

            // foreignkey制約
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('relay_users_x_projects');
    }
}
