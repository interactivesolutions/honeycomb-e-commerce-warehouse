<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCECStockHistoryActions extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_warehouses_stock_history_actions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'sign'];

    /**
     * Appends title
     *
     * @var array
     */
    protected $appends = ['title'];

    /**
     * Title attribute
     *
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function getTitleAttribute()
    {
        return trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history_actions.actions.' . $this->id);
    }

    /**
     * Filter user visible stock actions
     *
     * @param $query
     * @return mixed
     */
    public function scopeUserVisible($query)
    {
        return $query->whereNotIn('id', [
            'move-to-ready-for-shipment', 'warehouse-remove-ready-for-shipment', 'cancel-ready-for-shipment', 'warehouse-cancel-reserved', 'warehouse-pre-ordered', 'remove-pre-ordered', 'warehouse-cancel-pre-ordered'
        ]);
    }
}
