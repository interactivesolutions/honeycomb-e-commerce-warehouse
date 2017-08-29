<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\services;

use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistory;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockSummary;

class HCStockService
{
    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param null $warehouseId
     * @throws \Exception
     */
    public function reserve($goodId, $combinationId, $amount, $warehouseId)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        $onSale = $stock->on_sale - $amount;

        // Kai yra užtvirtinamas užsakymas (dar neapmokėtas, bet paruoštas mokėjimui). iš on_sale permetam į reserved lauką kiekį.
        if( is_null($stock) ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_reserve'));
        }

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
     */
    public function replenishmentReserve($goodId, $combinationId, $amount, $warehouseId)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        // Kai prekės atvežamos į sandelį ir dedamos į rezervaciją.
        if( is_null($stock) ) {
            $stock = HCECStockSummary::create([
                'good_id'            => $goodId,
                'warehouse_id'       => $warehouseId,
                'ordered'            => 0,
                'in_transit'         => 0,
                'on_sale'            => 0,
                'reserved'           => $amount,
                'ready_for_shipment' => 0,
                'total'              => $amount,
            ]);
        } else {
            $stock->reserved = $stock->reserved + $amount;
            $stock->total = $stock->total + $amount;
            $stock->save();
        }

        // log history
        $this->logHistory('warehouse-replenishment-reserve', $stock, $amount);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     */
    public function replenishmentForSale($goodId, $combinationId, $amount, $warehouseId)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        if( is_null($stock) ) {
            $stock = HCECStockSummary::create([
                'good_id'            => $goodId,
                'warehouse_id'       => $warehouseId,
                'ordered'            => 0,
                'in_transit'         => 0,
                'on_sale'            => $amount,
                'reserved'           => 0,
                'ready_for_shipment' => 0,
                'total'              => $amount,
            ]);
        } else {
            $stock->on_sale = $stock->on_sale + $amount;
            $stock->total = $stock->total + $amount;
            $stock->save();
        }

        // log history
        $this->logHistory('warehouse-replenishment-for-sale', $stock, $amount);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $warehouseId
     * @return array
     * @throws \Exception
     */
    protected function getStockSummary($goodId, $combinationId, $warehouseId)
    {
        return HCECStockSummary::where([
            'good_id'        => $goodId,
            'combination_id' => $combinationId,
            'warehouse_id'   => $warehouseId,
        ])->first();
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