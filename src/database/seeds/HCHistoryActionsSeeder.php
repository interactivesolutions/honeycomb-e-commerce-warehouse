<?php

namespace interactivesolutions\honeycombecommercewarehouse\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistoryActions;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistoryActionsTranslations;
use League\Flysystem\Exception;

class HCHistoryActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
                'sign' => '0',
            ],
            [
                'id'   => 'reserved',
                'sign' => '0',
            ],
        ];

        $translations = [
            [
                [
                    'language_code' => 'lt',
                    'name'          => 'Sandėlio papildymas pardavimui',
                    'description'   => 'Kai prekės atvežamos į sandelį ir iš karto dedamos į pardavimą, sandelio atžvilgiu. Dar bus kiekvienos prekės nustatymuose bus koks turi būti mažiausias kieks sandelyje ir tik virš jo pardavinėti.',
                ],
                [
                    'language_code' => 'en',
                    'name'          => 'Warehouse replenishment for sale',
                ],
            ],
            [
                [
                    'language_code' => 'lt',
                    'name'          => 'Sandėlio rezervavimo papildymas',
                    'description'   => 'Kai prekės atvežamos į sandelį ir dedamos į rezervaciją.',
                ],
                [
                    'language_code' => 'en',
                    'name'          => 'Warehouse replenishment reserve',
                ],
            ],
            [
                [
                    'language_code' => 'lt',
                    'name'          => 'Rezervuota',
                    'description'   => 'Kai yra užtvirtinamas užsakymas (dar neapmokėtas, bet paruoštas mokėjimui)',
                ],
                [
                    'language_code' => 'en',
                    'name'          => 'Reserved',
                ],
            ],
        ];

        DB::beginTransaction();

        try {
            foreach ( $actions as $key => $action ) {

                $record = HCECStockHistoryActions::where('id', $action['id'])->first();

                if( is_null($record) ) {
                    $record = HCECStockHistoryActions::create($action);
                }

                foreach ( $translations[$key] as $translation ) {

                    $trans = HCECStockHistoryActionsTranslations::where([
                        'record_id'     => $record->id,
                        'language_code' => $translation['language_code'],
                    ])->first();

                    if( is_null($trans) ) {
                        HCECStockHistoryActionsTranslations::create($translation + [
                                'record_id' => $record->id,
                            ]);
                    }
                }
            }
        } catch ( \Exception $e ) {
            DB::rollback();

            throw new Exception($e->getMessage());
        }

        DB::commit();
    }
}
