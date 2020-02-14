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
		Schema::create('wasabiapp_pickles2_pages', function (Blueprint $table) {
			$table->uuid('project_id', 36);
			$table->uuid('asignee_id', 36)->nullable();
			$table->text('path');
			$table->text('title')->nullable();
			$table->char('status',32)->nullable();
			$table->dateTimeTz('end_date')->nullable();
			$table->timestamps();

			$table->foreign('project_id')->references('id')->on('projects'); // foreignkey制約
			$table->foreign('asignee_id')->references('id')->on('users'); // foreignkey制約
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('wasabiapp_pickles2_pages');
	}
}
