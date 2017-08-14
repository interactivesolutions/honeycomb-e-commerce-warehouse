<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\http\controllers\ecommerce\warehouses\stock;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistory;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock\HCECStockHistoryActions;
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
        array_set($data, 'record.combination_id', array_get($_data, 'combination_id'));
        array_set($data, 'record.warehouse_id', array_get($_data, 'warehouse_id'));

        array_set($data, 'history.good_id', array_get($_data, 'good_id'));
        array_set($data, 'history.combination_id', array_get($_data, 'combination_id'));
        array_set($data, 'history.warehouse_id', array_get($_data, 'warehouse_id'));
        array_set($data, 'history.action_id', array_get($_data, 'action_id'));
        array_set($data, 'history.user_id', $this->user()->id);
        array_set($data, 'history.amount', array_get($_data, 'amount'));
        array_set($data, 'history.prime_cost', array_get($_data, 'prime_cost'));
        
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
        // get action
        $action = HCECStockHistoryActions::findOrFail($data['history']['action_id']);

        // log history
        HCECStockHistory::create($data['history']);

        // get summary
        $summary = HCECStockSummary::where([
            'good_id'        => $data['record']['good_id'],
            'combination_id' => $data['record']['combination_id'],
            'warehouse_id'   => $data['record']['warehouse_id'],
        ])->first();

        if( $action->id == 'warehouse-replenishment-for-sale' ) {
//            Kai prekės atvežamos į sandelį ir iš karto dedamos į pardavimą, sandelio atžvilgiu. Dar bus kiekvienos prekės nustatymuose bus koks turi būti mažiausias kieks sandelyje ir tik virš jo pardavinėti.
            if( is_null($summary) ) {
                $stock = HCECStockSummary::create([
                    'good_id'            => $data['record']['good_id'],
                    'warehouse_id'       => $data['record']['warehouse_id'],
                    'ordered'            => 0,
                    'in_transit'         => 0,
                    'on_sale'            => $data['history']['amount'],
                    'reserved'           => 0,
                    'ready_for_shipment' => 0,
                    'total'              => $data['history']['amount'],
                ]);
            } else {
                $summary->on_sale = $summary->on_sale + $data['history']['amount'];
                $summary->total = $summary->total + $data['history']['amount'];
                $summary->save();
            }

        } else if( $action->id == 'warehouse-replenishment-reserve' ) {
//            Kai prekės atvežamos į sandelį ir dedamos į rezervaciją.
            if( is_null($summary) ) {
                $stock = HCECStockSummary::create([
                    'good_id'            => $data['record']['good_id'],
                    'warehouse_id'       => $data['record']['warehouse_id'],
                    'ordered'            => 0,
                    'in_transit'         => 0,
                    'on_sale'            => 0,
                    'reserved'           => $data['history']['amount'],
                    'ready_for_shipment' => 0,
                    'total'              => $data['history']['amount'],
                ]);
            } else {
                $summary->reserved = $summary->reserved + $data['history']['amount'];
                $summary->total = $summary->total + $data['history']['amount'];
                $summary->save();
            }

        } else if( $action->id == 'reserved' ) {
//            Kai yra užtvirtinamas užsakymas (dar neapmokėtas, bet paruoštas mokėjimui). iš on_sale permetam į reserved lauką kiekį.
            if( is_null($summary) ) {
                throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.cant_reserve'));
            } else {
                $onSale = $summary->on_sale - $data['history']['amount'];

                if( $onSale < 0 ) {
                    throw new \Exception(trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.errors.not_enough_items_to_reserve', ['r' => $data['history']['amount'], 'left' => $summary->on_sale]));
                }

                $summary->on_sale = $summary->on_sale - $data['history']['amount'];
                $summary->reserved = $summary->reserved + $data['history']['amount'];
                $summary->save();
            }
        }
    }
}
