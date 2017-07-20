<?php namespace interactivesolutions\honeycombecommercewarehouse\app\validators\ecommerce\warehouses\stock;

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
            'good_id' => 'required',
'warehouse_id' => 'required',
'ordered' => 'required',
'in_transit' => 'required',
'on_sale' => 'required',
'reserved' => 'required',
'ready_for_shipment' => 'required',
'total' => 'required',

        ];
    }
}