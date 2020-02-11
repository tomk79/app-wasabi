<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectWasabiappRelationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_wasabiapp_relations', function (Blueprint $table) {
			$table->uuid('id', 36);
			$table->uuid('project_id', 36)->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('project_id')->references('id')->on('projects'); // foreignkey制約
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('project_wasabiapp_relations');
	}
}
