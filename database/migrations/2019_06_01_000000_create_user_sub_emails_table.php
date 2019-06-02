<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSubEmailsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_sub_emails', function (Blueprint $table) {
			$table->uuid('user_id', 36);
			$table->string('email')->index();
			$table->timestamp('email_verified_at')->nullable();
			$table->timestamp('created_at')->nullable();

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
		Schema::dropIfExists('user_sub_emails');
	}
}
