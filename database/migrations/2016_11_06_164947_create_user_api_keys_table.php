<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserApiKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_api_keys', function (Blueprint $table) {
            $table->string('hash')->unique();
            $table->integer('user_id');
            $table->string('name');
            $table->string('authkey')->unique();
            $table->timestamps();
            $table->softDeletes();

            // foreignkey制約
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_api_keys');
    }
}
