<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\services;

use interactivesolutions\honeycombecommercegoods\app\models\ecommerce\HCECGoods;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistory;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockSummary;

class HCUserStockService
{
    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param null $warehouseId
     * @param null $comment
     * @return array
     * @throws \Exception
     */
    public function reserve($goodId, $combinationId, $amount, $warehouseId = null, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $amount, $warehouseId);

        // Kai yra užtvirtinamas užsakymas (dar neapmokėtas, bet paruoštas mokėjimui). iš on_sale permetam į reserved lauką kiekį.
        if( is_null($stock) ) {

            $error = trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_reserve');

            return $this->makePreOrder($goodId, $combinationId, $amount, $warehouseId, $comment, $error);
        }

        $onSale = $stock->on_sale - $amount;

        if( $onSale < 0 ) {

            $error = trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.not_enough_items_to_reserve', ['r' => $amount, 'left' => $stock->on_sale]);

            return $this->makePreOrder($goodId, $combinationId, $amount, $warehouseId, $comment, $error);
        }

        $stock->on_sale = $stock->on_sale - $amount;
        $stock->reserved = $stock->reserved + $amount;
        $stock->save();

        // log history
        $this->logHistory('reserved', $stock, $amount, $comment);

        return $stock;
    }

    /**
     * @param $good
     * @param $combinationId
     * @param $amount
     * @param null $warehouseId
     * @param null $comment
     * @return array
     * @throws \Exception
     */
    public function preOrder($good, $combinationId, $amount, $warehouseId = null, $comment = null)
    {
        // TODO check for multiple warehouses
        $stocks = HCECStockSummary::where([
            'good_id'        => $good->id,
            'combination_id' => $combinationId,
        ])->get();

        $onSale =  $available = $stocks->sum('on_sale');

        $availableToPreOrder = $good->pre_order_count - $stocks->sum('pre_ordered');

        if( $availableToPreOrder < 0 || $amount > $availableToPreOrder ) {
            throw new \Exception(trans('HCECommerceOrders::e_commerce_carts.errors.not_enough_to_pre_order', ['on_sale' => $onSale, 'pre_order' => $availableToPreOrder]));
        }

        if( is_null($warehouseId) ) {
            $stock = $stocks->first();
        } else {
            $stock = $stocks->where('warehouse_id', $warehouseId)->first();
        }

        if( is_null($stock) ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_reserve'));
        }

        $stock->pre_ordered = $stock->pre_ordered + $amount;
        $stock->save();

        // log history
        $this->logHistory('warehouse-pre-ordered', $stock, $amount, $comment);

        return $stock;
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
     * @param $stock
     * @param $amount
     * @param null $comment
     */
    protected function logHistory($actionId, $stock, $amount, $comment = null)
    {
        HCECStockHistory::create([
            'good_id'        => $stock->good_id,
            'combination_id' => $stock->combination_id,
            'warehouse_id'   => $stock->warehouse_id,
            'action_id'      => $actionId,
            'user_id'        => auth()->check() ? auth()->id() : null,
            'amount'         => $amount,
            'comment'        => $comment,
        ]);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param $comment
     * @param $errorText
     * @return HCECStockSummary
     * @throws \Exception
     */
    protected function makePreOrder($goodId, $combinationId, $amount, $warehouseId, $comment, $errorText): HCECStockSummary
    {
        $good = HCECGoods::find($goodId);

        if( is_null($good) || ! $good->allow_pre_order ) {
            throw new \Exception($errorText);
        }

        return $this->preOrder($good, $combinationId, $amount, $warehouseId, $comment);
    }
}