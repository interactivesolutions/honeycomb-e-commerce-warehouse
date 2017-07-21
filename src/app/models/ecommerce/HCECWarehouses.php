<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\models\ecommerce;

use interactivesolutions\honeycombacl\app\models\HCUsers;
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

    /**
     * Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(HCUsers::class, HCECWarehousesUsers::getTableName(), 'warehouse_id', 'user_id');
    }

    /**
     * Sync users
     *
     * @param array $users
     */
    public function updateUsers(array $users)
    {
        $this->users()->sync($users);
    }
}