<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCECStockHistory extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_warehouses_stock_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'good_id', 'warehouse_id', 'action_id', 'user_id', 'amount', 'prime_cost'];
}