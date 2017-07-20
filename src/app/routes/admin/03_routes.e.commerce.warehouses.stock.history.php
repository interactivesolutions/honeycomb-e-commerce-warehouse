<?php

Route::group(['prefix' => config('hc.admin_url'), 'middleware' => ['web', 'auth']], function ()
{
    Route::get('e-commerce/warehouses/stock/history', ['as' => 'admin.routes.e.commerce.warehouses.stock.history.index', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@adminIndex']);

    Route::group(['prefix' => 'api/e-commerce/warehouses/stock/history'], function ()
    {
        Route::get('/', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiStore']);
        Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiDestroy']);

        Route::get('list', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.list', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiIndex']);
        Route::post('restore', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_create', 'acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiMerge']);
        Route::delete('force', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.force', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiShow']);
            Route::put('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiDestroy']);

            Route::put('strict', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.update.strict', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.duplicate.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_list', 'acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiDuplicate']);
            Route::delete('force', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.force.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryController@apiForceDelete']);
        });
    });
});
