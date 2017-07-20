<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcWarehousesStockHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_warehouses_stock_history', function(Blueprint $table)
		{
			$table->integer('count', true);
			$table->string('id', 36)->unique('id_UNIQUE');
			$table->timestamps();
			$table->softDeletes();
			$table->string('good_id', 36)->index('fk_hc_warehouses_stock_history_hc_goods1_idx');
			$table->string('warehouse_id', 36)->index('fk_hc_warehouses_stock_history_hc_warehouses1_idx');
			$table->string('action_id', 36)->index('fk_hc_warehouses_stock_history_hc_warehouses_stock_histor_idx');
			$table->string('user_id', 36)->index('fk_hc_warehouses_stock_history_hc_users1_idx');
			$table->float('amount', 10, 0);
			$table->float('prime_cost', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_warehouses_stock_history');
	}

}
