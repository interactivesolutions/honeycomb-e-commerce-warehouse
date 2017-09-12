<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\forms\ecommerce\warehouses\stock;

class HCECStockHistoryActionsForm
{
    // name of the form
    protected $formID = 'e-commerce-warehouses-stock-history-actions';

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
            'storageURL' => route('admin.api.routes.e.commerce.warehouses.stock.history.actions'),
            'buttons'    => [
                [
                    "class" => "col-centered",
                    "label" => trans('HCTranslations::core.buttons.submit'),
                    "type"  => "submit",
                ],
            ],
            'structure'  => [

                [
                    "type"            => "radioList",
                    "fieldID"         => "sign",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history_actions.sign"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "tabID"           => trans('HCTranslations::core.general'),
                    "options"         => [
                        [
                            'id'    => '-1',
                            'label' => trans('HCTranslations::core.decrease'),
                        ],
                        [
                            'id'    => '0',
                            'label' => trans('HCTranslations::core.neutral'),
                        ],
                        [
                            'id'    => '1',
                            'label' => trans('HCTranslations::core.increase'),
                        ],
                    ],
                ],
                [
                    "type"            => "singleLine",
                    "fieldID"         => "title",
                    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history_actions.title"),
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