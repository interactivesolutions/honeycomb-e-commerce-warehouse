<?php

namespace interactivesolutions\honeycombecommercewarehouse\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistoryActions;
use League\Flysystem\Exception;

class HCHistoryActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $actions = [
            [
                'id'   => 'warehouse-replenishment-for-sale',
                'sign' => '1',
            ],
            [
                'id'   => 'warehouse-replenishment-reserve',
                'sign' => '1',
            ],
            [
                'id'   => 'reserved',
                'sign' => '0',
            ],
            [
                'id'   => 'warehouse-remove-from-sale',
                'sign' => '-1',
            ],
            [
                'id'   => 'warehouse-remove-from-reserved',
                'sign' => '-1',
            ],
            [
                'id'   => 'warehouse-remove-ready-for-shipment',
                'sign' => '-1',
            ],
            [
                'id'   => 'move-to-ready-for-shipment',
                'sign' => '0',
            ],
            [
                'id'   => 'cancel-ready-for-shipment',
                'sign' => '0',
            ],
            [
                'id'   => 'warehouse-cancel-reserved',
                'sign' => '0',
            ],
            [
                'id'   => 'warehouse-pre-ordered',
                'sign' => '0',
            ],
            [
                'id'   => 'remove-pre-ordered',
                'sign' => '0',
            ],
            [
                'id'   => 'warehouse-cancel-pre-ordered',
                'sign' => '0',
            ],
        ];

        DB::beginTransaction();

        try {
            foreach ( $actions as $key => $action ) {
                HCECStockHistoryActions::firstOrCreate($action);
            }
        } catch ( \Exception $e ) {
            DB::rollback();

            throw new Exception($e->getMessage());
        }

        DB::commit();
    }
}
