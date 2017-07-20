<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcWarehousesUsersConnTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_warehouses_users_conn', function(Blueprint $table)
		{
			$table->integer('count', true);
			$table->timestamps();
			$table->string('user_id', 36)->nullable()->index('fk_hc_warehouses_users_conn_hc_users1_idx');
			$table->string('warehouse_id', 36)->nullable()->index('fk_hc_warehouses_users_conn_hc_warehouses1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_warehouses_users_conn');
	}

}
