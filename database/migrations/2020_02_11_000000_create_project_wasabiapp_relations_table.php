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
			$table->uuid('project_id', 36);
			$table->char('wasabiapp_id', 32);
			$table->timestamps();

			$table->foreign('project_id')->references('id')->on('projects'); // foreignkey制約
			$table->primary(['project_id', 'wasabiapp_id'])->name("wsb_proj_app_rels_projid_appid_primary"); // 複合unique制約
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
