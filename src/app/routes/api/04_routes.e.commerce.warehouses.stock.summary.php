<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::group(['prefix' => 'v1/e-commerce/warehouses/stock/summary'], function ()
    {
        Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiStore']);
        Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiDestroy']);

        Route::group(['prefix' => 'list'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.list', 'middleware' => ['acl-apps:api_v1_interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiList']);
            Route::get('{timestamp}', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiIndexSync']);
        });

        Route::post('restore', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.restore', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.merge', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_create', 'acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiMerge']);
        Route::delete('force', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.force', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiShow']);
            Route::put('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiDestroy']);

            Route::put('strict', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.update.strict', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.duplicate.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list', 'acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiDuplicate']);
            Route::delete('force', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.force.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiForceDelete']);
        });
    });
});