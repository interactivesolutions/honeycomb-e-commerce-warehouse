<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\validators\ecommerce\warehouses\stock;

use interactivesolutions\honeycombcore\http\controllers\HCCoreFormValidator;

class HCECStockSummaryValidator extends HCCoreFormValidator
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'good_id'      => 'required|exists:hc_goods,id',
            'warehouse_id' => 'required|exists:hc_warehouses,id',
            'action_id'    => 'required|exists:hc_warehouses_stock_history_actions,id',
            'amount'       => 'required',
        ];
    }
}