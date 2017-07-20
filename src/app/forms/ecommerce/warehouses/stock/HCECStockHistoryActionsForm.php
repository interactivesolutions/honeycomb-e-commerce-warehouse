<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\forms\ecommerce\warehouses\stock;

class HCECStockHistoryActionsForm
{
    // name of the form
    protected $formID = 'e-commerce-warehouses-stock-history-actions';

    // is form multi language
    protected $multiLanguage = 1;

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
    "type"            => "singleLine",
    "fieldID"         => "translations.name",
    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history_actions.name"),
    "required"        => 1,
    "requiredVisible" => 1,
    "tabID"           => trans('HCTranslations::core.translations'),
    "multiLanguage"   => 1,
],[
    "type"            => "singleLine",
    "fieldID"         => "translations.description",
    "label"           => trans("HCECommerceWarehouse::e_commerce_warehouses_stock_history_actions.description"),
    "required"        => 0,
    "requiredVisible" => 0,
    "tabID"           => trans('HCTranslations::core.translations'),
    "multiLanguage"   => 1,
],
            ],
        ];

        if ($this->multiLanguage)
            $form['availableLanguages'] = getHCContentLanguages();

        if (!$edit)
            return $form;

        //Make changes to edit form if needed
        // $form['structure'][] = [];

        return $form;
    }
}