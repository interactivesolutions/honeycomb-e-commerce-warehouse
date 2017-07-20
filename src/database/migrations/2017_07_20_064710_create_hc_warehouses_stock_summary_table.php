<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcWarehousesStockSummaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_warehouses_stock_summary', function(Blueprint $table)
		{
			$table->integer('count', true);
			$table->string('id', 36)->unique('id_UNIQUE');
			$table->timestamps();
			$table->softDeletes();
			$table->string('good_id', 36)->index('fk_hc_warehouses_stock_summary_hc_goods1_idx');
			$table->string('warehouse_id', 36)->index('fk_hc_warehouses_stock_summary_hc_warehouses1_idx');
			$table->float('ordered', 10, 0);
			$table->float('in_transit', 10, 0);
			$table->float('on_sale', 10, 0);
			$table->float('reserved', 10, 0);
			$table->float('ready_for_shipment', 10, 0);
			$table->float('total', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_warehouses_stock_summary');
	}

}
