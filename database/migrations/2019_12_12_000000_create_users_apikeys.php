<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersApikeys extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_apikeys', function (Blueprint $table) {
			$table->uuid('id', 36)->primary();
			$table->uuid('user_id', 36);
			$table->text('apikey');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('user_id')->references('id')->on('users'); // foreignkey制約
		});

		Schema::create('users_apikeys_logs', function (Blueprint $table) {
			$table->uuid('apikey_id', 36);
			$table->text('log');
			$table->string('client_ip');
			$table->string('client_user_agent');
			$table->timestamps();

			$table->foreign('apikey_id')->references('id')->on('users_apikeys'); // foreignkey制約
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users_apikeys_logs');
		Schema::dropIfExists('users_apikeys');
	}
}
