<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserApikeys extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_apikeys', function (Blueprint $table) {
			$table->uuid('id', 36)->primary();
			$table->uuid('user_id', 36);
			$table->string('name');
			$table->string('description')->nullable();
			$table->text('apikey');
			$table->timestamps();
			$table->softDeletes();

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
		Schema::dropIfExists('user_apikeys');
	}
}
