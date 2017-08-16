<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewManagementTypeToHcWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE hc_warehouses CHANGE COLUMN management_type management_type ENUM('FIFO','LIFO','FEFO')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE hc_warehouses CHANGE COLUMN management_type management_type ENUM('FIFO','LIFO')");
    }
}
