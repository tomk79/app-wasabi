<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWasabiappAbstractTaskmanagerProjectConfsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wasabiapp_abstract_taskmanager_project_confs', function (Blueprint $table) {
			$table->uuid('project_id', 36);
			$table->char('foreign_service_id', 255);
			$table->text('space')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('project_id')->references('id')->on('projects')->name('wasabiapp_abstaskmgr_pjconf_fk'); // foreignkey制約
			$table->unique(["project_id"],"wasabiapp_abstaskmgr_pjconf_unq");
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('wasabiapp_abstract_taskmanager_project_confs');
	}
}
