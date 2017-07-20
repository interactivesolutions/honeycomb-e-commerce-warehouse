<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHcWarehousesStockHistoryActionsTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hc_warehouses_stock_history_actions_translations', function(Blueprint $table)
		{
			$table->foreign('language_code', 'fk_hc_goods_translations_hc_languages1')->references('iso_639_1')->on('hc_languages')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('record_id', 'fk_hc_warehouses_stock_history_actions_translations_hc_wareh1')->references('id')->on('hc_warehouses_stock_history_actions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hc_warehouses_stock_history_actions_translations', function(Blueprint $table)
		{
			$table->dropForeign('fk_hc_goods_translations_hc_languages1');
			$table->dropForeign('fk_hc_warehouses_stock_history_actions_translations_hc_wareh1');
		});
	}

}
