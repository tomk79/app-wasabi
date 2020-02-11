<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logs', function (Blueprint $table) {
			$table->uuid('id', 36)->nullable();
			$table->uuid('user_id', 36)->nullable();
			$table->text('user_name')->nullable();
			$table->text('user_apikey')->nullable();
			$table->uuid('group_id', 36)->nullable();
			$table->text('group_name')->nullable();
			$table->uuid('project_id', 36)->nullable();
			$table->text('project_name')->nullable();
			$table->char('wasabiapp_id', 255)->nullable();
			$table->ipAddress('ip_address')->nullable();
			$table->text('target')->nullable();
			$table->text('action')->nullable();
			$table->json('options')->nullable();
			$table->text('comment')->nullable();
			$table->timestamps();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('logs');
	}
}
