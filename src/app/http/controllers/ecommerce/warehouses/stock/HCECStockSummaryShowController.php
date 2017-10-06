<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\http\controllers\ecommerce\warehouses\stock;

use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombecommerceorders\app\models\ecommerce\HCECOrders;
use interactivesolutions\honeycombecommerceorders\app\models\ecommerce\orders\HCECOrderDetails;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistory;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockSummary;

class HCECStockSummaryShowController extends HCBaseController
{
    /**
     * Returning configured admin view
     *
     * @param $summaryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($summaryId)
    {
        $summary = HCECStockSummary::findOrFail($summaryId);

        $summary->load(['warehouse', 'good.translations']);

        $history = HCECStockHistory::with(['action', 'user'])->where([
                'good_id'        => $summary->good_id,
                'combination_id' => $summary->combination_id,
            ])->latest()->get();

        $sold = HCECOrderDetails::whereHas('order', function ($query) {
            $query->where('order_payment_status_id', 'payment-accepted')->whereNotIn('order_state_id', ['canceled', 'canceled-and-restored']);
        })->where('good_id', $summary->good_id)
            ->where('combination_id', $summary->combination_id)
            ->sum('amount');

        $config = [
            'title'      => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.stock_good_info'),
            'summary'    => $summary,
            'history'    => $history,
            'goods_sold' => $sold,
        ];

        return hcview('HCECommerceWarehouse::stock.show', compact('config'));
    }

}
