<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCECWarehouses extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_warehouses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'reference', 'name', 'management_type', 'currency', 'contacts', 'address', 'country_id'];
}