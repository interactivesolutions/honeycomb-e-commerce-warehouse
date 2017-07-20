<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCECStockSummary extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_warehouses_stock_summary';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'good_id', 'warehouse_id', 'ordered', 'in_transit', 'on_sale', 'reserved', 'ready_for_shipment', 'total'];
}