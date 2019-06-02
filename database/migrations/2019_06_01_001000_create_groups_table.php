<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('groups', function (Blueprint $table) {
			$table->uuid('id', 36)->primary();
			$table->string('name');
			$table->string('account')->unique()->nullable();
			$table->string('description')->nullable();
			$table->uuid('parent_group_id', 36)->nullable();
			$table->uuid('root_group_id', 36)->nullable();
			$table->uuid('creator_user_id', 36);
			$table->integer('private_flg');
			$table->mediumtext('icon')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('creator_user_id')->references('id')->on('users'); // foreignkey制約
		});
		Schema::table('groups', function (Blueprint $table) {
			$table->foreign('parent_group_id')->references('id')->on('groups'); // foreignkey制約
			$table->foreign('root_group_id')->references('id')->on('groups'); // foreignkey制約
		});

		Schema::create('user_group_relations', function (Blueprint $table) {
			$table->uuid('user_id', 36);
			$table->uuid('group_id', 36);
			$table->string('role');

			$table->foreign('user_id')->references('id')->on('users'); // foreignkey制約
			$table->foreign('group_id')->references('id')->on('groups'); // foreignkey制約
			$table->unique(['user_id', 'group_id']); // 複合unique制約
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_group_relations');
		Schema::dropIfExists('groups');
	}
}
