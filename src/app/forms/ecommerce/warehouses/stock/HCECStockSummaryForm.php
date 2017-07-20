<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\forms\ecommerce\warehouses\stock;

use interactivesolutions\honeycombecommercegoods\app\models\ecommerce\HCECGoods;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\HCECWarehouses;

class HCECStockSummaryForm
{
    // name of the form
    protected $formID = 'e-commerce-warehouses-stock-summary';

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
            'storageURL' => route('admin.api.routes.e.commerce.warehouses.stock.summary'),
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
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_summary.good_id"),
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
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_summary.warehouse_id"),
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
                    "type"            => "singleLine",
                    "fieldID"         => "ordered",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_summary.ordered"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], [
                    "type"            => "singleLine",
                    "fieldID"         => "in_transit",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_summary.in_transit"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], [
                    "type"            => "singleLine",
                    "fieldID"         => "on_sale",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_summary.on_sale"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], [
                    "type"            => "singleLine",
                    "fieldID"         => "reserved",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_summary.reserved"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], [
                    "type"            => "singleLine",
                    "fieldID"         => "ready_for_shipment",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_summary.ready_for_shipment"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], [
                    "type"            => "singleLine",
                    "fieldID"         => "total",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_summary.total"),
                    "required"        => 1,
                    "requiredVisible" => 1,
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