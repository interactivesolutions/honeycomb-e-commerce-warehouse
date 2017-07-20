<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::group(['prefix' => 'v1/e-commerce/warehouses/stock/history-actions'], function ()
    {
        Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiStore']);
        Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiDestroy']);

        Route::group(['prefix' => 'list'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.list', 'middleware' => ['acl-apps:api_v1_interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiList']);
            Route::get('{timestamp}', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiIndexSync']);
        });

        Route::post('restore', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.restore', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.merge', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_create', 'acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiMerge']);
        Route::delete('force', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.force', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiShow']);
            Route::put('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiDestroy']);

            Route::put('strict', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.update.strict', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_update'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.duplicate.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_list', 'acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_create'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiDuplicate']);
            Route::delete('force', ['as' => 'api.v1.routes.e.commerce.warehouses.stock.history.actions.force.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_history_actions_force_delete'], 'uses' => 'ecommerce\\warehouses\\stock\\HCECStockHistoryActionsController@apiForceDelete']);
        });
    });
});