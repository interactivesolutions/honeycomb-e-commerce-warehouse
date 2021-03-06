<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\services;

use interactivesolutions\honeycombecommerceorders\app\models\ecommerce\HCECOrders;
use interactivesolutions\honeycombecommerceorders\app\models\ecommerce\orders\HCECOrderDetails;
use interactivesolutions\honeycombecommerceorders\app\services\HCOrderService;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistory;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockSummary;

class HCStockService
{
    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param null $warehouseId
     * @param $primeCost
     * @param null $comment
     * @throws \Exception
     */
    public function reserve($goodId, $combinationId, $amount, $warehouseId, $primeCost, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

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
        $this->logHistory('reserved', $stock, $amount, $primeCost, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param $primeCost
     * @param null $comment
     */
    public function replenishmentReserve($goodId, $combinationId, $amount, $warehouseId, $primeCost, $comment = null)
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
            $stock->reserved = $stock->reserved + $amount;
            $stock->total = $stock->total + $amount;
            $stock->save();
        }

        // log history
        $this->logHistory('warehouse-replenishment-reserve', $stock, $amount, $primeCost, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param $primeCost
     * @param null $comment
     */
    public function replenishmentForSale($goodId, $combinationId, $amount, $warehouseId, $primeCost, $comment = null)
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
            $stock->on_sale = $stock->on_sale + $amount;
            $stock->total = $stock->total + $amount;
            $stock->save();
        }

        // log history
        $this->logHistory('warehouse-replenishment-for-sale', $stock, $amount, $primeCost, $comment);

        // handle pre ordered
        $this->handlePreOrdered($stock, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param $primeCost
     * @param null $comment
     * @throws \Exception
     */
    public function removeOnSale($goodId, $combinationId, $amount, $warehouseId, $primeCost, $comment = null)
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
        $this->logHistory('warehouse-remove-from-sale', $stock, $amount, $primeCost, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param $primeCost
     * @param null $comment
     * @throws \Exception
     */
    public function removeReserved($goodId, $combinationId, $amount, $warehouseId, $primeCost, $comment = null)
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
        $this->logHistory('warehouse-remove-from-reserved', $stock, $amount, $primeCost, $comment);
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
        $this->logHistory('warehouse-cancel-reserved', $stock, $amount, null, $comment);

        // handle pre ordered
        $this->handlePreOrdered($stock, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $amount
     * @param $warehouseId
     * @param null $comment
     * @throws \Exception
     */
    public function cancelPreOrdered($goodId, $combinationId, $amount, $warehouseId, $comment = null)
    {
        $stock = $this->getStockSummary($goodId, $combinationId, $warehouseId);

        if( is_null($stock) || $stock->pre_ordered == 0 ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_cancel_pre_ordered', ['count' => 0]));
        }

        if( $stock->pre_ordered < $amount ) {
            throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_cancel_pre_ordered', ['count' => $stock->reserved]));
        }

        $stock->pre_ordered = $stock->pre_ordered - $amount;
        $stock->save();

        // log history
        $this->logHistory('warehouse-cancel-pre-ordered', $stock, $amount, null, $comment);
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
        $this->logHistory('warehouse-remove-ready-for-shipment', $stock, $amount, null, $comment);
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
        $this->logHistory('move-to-ready-for-shipment', $stock, $amount, null, $comment);
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
        $this->logHistory('cancel-ready-for-shipment', $stock, $amount, null, $comment);

        // handle pre ordered
        $this->handlePreOrdered($stock, $comment);
    }

    /**
     * @param $goodId
     * @param $combinationId
     * @param $warehouseId
     * @return array
     * @throws \Exception
     */
    public function getStockSummary($goodId, $combinationId, $warehouseId)
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
     * @param $primeCost
     * @param $comment
     */
    protected function logHistory($actionId, $stock, $amount, $primeCost = null, $comment = null)
    {
        HCECStockHistory::create([
            'good_id'        => $stock->good_id,
            'combination_id' => $stock->combination_id,
            'warehouse_id'   => $stock->warehouse_id,
            'action_id'      => $actionId,
            'user_id'        => auth()->check() ? auth()->id() : null,
            'amount'         => $amount,
            'prime_cost'     => $primeCost,
            'comment'        => $comment,
        ]);
    }

    /**
     * @param $stock
     * @param $amount
     * @param $comment
     * @return mixed
     */
    public function removePreOrdered($stock, $amount, $comment)
    {
        $stock->on_sale = $stock->on_sale - $amount;
        $stock->pre_ordered = $stock->pre_ordered - $amount;
        $stock->reserved = $stock->reserved + $amount;
        $stock->save();

        // log history
        $this->logHistory('remove-pre-ordered', $stock, $amount, null, $comment);

        return $stock;
    }

    /**
     * @param $comment
     * @param $stock
     */
    protected function handlePreOrdered($stock, $comment)
    {
        if( $stock->pre_ordered <= 0 ) {
            return;
        }

        $orders = HCECOrders::with(['details' => function ($query) use ($stock) {
            $query->where([
                'good_id'        => $stock->good_id,
                'combination_id' => $stock->combination_id,
            ])->isPreOrdered();
        }])
            ->select('hc_orders.*', 'hc_order_history.created_at as payment_accepted_at')
            ->join('hc_order_history', function ($join) {
                $join->on('hc_order_history.order_id', '=', 'hc_orders.id')
                    ->where('hc_order_history.order_payment_status_id', 'payment-accepted');
            })
            ->where('hc_orders.order_payment_status_id', 'payment-accepted')
            ->where('hc_orders.order_state_id', 'waiting-for-stock')
            ->whereHas('details', function ($query) use ($stock) {
                $query->where([
                    'good_id'        => $stock->good_id,
                    'combination_id' => $stock->combination_id,
                ]);
            })
            ->orderBy('payment_accepted_at')
            ->get();

        if( $orders->isNotEmpty() ) {
            $orderService = new HCOrderService();

            foreach ( $orders as $key => $order ) {

                foreach ( $order->details as $detail ) {

                    if( $detail->amount && $detail->amount <= $stock->on_sale ) {
                        $stockComment = sprintf("'%s' pre ordered amount -%s ", $detail->name, $detail->amount);

                        // log history for removing pre_order
                        $stock = $this->removePreOrdered($stock, $detail->amount, $stockComment);

                        // change order detail status from pre ordered to normal
                        $detail->is_pre_ordered = '0';
                        $detail->save();
                    }
                }

                // check if order status can be change to ready for processing
                if( ! $order->details()->isPreOrdered()->count() ) {
                    $orderService->readyForProcessing($order, "Pre ordered goods arrived");
                }
            }
        }
    }

    /**
     * Reduce pre order
     *
     * @param $order
     * @return bool
     */
    public function reducePreOrdered($order)
    {
        $order->load(['details' => function ($query) {
            $query->isPreOrdered();
        }]);

        foreach ( $order->details as $detail ) {

            $stock = $this->getStockSummary($detail->good_id, $detail->combination_id, $detail->warehouse_id);

            if( $detail->amount && $detail->amount <= $stock->on_sale ) {
                $stockComment = sprintf("'%s' pre ordered amount -%s from order : %s", $detail->name, $detail->amount, $order->reference);

                // log history for removing pre_order
                $this->removePreOrdered($stock, $detail->amount, $stockComment);

                $detail->is_pre_ordered = '0';
                $detail->save();
            }
        }

        if( $order->details()->isPreOrdered()->exists() ) {
            return false;
        }

        return true;
    }
}