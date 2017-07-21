<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\http\controllers\ecommerce;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\HCECWarehouses;
use interactivesolutions\honeycombecommercewarehouse\app\validators\ecommerce\HCECWarehousesValidator;

class HCECWarehousesController extends HCBaseController
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
            'title'       => trans('HCECommerceWarehouse::e_commerce_warehouses.page_title'),
            'listURL'     => route('admin.api.routes.e.commerce.warehouses'),
            'newFormUrl'  => route('admin.api.form-manager', ['e-commerce-warehouses-new']),
            'editFormUrl' => route('admin.api.form-manager', ['e-commerce-warehouses-edit']),
            'imagesUrl'   => route('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader(),
        ];

        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_create') )
            $config['actions'][] = 'new';

        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_update') ) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if( auth()->user()->can('interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_delete') )
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
            'reference'       => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses.reference'),
            ],
            'name'            => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses.name'),
            ],
            'management_type' => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses.management_type'),
            ],
            'currency'        => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses.currency'),
            ],
            'contacts'        => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses.contacts'),
            ],
            'address'         => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses.address'),
            ],
            'country_id'      => [
                "type"  => "text",
                "label" => trans('HCECommerceWarehouse::e_commerce_warehouses.country_id'),
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

        $record = HCECWarehouses::create(array_get($data, 'record'));
        $record->updateUsers(array_get($data, 'users', []));

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
        $record = HCECWarehouses::findOrFail($id);

        $data = $this->getInputData();

        $record->update(array_get($data, 'record', []));
        $record->updateUsers(array_get($data, 'users', []));

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
        HCECWarehouses::where('id', $id)->update(request()->all());

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
        HCECWarehouses::destroy($list);

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
        HCECWarehouses::onlyTrashed()->whereIn('id', $list)->forceDelete();

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
        HCECWarehouses::whereIn('id', $list)->restore();

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
            $select = HCECWarehouses::getFillableFields();

        $list = HCECWarehouses::with($with)->select($select)
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
            $query->where('reference', 'LIKE', '%' . $phrase . '%')
                ->orWhere('name', 'LIKE', '%' . $phrase . '%')
                ->orWhere('management_type', 'LIKE', '%' . $phrase . '%')
                ->orWhere('currency', 'LIKE', '%' . $phrase . '%')
                ->orWhere('contacts', 'LIKE', '%' . $phrase . '%')
                ->orWhere('address', 'LIKE', '%' . $phrase . '%')
                ->orWhere('country_id', 'LIKE', '%' . $phrase . '%');
        });
    }

    /**
     * Getting user data on POST call
     *
     * @return mixed
     */
    protected function getInputData()
    {
        (new HCECWarehousesValidator())->validateForm();

        $_data = request()->all();

        if( array_has($_data, 'id') )
            array_set($data, 'record.id', array_get($_data, 'id'));

        array_set($data, 'record.reference', array_get($_data, 'reference'));
        array_set($data, 'record.name', array_get($_data, 'name'));
        array_set($data, 'record.management_type', array_get($_data, 'management_type'));
        array_set($data, 'record.currency', array_get($_data, 'currency'));
        array_set($data, 'record.contacts', array_get($_data, 'contacts'));
        array_set($data, 'record.address', array_get($_data, 'address'));
        array_set($data, 'record.country_id', array_get($_data, 'country_id'));

        array_set($data, 'users', array_get($_data, 'users'));

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
        $with = ['users'];

        $select = HCECWarehouses::getFillableFields();

        $record = HCECWarehouses::with($with)
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
