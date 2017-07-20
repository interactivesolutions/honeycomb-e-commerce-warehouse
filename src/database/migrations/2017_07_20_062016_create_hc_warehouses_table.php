<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_warehouses', function(Blueprint $table)
		{
			$table->integer('count', true);
			$table->string('id', 36)->unique('id_UNIQUE');
			$table->timestamps();
			$table->softDeletes();
			$table->string('reference')->unique('reference_UNIQUE');
			$table->string('name');
			$table->enum('management_type', array('FIFO','LIFO'));
			$table->string('currency', 3);
			$table->text('contacts', 65535)->nullable();
			$table->text('address', 65535)->nullable();
			$table->string('country_id', 36)->index('fk_hc_warehouses_hc_regions_countries1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_warehouses');
	}

}
