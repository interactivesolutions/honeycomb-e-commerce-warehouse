<?php namespace interactivesolutions\honeycombecommercewarehouse\app\validators\ecommerce;

use interactivesolutions\honeycombcore\http\controllers\HCCoreFormValidator;

class HCECWarehousesValidator extends HCCoreFormValidator
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'reference'       => 'required',
            'name'            => 'required',
            'management_type' => 'required',
            'currency'        => 'required',
            'country_id'      => 'required',

        ];
    }
}