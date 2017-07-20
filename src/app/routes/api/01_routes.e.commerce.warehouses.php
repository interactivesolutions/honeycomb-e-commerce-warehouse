<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::group(['prefix' => 'v1/e-commerce/warehouses'], function ()
    {
        Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list'], 'uses' => 'ecommerce\\HCECWarehousesController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_create'], 'uses' => 'ecommerce\\HCECWarehousesController@apiStore']);
        Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiDestroy']);

        Route::group(['prefix' => 'list'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.list', 'middleware' => ['acl-apps:api_v1_interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list'], 'uses' => 'ecommerce\\HCECWarehousesController@apiList']);
            Route::get('{timestamp}', ['as' => 'api.v1.routes.e.commerce.warehouses.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list'], 'uses' => 'ecommerce\\HCECWarehousesController@apiIndexSync']);
        });

        Route::post('restore', ['as' => 'api.v1.routes.e.commerce.warehouses.restore', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_update'], 'uses' => 'ecommerce\\HCECWarehousesController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.e.commerce.warehouses.merge', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_create', 'acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiMerge']);
        Route::delete('force', ['as' => 'api.v1.routes.e.commerce.warehouses.force', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_force_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list'], 'uses' => 'ecommerce\\HCECWarehousesController@apiShow']);
            Route::put('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_update'], 'uses' => 'ecommerce\\HCECWarehousesController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiDestroy']);

            Route::put('strict', ['as' => 'api.v1.routes.e.commerce.warehouses.update.strict', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_update'], 'uses' => 'ecommerce\\HCECWarehousesController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'api.v1.routes.e.commerce.warehouses.duplicate.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_list', 'acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_create'], 'uses' => 'ecommerce\\HCECWarehousesController@apiDuplicate']);
            Route::delete('force', ['as' => 'api.v1.routes.e.commerce.warehouses.force.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_force_delete'], 'uses' => 'ecommerce\\HCECWarehousesController@apiForceDelete']);
        });
    });
});