<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\http\controllers\ecommerce\warehouses\stock;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistoryActions;
use interactivesolutions\honeycombecommercewarehouse\app\validators\ecommerce\warehouses\stock\HCECStockHistoryActionsValidator;

class HCECStockHistoryActionsController extends HCBaseController
{
    /**
     * Returning configured admin view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminIndex()
    {
        $config = [
            'title'       => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history_actions.page_title'),
            'listURL'     => route('admin.api.routes.e.commerce.warehouses.stock.history.actions'),
            'newFormUrl'  => route('admin.api.form-manager', ['e-commerce-warehouses-stock-history-actions-new']),
            'editFormUrl' => route('admin.api.form-manager', ['e-commerce-warehouses-stock-history-actions-edit']),
            'imagesUrl'   => route('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader(),
        ];

//        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_create') )
//            $config['actions'][] = 'new';

//        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_update') ) {
//            $config['actions'][] = 'update';
//            $config['actions'][] = 'restore';
//        }

//        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_delete') )
//            $config['actions'][] = 'delete';

        $config['actions'][] = 'search';
        $config['filters'] = $this->getFilters();

        return hcview('HCCoreUI::admin.content.list', ['config' => $config]);
    }

    /**
     * Creating Admin List Header based on Main Table
     *
     * @return array
     */
    private function getAdminListHeader()
    {
        return [
            'title' => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history_actions.title'),
            ],
            'sign'  => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history_actions.sign'),
            ],
        ];
    }

    /**
     * Create item
     *
     * @return mixed
     */
    protected function __apiStore()
    {
        $data = $this->getInputData();

        $record = HCECStockHistoryActions::create(array_get($data, 'record', []));

        return $this->apiShow($record->id);
    }

    /**
     * Updates existing item based on ID
     *
     * @param $id
     * @return mixed
     */
    protected function __apiUpdate(string $id)
    {
        $record = HCECStockHistoryActions::findOrFail($id);

        $data = $this->getInputData();

        $record->update(array_get($data, 'record', []));

        return $this->apiShow($record->id);
    }

    /**
     * Updates existing specific items based on ID
     *
     * @param string $id
     * @return mixed
     */
    protected function __apiUpdateStrict(string $id)
    {
        HCECStockHistoryActions::where('id', $id)->update(request()->all());

        return $this->apiShow($id);
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed
     */
    protected function __apiDestroy(array $list)
    {
        HCECStockHistoryActions::destroy($list);

        return hcSuccess();
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed
     */
    protected function __apiForceDelete(array $list)
    {
        HCECStockHistoryActions::onlyTrashed()->whereIn('id', $list)->forceDelete();

        return hcSuccess();
    }

    /**
     * Restore multiple records
     *
     * @param $list
     * @return mixed
     */
    protected function __apiRestore(array $list)
    {
        HCECStockHistoryActions::onlyTrashed()->whereIn('id', $list)->restore();

        return hcSuccess();
    }

    /**
     * Creating data query
     *
     * @param array $select
     * @return mixed
     */
    protected function createQuery(array $select = null)
    {
        $with = [];

        if( $select == null )
            $select = HCECStockHistoryActions::getFillableFields();

        $list = HCECStockHistoryActions::with($with)
            ->select($select)
            ->where(function ($query) use ($select) {
                $query = $this->getRequestParameters($query, $select);
            });

        // enabling check for deleted
        $list = $this->checkForDeleted($list);

        // add search items
        $list = $this->search($list);

        // ordering data
        $list = $this->orderData($list, $select);

        return $list;
    }

    /**
     * List search elements
     * @param Builder $query
     * @param string $phrase
     * @return Builder
     */
    protected function searchQuery(Builder $query, string $phrase)
    {
        return $query->where(function (Builder $query) use ($phrase) {
            $query->where('id', 'LIKE', '%' . $phrase . '%');
        });
    }

    /**
     * Getting user data on POST call
     *
     * @return mixed
     */
    protected function getInputData()
    {
        (new HCECStockHistoryActionsValidator())->validateForm();

        $_data = request()->all();

        if( array_has($_data, 'id') )
            array_set($data, 'record.id', array_get($_data, 'id'));

        array_set($data, 'record.sign', array_get($_data, 'sign'));

        return makeEmptyNullable($data);
    }

    /**
     * Getting single record
     *
     * @param $id
     * @return mixed
     */
    public function apiShow(string $id)
    {
        $with = [];

        $select = HCECStockHistoryActions::getFillableFields(true);

        $record = HCECStockHistoryActions::with($with)
            ->select($select)
            ->where('id', $id)
            ->firstOrFail();

        return $record;
    }

    /**
     * Generating filters required for admin view
     *
     * @return array
     */
    public function getFilters()
    {
        $filters = [];

        return $filters;
    }
}
