<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCECWarehousesUsers extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_warehouses_users_conn';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'warehouse_id'];
}