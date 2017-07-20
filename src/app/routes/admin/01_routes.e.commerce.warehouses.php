<?php

Route::group(['prefix' => config('hc.admin_url'), 'middleware' => ['web', 'auth']], function ()
{
    Route::get('e-commerce/warehouses', ['as' => 'admin.routes.e.commerce.warehouses.index', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list'], 'uses' => 'ecommerce\\HCECWarehousesController@adminIndex']);

    Route::group(['prefix' => 'api/e-commerce/warehouses'], function ()
    {
        Route::get('/', ['as' => 'admin.api.routes.e.commerce.warehouses', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list'], 'uses' => 'ecommerce\\HCECWarehousesController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_create'], 'uses' => 'ecommerce\\HCECWarehousesController@apiStore']);
        Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiDestroy']);

        Route::get('list', ['as' => 'admin.api.routes.e.commerce.warehouses.list', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list'], 'uses' => 'ecommerce\\HCECWarehousesController@apiIndex']);
        Route::post('restore', ['as' => 'admin.api.routes.e.commerce.warehouses.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_update'], 'uses' => 'ecommerce\\HCECWarehousesController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.e.commerce.warehouses.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_create', 'acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiMerge']);
        Route::delete('force', ['as' => 'admin.api.routes.e.commerce.warehouses.force', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_force_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'admin.api.routes.e.commerce.warehouses.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list'], 'uses' => 'ecommerce\\HCECWarehousesController@apiShow']);
            Route::put('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_update'], 'uses' => 'ecommerce\\HCECWarehousesController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiDestroy']);

            Route::put('strict', ['as' => 'admin.api.routes.e.commerce.warehouses.update.strict', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_update'], 'uses' => 'ecommerce\\HCECWarehousesController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'admin.api.routes.e.commerce.warehouses.duplicate.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list', 'acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_create'], 'uses' => 'ecommerce\\HCECWarehousesController@apiDuplicate']);
            Route::delete('force', ['as' => 'admin.api.routes.e.commerce.warehouses.force.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_force_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiForceDelete']);
        });
    });
});
