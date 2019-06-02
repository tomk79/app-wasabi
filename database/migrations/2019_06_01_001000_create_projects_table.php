<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function (Blueprint $table) {
			$table->uuid('id', 36)->primary();
			$table->string('name');
			$table->string('description')->nullable();
			$table->uuid('group_id', 36);
			$table->uuid('creator_user_id', 36);
			$table->mediumtext('icon')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('group_id')->references('id')->on('groups'); // foreignkey制約
			$table->foreign('creator_user_id')->references('id')->on('users'); // foreignkey制約
		});

		Schema::create('user_project_relations', function (Blueprint $table) {
			$table->uuid('user_id', 36);
			$table->uuid('project_id', 36);
			$table->string('role');

			$table->foreign('user_id')->references('id')->on('users'); // foreignkey制約
			$table->foreign('project_id')->references('id')->on('projects'); // foreignkey制約
			$table->unique(['user_id', 'project_id']); // 複合unique制約
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_project_relations');
		Schema::dropIfExists('projects');
	}
}
