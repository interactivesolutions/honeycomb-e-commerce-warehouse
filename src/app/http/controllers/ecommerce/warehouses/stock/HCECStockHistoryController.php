<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\http\controllers\ecommerce\warehouses\stock;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistory;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistoryActions;
use interactivesolutions\honeycombecommercewarehouse\app\validators\ecommerce\warehouses\stock\HCECStockHistoryValidator;

class HCECStockHistoryController extends HCBaseController
{
    /**
     * Returning configured admin view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminIndex()
    {
        $config = [
            'title'       => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.page_title'),
            'listURL'     => route('admin.api.routes.e.commerce.warehouses.stock.history'),
            'newFormUrl'  => route('admin.api.form-manager', ['e-commerce-warehouses-stock-history-new']),
            'editFormUrl' => route('admin.api.form-manager', ['e-commerce-warehouses-stock-history-edit']),
            'imagesUrl'   => route('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader(),
        ];

//        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_create') )
//            $config['actions'][] = 'new';
//
//        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_update') ) {
//            $config['actions'][] = 'update';
//            $config['actions'][] = 'restore';
//        }

//        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_delete') )
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
    public function getAdminListHeader()
    {
        return [
            'good.translations.{lang}.label'  => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.good_id'),
            ],
            'warehouse.name'                  => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.warehouse_id'),
            ],
            'action.translations.{lang}.name' => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.action_id'),
            ],
            'user.email'                      => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.user_id'),
            ],
            'amount'                          => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.amount'),
            ],
            'prime_cost'                      => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.prime_cost'),
            ],
            'comment'                         => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.comment'),
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

        $record = HCECStockHistory::create(array_get($data, 'record'));

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
        $record = HCECStockHistory::findOrFail($id);

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
        HCECStockHistory::where('id', $id)->update(request()->all());

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
        HCECStockHistory::destroy($list);

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
        HCECStockHistory::onlyTrashed()->whereIn('id', $list)->forceDelete();

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
        HCECStockHistory::whereIn('id', $list)->restore();

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
        $with = ['good.translations', 'warehouse', 'action.translations', 'user'];

        if( $select == null )
            $select = HCECStockHistory::getFillableFields();

        $list = HCECStockHistory::with($with)->select($select)
            // add filters
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
            $query->where('good_id', 'LIKE', '%' . $phrase . '%')
                ->orWhere('warehouse_id', 'LIKE', '%' . $phrase . '%')
                ->orWhere('action_id', 'LIKE', '%' . $phrase . '%')
                ->orWhere('user_id', 'LIKE', '%' . $phrase . '%')
                ->orWhere('amount', 'LIKE', '%' . $phrase . '%')
                ->orWhere('prime_cost', 'LIKE', '%' . $phrase . '%')
                ->orWhere('comment', 'LIKE', '%' . $phrase . '%')
                ->orWhereHas('good.translations', function ($query) use ($phrase) {
                    $query->where('label', 'LIKE', '%' . $phrase . '%');
                })->orWhereHas('user', function ($query) use ($phrase) {
                    $query->where('email', 'LIKE', '%' . $phrase . '%');
                })->orWhereHas('warehouse', function ($query) use ($phrase) {
                    $query->where('name', 'LIKE', '%' . $phrase . '%');
                });
        });
    }

    /**
     * Getting user data on POST call
     *
     * @return mixed
     */
    protected function getInputData()
    {
        (new HCECStockHistoryValidator())->validateForm();

        $_data = request()->all();

        if( array_has($_data, 'id') )
            array_set($data, 'record.id', array_get($_data, 'id'));

        array_set($data, 'record.good_id', array_get($_data, 'good_id'));
        array_set($data, 'record.warehouse_id', array_get($_data, 'warehouse_id'));
        array_set($data, 'record.action_id', array_get($_data, 'action_id'));
        array_set($data, 'record.user_id', array_get($_data, 'user_id'));
        array_set($data, 'record.amount', array_get($_data, 'amount'));
        array_set($data, 'record.prime_cost', array_get($_data, 'prime_cost'));
        array_set($data, 'record.comment', array_get($_data, 'comment'));

        return $data;
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

        $select = HCECStockHistory::getFillableFields();

        $record = HCECStockHistory::with($with)
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

        $types = [
            'fieldID'   => 'action_id',
            'type'      => 'dropDownList',
            'label'     => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.action_id'),
            'options'   => HCECStockHistoryActions::with('translations')->get()->toArray(),
            'showNodes' => ['translations.{lang}.name'],
        ];

        $filters[] = addAllOptionToDropDownList($types);

        return $filters;
    }
}
