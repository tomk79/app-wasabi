<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWasabiappPickles2PagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wasabiapp_abstract_taskmanaer_auth', function (Blueprint $table) {
			$table->uuid('user_id', 36)->nullable();
			$table->uuid('project_id', 36)->nullable();
			$table->text('space')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('project_id')->references('id')->on('projects'); // foreignkey制約
			$table->foreign('user_id')->references('id')->on('users'); // foreignkey制約
			$table->primary(['project_id', 'user_id']); // 複合unique制約
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('wasabiapp_abstract_taskmanaer_auth');
	}
}
