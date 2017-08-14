<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\warehouses\stock;

use interactivesolutions\honeycombacl\app\models\HCUsers;
use interactivesolutions\honeycombcore\models\HCUuidModel;
use interactivesolutions\honeycombecommercegoods\app\models\ecommerce\goods\combinations\HCECCombinations;
use interactivesolutions\honeycombecommercegoods\app\models\ecommerce\HCECGoods;
use interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce\HCECWarehouses;

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
    protected $fillable = ['id', 'good_id', 'combination_id', 'warehouse_id', 'action_id', 'user_id', 'amount', 'prime_cost'];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function action()
    {
        return $this->belongsTo(HCECStockHistoryActions::class, 'action_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(HCUsers::class, 'user_id', 'id');
    }
}