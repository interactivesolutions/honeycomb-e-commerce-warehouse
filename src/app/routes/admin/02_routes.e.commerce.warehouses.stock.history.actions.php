<?php

Route::group(['prefix' => config('hc.admin_url'), 'middleware' => ['web', 'auth']], function ()
{
    Route::get('e-commerce/warehouses/stock/history-actions', ['as' => 'admin.routes.e.commerce.warehouses.stock.history.actions.index', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@adminIndex']);

    Route::group(['prefix' => 'api/e-commerce/warehouses/stock/history-actions'], function ()
    {
        Route::get('/', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.actions', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiStore']);
        Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiDestroy']);

        Route::get('list', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.actions.list', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiIndex']);
        Route::post('restore', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.actions.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_create', 'acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiMerge']);
        Route::delete('force', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.actions.force', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.actions.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiShow']);
            Route::put('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiDestroy']);

            Route::put('strict', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.actions.update.strict', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.actions.duplicate.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list', 'acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiDuplicate']);
            Route::delete('force', ['as' => 'admin.api.routes.e.commerce.warehouses.stock.history.actions.force.single', 'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiForceDelete']);
        });
    });
});
