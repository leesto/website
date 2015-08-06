<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		// Create the table
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('username', 10)->unique();
			$table->string('email')->unique();
			$table->string('password');
			$table->string('forename');
			$table->string('surname');
			$table->string('phone', 13);
			$table->text('address');
			$table->string('tool_colours');
			$table->date('dob');
			$table->boolean('show_email')->default(1);
			$table->boolean('show_phone')->default(0);
			$table->boolean('show_address')->default(0);
			$table->boolean('show_age')->default(0);
			$table->rememberToken();
			$table->timestamps();
			$table->boolean('status')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}
}
