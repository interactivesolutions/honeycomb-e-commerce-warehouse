<?php

Route::group(['prefix' => config('hc.admin_url'), 'middleware' => ['web', 'auth']], function ()
{
    Route::get('e-commerce/warehouses/stock/summary', ['as' => 'admin.routes.e.commerce.warehouses.stock.summary.index', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@adminIndex']);

    Route::group(['prefix' => 'api/e-commerce/warehouses/stock/summary'], function ()
    {
        Route::get('/', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.summary', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiStore']);
        Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiDestroy']);

        Route::get('list', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.summary.list', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiIndex']);
        Route::post('restore', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.summary.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.summary.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_create', 'acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiMerge']);
        Route::delete('force', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.summary.force', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.summary.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiShow']);
            Route::put('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiDestroy']);

            Route::put('strict', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.summary.update.strict', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.summary.duplicate.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list', 'acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiDuplicate']);
            Route::delete('force', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.summary.force.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockSummaryController@apiForceDelete']);
        });
    });
});
