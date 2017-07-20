<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::group(['prefix' => 'v1/e-commerce/warehouses/stock/history'], function ()
    {
        Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiStore']);
        Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiDestroy']);

        Route::group(['prefix' => 'list'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.list', 'middleware' => ['acl-apps:api_v1_interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiList']);
            Route::get('{timestamp}', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiIndexSync']);
        });

        Route::post('restore', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.restore', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.merge', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_create', 'acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiMerge']);
        Route::delete('force', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.force', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiShow']);
            Route::put('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiDestroy']);

            Route::put('strict', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.update.strict', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.duplicate.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list', 'acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiDuplicate']);
            Route::delete('force', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.force.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiForceDelete']);
        });
    });
});