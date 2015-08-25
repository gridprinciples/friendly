<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFriendsTable extends Migration {

	public function up()
	{
		Schema::create('friends', function(Blueprint $table) {
			$table->bigIncrements('id');

			$table->integer('user_id')->unsigned()->index();
			$table->integer('other_user_id')->unsigned()->index();

			$table->string('name')->nullable()->default('friend'); // party_id's title.  "Party names Other Party"
			$table->string('other_name')->nullable()->default('friend'); // other_party_id's title. "Other Party names Party"

			$table->date('start')->nullable(); // When did this relationship begin?
			$table->date('end')->nullable(); // When did this relationship end?

			$table->timestamp('approved_at')->nullable();

			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('friends');
	}
}
