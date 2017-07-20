<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHcWarehousesUsersConnTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hc_warehouses_users_conn', function(Blueprint $table)
		{
			$table->foreign('user_id', 'fk_hc_warehouses_users_conn_hc_users1')->references('id')->on('hc_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('warehouse_id', 'fk_hc_warehouses_users_conn_hc_warehouses1')->references('id')->on('hc_warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hc_warehouses_users_conn', function(Blueprint $table)
		{
			$table->dropForeign('fk_hc_warehouses_users_conn_hc_users1');
			$table->dropForeign('fk_hc_warehouses_users_conn_hc_warehouses1');
		});
	}

}
