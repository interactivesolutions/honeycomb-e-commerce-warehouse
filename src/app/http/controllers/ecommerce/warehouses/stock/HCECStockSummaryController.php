<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\http\controllers\ecommerce\warehouses\stock;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistoryActions;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockSummary;
use interactivesolutions\honeycombecommercewarehouse\app\services\HCStockService;
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
//            $config['actions'][] = 'restore';
        }

//        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_delete') )
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
            'good.translations.{lang}.label' => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.good_id'),
            ],
            'warehouse.name'                 => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.warehouse_id'),
            ],
            'ordered'                        => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.ordered'),
            ],
            'in_transit'                     => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.in_transit'),
            ],
            'on_sale'                        => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.on_sale'),
            ],
            'reserved'                       => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.reserved'),
            ],
            'ready_for_shipment'             => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.ready_for_shipment'),
            ],
            'total'                          => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.total'),
            ],
            'pre_ordered'                    => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.pre_ordered'),
            ],
            'content_url'                => [
                "type"  => "external-button",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.view_content'),
            ],
        ];
    }

    /**
     * Create item
     *
     * @return mixed
     * @throws \Exception
     */
    protected function __apiStore()
    {
        $data = $this->getInputData();

        $this->handleAction($data);

        return hcSuccess();
    }

    /**
     * Updates existing item based on ID
     *
     * @param $id
     * @return mixed
     */
    protected function __apiUpdate(string $id)
    {
        HCECStockSummary::findOrFail($id);

        $data = $this->getInputData();

        $this->handleAction($data);

        return hcSuccess();
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
        $with = ['good.translations', 'warehouse'];

        if( $select == null )
            $select = HCECStockSummary::getFillableFields();

        HCECStockSummary::$customAppends = ['content_url'];

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
                ->orWhereHas('warehouse', function($query) use ($phrase) {
                    $query->where('name', 'LIKE', '%' . $phrase . '%');
                })
                ->orWhereHas('good.translations', function ($query) use ($phrase) {
                    $query->where('label', 'LIKE', '%' . $phrase . '%');
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
        (new HCECStockSummaryValidator())->validateForm();

        $_data = request()->all();

        if( array_has($_data, 'id') )
            array_set($data, 'record.id', array_get($_data, 'id'));

        array_set($data, 'record.good_id', array_get($_data, 'good_id'));
        array_set($data, 'record.combination_id', array_get($_data, 'combination_id'));
        array_set($data, 'record.warehouse_id', array_get($_data, 'warehouse_id'));

        array_set($data, 'history.good_id', array_get($_data, 'good_id'));
        array_set($data, 'history.combination_id', array_get($_data, 'combination_id'));
        array_set($data, 'history.warehouse_id', array_get($_data, 'warehouse_id'));
        array_set($data, 'history.action_id', array_get($_data, 'action_id'));
        array_set($data, 'history.user_id', $this->user()->id);
        array_set($data, 'history.amount', array_get($_data, 'amount'));
        array_set($data, 'history.prime_cost', array_get($_data, 'prime_cost'));
        array_set($data, 'history.comment', array_get($_data, 'comment'));

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

    /**
     * Handle action
     *
     * @param $data
     * @throws \Exception
     */
    protected function handleAction($data)
    {
        $stockService = new HCStockService();

        // get action
        $action = HCECStockHistoryActions::findOrFail($data['history']['action_id']);

        if( $action->id == 'warehouse-replenishment-for-sale' ) {

            $stockService->replenishmentForSale(
                $data['record']['good_id'],
                $data['record']['combination_id'],
                $data['history']['amount'],
                $data['record']['warehouse_id'],
                $data['history']['comment']
            );

        } else if( $action->id == 'warehouse-replenishment-reserve' ) {
            $stockService->replenishmentReserve(
                $data['record']['good_id'],
                $data['record']['combination_id'],
                $data['history']['amount'],
                $data['record']['warehouse_id'],
                $data['history']['comment']
            );

        } else if( $action->id == 'reserved' ) {

            $stockService->reserve(
                $data['record']['good_id'],
                $data['record']['combination_id'],
                $data['history']['amount'],
                $data['record']['warehouse_id'],
                $data['history']['comment']
            );

        } else if( $action->id == 'warehouse-remove-from-sale' ) {

            $stockService->removeOnSale(
                $data['record']['good_id'],
                $data['record']['combination_id'],
                $data['history']['amount'],
                $data['record']['warehouse_id'],
                $data['history']['comment']
            );

        } else if( $action->id == 'warehouse-remove-from-reserved' ) {

            $stockService->removeReserved(
                $data['record']['good_id'],
                $data['record']['combination_id'],
                $data['history']['amount'],
                $data['record']['warehouse_id'],
                $data['history']['comment']
            );
        }
    }
}
