<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserForeignAccountsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_foreign_accounts', function (Blueprint $table) {
			$table->uuid('id', 36)->primary();
			$table->uuid('user_id', 36);
			$table->char('foreign_service_id', 255);
			$table->char('space', 255);
			$table->json('auth_info')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('user_id')->references('id')->on('users'); // foreignkey制約
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_foreign_accounts');
	}
}
