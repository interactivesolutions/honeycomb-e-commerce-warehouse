<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\forms\ecommerce\warehouses\stock;

use interactivesolutions\honeycombacl\app\models\HCUsers;
use interactivesolutions\honeycombecommercegoods\app\models\ecommerce\HCECGoods;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\HCECWarehouses;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistoryActions;

class HCECStockHistoryForm
{
    // name of the form
    protected $formID = 'e-commerce-warehouses-stock-history';

    // is form multi language
    protected $multiLanguage = 0;

    /**
     * Creating form
     *
     * @param bool $edit
     * @return array
     */
    public function createForm(bool $edit = false)
    {
        $form = [
            'storageURL' => route('admin.api.routes.e.commerce.warehouses.stock.history'),
            'buttons'    => [
                [
                    "class" => "col-centered",
                    "label" => trans('HCTranslations::core.buttons.submit'),
                    "type"  => "submit",
                ],
            ],
            'structure'  => [
                [
                    "type"            => "dropDownList",
                    "fieldID"         => "good_id",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history.good_id"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "options"         => HCECGoods::select('id')->with('translations')->get(),
                    "search"          => [
                        "maximumSelectionLength" => 1,
                        "minimumSelectionLength" => 1,
                        "showNodes"              => ["translations.{lang}.label"],
                    ],
                ],
                [
                    "type"            => "dropDownList",
                    "fieldID"         => "warehouse_id",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history.warehouse_id"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "options"         => HCECWarehouses::select('id', 'name')->get(),
                    "search"          => [
                        "maximumSelectionLength" => 1,
                        "minimumSelectionLength" => 1,
                        "showNodes"              => ["name"],
                    ],
                ],
                [
                    "type"            => "dropDownList",
                    "fieldID"         => "action_id",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history.action_id"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "options"         => HCECStockHistoryActions::select('id')->with('translations')->get(),
                    "search"          => [
                        "maximumSelectionLength" => 1,
                        "minimumSelectionLength" => 1,
                        "showNodes"              => ["translations.{lang}.name"],
                    ],
                ],
                [
                    "type"            => "dropDownList",
                    "fieldID"         => "user_id",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history.user_id"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "options"         => HCUsers::select('id', 'email')->get(),
                    "search"          => [
                        "maximumSelectionLength" => 1,
                        "minimumSelectionLength" => 1,
                        "showNodes"              => ["email"],
                    ],
                ],
                [
                    "type"            => "singleLine",
                    "fieldID"         => "amount",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history.amount"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ],
                [
                    "type"            => "singleLine",
                    "fieldID"         => "prime_cost",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history.prime_cost"),
                    "required"        => 0,
                    "requiredVisible" => 0,
                ],
            ],
        ];

        if( $this->multiLanguage )
            $form['availableLanguages'] = getHCContentLanguages();

        if( ! $edit )
            return $form;

        //Make changes to edit form if needed
        // $form['structure'][] = [];

        return $form;
    }
}