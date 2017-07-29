<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuildIsServerPack extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('builds', function(Blueprint $table) {
            $table->boolean('is_server_pack')->default(false);
            $table->boolean('server_pack_is_built')->default(false);
        } );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

        Schema::table('builds', function(Blueprint $table) {
            $table->removeColumn('is_server_pack');
            $table->removeColumn('server_pack_is_built');
        } );
	}

}
