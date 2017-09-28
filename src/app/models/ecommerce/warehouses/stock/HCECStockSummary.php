<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock;

use interactivesolutions\honeycombcore\models\HCUuidModel;
use interactivesolutions\honeycombecommercegoods\app\models\ecommerce\goods\combinations\HCECCombinations;
use interactivesolutions\honeycombecommercegoods\app\models\ecommerce\HCECGoods;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\HCECWarehouses;

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
    protected $fillable = ['id', 'good_id', 'combination_id', 'warehouse_id', 'ordered', 'in_transit', 'on_sale', 'reserved', 'ready_for_shipment', 'total', 'pre_ordered'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function good()
    {
        return $this->belongsTo(HCECGoods::class, 'good_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function combination()
    {
        return $this->belongsTo(HCECCombinations::class, 'combination_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(HCECWarehouses::class, 'warehouse_id', 'id');
    }

}