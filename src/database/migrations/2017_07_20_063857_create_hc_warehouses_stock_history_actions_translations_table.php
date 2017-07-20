<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcWarehousesStockHistoryActionsTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_warehouses_stock_history_actions_translations', function(Blueprint $table)
		{
			$table->integer('count', true);
            $table->string('id', 36)->unique('id_UNIQUE');
			$table->timestamps();
			$table->softDeletes();
			$table->string('record_id', 36);
			$table->string('language_code', 2)->index('fk_hc_goods_translations_hc_languages1_idx');
			$table->string('name');
			$table->text('description', 65535)->nullable();

			$table->unique(['record_id','language_code'], 'hc_warehouses_stock_history_actions_translations');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_warehouses_stock_history_actions_translations');
	}

}
