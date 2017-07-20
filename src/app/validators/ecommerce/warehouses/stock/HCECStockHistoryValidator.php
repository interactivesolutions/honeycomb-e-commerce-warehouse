<?php namespace interactivesolutions\honeycombecommercewarehouse\app\validators\ecommerce\warehouses\stock;

use interactivesolutions\honeycombcore\http\controllers\HCCoreFormValidator;

class HCECStockHistoryValidator extends HCCoreFormValidator
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
'action_id' => 'required',
'user_id' => 'required',
'amount' => 'required',

        ];
    }
}