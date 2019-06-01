<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orgs', function (Blueprint $table) {
			$table->uuid('id', 36)->primary();
			$table->string('name');
			$table->string('account')->unique();
			$table->string('description')->nullable();
			$table->uuid('parent_org_id', 36)->nullable();
			$table->uuid('creator_user_id', 36);
			$table->mediumtext('icon')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('creator_user_id')->references('id')->on('users'); // foreignkey制約
		});
		Schema::table('orgs', function (Blueprint $table) {
			$table->foreign('parent_org_id')->references('id')->on('orgs'); // foreignkey制約
		});

		Schema::create('user_org_relations', function (Blueprint $table) {
			$table->uuid('user_id', 36);
			$table->uuid('org_id', 36);
			$table->string('role');

			$table->foreign('user_id')->references('id')->on('users'); // foreignkey制約
			$table->foreign('org_id')->references('id')->on('orgs'); // foreignkey制約
			$table->unique(['user_id', 'org_id']); // 複合unique制約
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_org_relations');
		Schema::dropIfExists('orgs');
	}
}
