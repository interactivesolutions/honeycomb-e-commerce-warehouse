<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\services;

use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistory;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockSummary;

class HCUserStockService
{
    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param null $warehouseId
     * @throws \Exception
     */
    public function reserve($goodId, $combinationId, $amount, $warehouseId = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $amount, $warehouseId);

        // Kai yra užtvirtinamas užsakymas (dar neapmokėtas, bet paruoštas mokėjimui). iš on_sale permetam į reserved lauką kiekį.
        if( is_null($stock) ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_reserve'));
        }

        $onSale = $stock->on_sale - $amount;

        if( $onSale < 0 ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.not_enough_items_to_reserve', ['r' => $amount, 'left' => $stock->on_sale]));
        }

        $stock->on_sale = $stock->on_sale - $amount;
        $stock->reserved = $stock->reserved + $amount;
        $stock->save();

        // log history
        $this->logHistory('reserved', $stock, $amount);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @return array
     * @throws \Exception
     */
    protected function getStockSummary($goodId, $combinationId, $amount, $warehouseId)
    {
        $stock = null;

        if( is_null($warehouseId) ) {
            // get summary
            $stockSummaries = HCECStockSummary::where([
                'good_id'        => $goodId,
                'combination_id' => $combinationId,
            ])->get();

            if( $stockSummaries->isEmpty() ) {
                throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_reserve'));
            }

            foreach ( $stockSummaries as $stockSummary ) {
                $onSale = $stockSummary->on_sale - $amount;

                if( $onSale >= 0 ) {
                    $stock = $stockSummary;
                }

                break;
            }
        } else {
            // get summary
            $stockSummary = HCECStockSummary::where([
                'good_id'        => $goodId,
                'combination_id' => $combinationId,
                'warehouse_id'   => $warehouseId,
            ])->first();

            $onSale = $stockSummary->on_sale - $amount;

            if( $onSale >= 0 ) {
                $stock = $stockSummary;
            }
        }

        return $stock;
    }

    /**
     * Log to history
     *
     * @param $actionId
     * @param $amount
     * @param $stock
     */
    protected function logHistory($actionId, $stock, $amount)
    {
        HCECStockHistory::create([
            'good_id'        => $stock->good_id,
            'combination_id' => $stock->combination_id,
            'warehouse_id'   => $stock->warehouse_id,
            'action_id'      => $actionId,
            'user_id'        => auth()->check() ? auth()->id() : null,
            'amount'         => $amount,
        ]);
    }
}