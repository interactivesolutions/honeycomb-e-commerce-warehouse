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
     * @param null $comment
     * @throws \Exception
     */
    public function reserve($goodId, $combinationId, $amount, $warehouseId, $comment = null)
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
        $this->logHistory('reserved', $stock, $amount, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     */
    public function replenishmentReserve($goodId, $combinationId, $amount, $warehouseId, $comment = null)
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
                'pre_ordered'        => 0,
            ]);
        } else {

            if( $stock->pre_ordered > 0 ) {

                if( $stock->pre_ordered >= $amount ) {
                    $stock->pre_ordered = $stock->pre_ordered - $amount;

                    // log history
                    $this->logHistory('remove-pre-ordered', $stock, $amount, $comment);
                } else {
                    $preOrderedToRemove = $stock->pre_ordered;
                    
                    $stock->pre_ordered = 0;
                    // log history
                    $this->logHistory('remove-pre-ordered', $stock, $preOrderedToRemove, $comment);
                }
            }

            $stock->reserved = $stock->reserved + $amount;
            $stock->total = $stock->total + $amount;
            $stock->save();
        }

        // log history
        $this->logHistory('warehouse-replenishment-reserve', $stock, $amount, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     */
    public function replenishmentForSale($goodId, $combinationId, $amount, $warehouseId, $comment = null)
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
                'pre_ordered'        => 0,
            ]);
        } else {

            if( $stock->pre_ordered > 0 ) {

                if( $stock->pre_ordered >= $amount ) {
                    $stock->pre_ordered = $stock->pre_ordered - $amount;
                    $stock->reserved = $stock->reserved + $amount;

                    // log history
                    $this->logHistory('remove-pre-ordered', $stock, $amount, $comment);
                } else {
                    // get amount which whill be for replenishment for sale
                    $availableForReplenishment = $amount - $stock->pre_ordered;
                    $preOrderedToRemove = $stock->pre_ordered;

                    // move from pre ordered to reserved
                    $stock->reserved = $stock->reserved + $preOrderedToRemove;
                    $stock->pre_ordered = 0;

                    $stock->on_sale = $stock->on_sale + $availableForReplenishment;

                    // log history
                    $this->logHistory('remove-pre-ordered', $stock, $preOrderedToRemove, $comment);
                }
            }

            $stock->total = $stock->total + $amount;
            $stock->save();
        }

        // log history
        $this->logHistory('warehouse-replenishment-for-sale', $stock, $amount, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     * @throws \Exception
     */
    public function removeOnSale($goodId, $combinationId, $amount, $warehouseId, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        if( is_null($stock) || $stock->on_sale == 0 ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_remove_on_sale', ['count' => 0]));
        }

        if( $stock->on_sale < $amount ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_remove_on_sale', ['count' => $stock->on_sale]));
        }

        $stock->on_sale = $stock->on_sale - $amount;
        $stock->total = $stock->total - $amount;
        $stock->save();

        // log history
        $this->logHistory('warehouse-remove-from-sale', $stock, $amount, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     * @throws \Exception
     */
    public function removeReserved($goodId, $combinationId, $amount, $warehouseId, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        if( is_null($stock) || $stock->reserved == 0 ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_remove_reserved', ['count' => 0]));
        }

        if( $stock->reserved < $amount ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_remove_reserved', ['count' => $stock->reserved]));
        }

        $stock->reserved = $stock->reserved - $amount;
        $stock->total = $stock->total - $amount;
        $stock->save();

        // log history
        $this->logHistory('warehouse-remove-from-reserved', $stock, $amount, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     * @throws \Exception
     */
    public function cancelReserved($goodId, $combinationId, $amount, $warehouseId, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        if( is_null($stock) || $stock->reserved == 0 ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_cancel_reserved', ['count' => 0]));
        }

        if( $stock->reserved < $amount ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_cancel_reserved', ['count' => $stock->reserved]));
        }

        $stock->reserved = $stock->reserved - $amount;
        $stock->on_sale = $stock->on_sale + $amount;
        $stock->save();

        // log history
        $this->logHistory('warehouse-cancel-reserved', $stock, $amount, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     * @throws \Exception
     */
    public function removeReadyForShipment($goodId, $combinationId, $amount, $warehouseId, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        if( is_null($stock) || $stock->ready_for_shipment == 0 ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_remove_ready_for_shipment', ['count' => 0]));
        }

        if( $stock->ready_for_shipment < $amount ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_remove_ready_for_shipment', ['count' => $stock->ready_for_shipment]));
        }

        $stock->ready_for_shipment = $stock->ready_for_shipment - $amount;
        $stock->total = $stock->total - $amount;
        $stock->save();

        // log history
        $this->logHistory('warehouse-remove-ready-for-shipment', $stock, $amount, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     * @throws \Exception
     */
    public function moveToReadyForShipment($goodId, $combinationId, $amount, $warehouseId, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        if( is_null($stock) || $stock->reserved == 0 ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_move_for_shipment', ['count' => 0]));
        }

        if( $stock->reserved < $amount ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_move_for_shipment', ['count' => $stock->reserved]));
        }

        $stock->reserved = $stock->reserved - $amount;
        $stock->ready_for_shipment = $stock->ready_for_shipment + $amount;
        $stock->save();

        // log history
        $this->logHistory('move-to-ready-for-shipment', $stock, $amount, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     * @throws \Exception
     */
    public function cancelReadyForShipment($goodId, $combinationId, $amount, $warehouseId, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        if( is_null($stock) || $stock->ready_for_shipment == 0 ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_cancel_ready_for_shipment', ['count' => 0]));
        }

        if( $stock->ready_for_shipment < $amount ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_cancel_ready_for_shipment', ['count' => $stock->ready_for_shipment]));
        }

        $stock->ready_for_shipment = $stock->ready_for_shipment - $amount;
        $stock->on_sale = $stock->on_sale + $amount;
        $stock->save();

        // log history
        $this->logHistory('cancel-ready-for-shipment', $stock, $amount, $comment);
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
     * @param $stock
     * @param $amount
     * @param $comment
     */
    protected function logHistory($actionId, $stock, $amount, $comment)
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
}