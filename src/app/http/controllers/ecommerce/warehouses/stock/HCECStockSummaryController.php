<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\http\controllers\ecommerce\warehouses\stock;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockSummary;
use interactivesolutions\honeycombecommercewarehouse\app\validators\ecommerce\warehouses\stock\HCECStockSummaryValidator;

class HCECStockSummaryController extends HCBaseController
{

    //TODO recordsPerPage setting

    /**
     * Returning configured admin view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminIndex()
    {
        $config = [
            'title'       => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.page_title'),
            'listURL'     => route('admin.api.routes.e.commerce.warehouses.stock.summary'),
            'newFormUrl'  => route('admin.api.form-manager', ['e-commerce-warehouses-stock-summary-new']),
            'editFormUrl' => route('admin.api.form-manager', ['e-commerce-warehouses-stock-summary-edit']),
            'imagesUrl'   => route('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader(),
        ];

        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_create') )
            $config['actions'][] = 'new';

        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_update') ) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_delete') )
            $config['actions'][] = 'delete';

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
            'good_id'            => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.good_id'),
            ],
            'warehouse_id'       => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.warehouse_id'),
            ],
            'ordered'            => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.ordered'),
            ],
            'in_transit'         => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.in_transit'),
            ],
            'on_sale'            => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.on_sale'),
            ],
            'reserved'           => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.reserved'),
            ],
            'ready_for_shipment' => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.ready_for_shipment'),
            ],
            'total'              => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.total'),
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

        $record = HCECStockSummary::create(array_get($data, 'record'));

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
        $record = HCECStockSummary::findOrFail($id);

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
        HCECStockSummary::where('id', $id)->update(request()->all());

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
        HCECStockSummary::destroy($list);

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
        HCECStockSummary::onlyTrashed()->whereIn('id', $list)->forceDelete();

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
        HCECStockSummary::whereIn('id', $list)->restore();

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
            $select = HCECStockSummary::getFillableFields();

        $list = HCECStockSummary::with($with)->select($select)
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
                ->orWhere('ordered', 'LIKE', '%' . $phrase . '%')
                ->orWhere('in_transit', 'LIKE', '%' . $phrase . '%')
                ->orWhere('on_sale', 'LIKE', '%' . $phrase . '%')
                ->orWhere('reserved', 'LIKE', '%' . $phrase . '%')
                ->orWhere('ready_for_shipment', 'LIKE', '%' . $phrase . '%')
                ->orWhere('total', 'LIKE', '%' . $phrase . '%');
        });
    }

    /**
     * Getting user data on POST call
     *
     * @return mixed
     */
    protected function getInputData()
    {
        (new HCECStockSummaryValidator())->validateForm();

        $_data = request()->all();

        if( array_has($_data, 'id') )
            array_set($data, 'record.id', array_get($_data, 'id'));

        array_set($data, 'record.good_id', array_get($_data, 'good_id'));
        array_set($data, 'record.warehouse_id', array_get($_data, 'warehouse_id'));
        array_set($data, 'record.ordered', array_get($_data, 'ordered'));
        array_set($data, 'record.in_transit', array_get($_data, 'in_transit'));
        array_set($data, 'record.on_sale', array_get($_data, 'on_sale'));
        array_set($data, 'record.reserved', array_get($_data, 'reserved'));
        array_set($data, 'record.ready_for_shipment', array_get($_data, 'ready_for_shipment'));
        array_set($data, 'record.total', array_get($_data, 'total'));

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

        $select = HCECStockSummary::getFillableFields();

        $record = HCECStockSummary::with($with)
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
