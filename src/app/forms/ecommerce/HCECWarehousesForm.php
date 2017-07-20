<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\forms\ecommerce;

use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\HCECWarehouses;
use interactivesolutions\honeycombregions\app\models\regions\HCCountries;

class HCECWarehousesForm
{
    // name of the form
    protected $formID = 'e-commerce-warehouses';

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
            'storageURL' => route('admin.api.routes.e.commerce.warehouses'),
            'buttons'    => [
                [
                    "class" => "col-centered",
                    "label" => trans('HCTranslations::core.buttons.submit'),
                    "type"  => "submit",
                ],
            ],
            'structure'  => [
                [
                    "type"            => "singleLine",
                    "fieldID"         => "reference",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses.reference"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], [
                    "type"            => "singleLine",
                    "fieldID"         => "name",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses.name"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], 
                [
                    "type"            => "radioList",
                    "fieldID"         => "management_type",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses.management_type"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    'options'         => HCECWarehouses::getTableEnumList('management_type', 'label'),
                ],
                [
                    "type"            => "singleLine",
                    "fieldID"         => "currency",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses.currency"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], [
                    "type"            => "singleLine",
                    "fieldID"         => "contacts",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses.contacts"),
                    "required"        => 0,
                    "requiredVisible" => 0,
                ], [
                    "type"            => "singleLine",
                    "fieldID"         => "address",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses.address"),
                    "required"        => 0,
                    "requiredVisible" => 0,
                ],
                [
                    "type"            => "dropDownList",
                    "fieldID"         => "country_id",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses.country_id"),
                    "required"        => 0,
                    "requiredVisible" => 0,
                    "options"         => HCCountries::select('id', 'translation_key')->get(),
                    "search"          => [
                        "maximumSelectionLength" => 1,
                        "minimumSelectionLength" => 1,
                        "showNodes"              => ["translation"],
                    ],
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