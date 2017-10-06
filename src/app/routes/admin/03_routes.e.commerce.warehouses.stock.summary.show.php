<?php

Route::group(['prefix' => config('hc.admin_url'), 'middleware' => ['web', 'auth']], function () {
    Route::get('e-commerce/warehouses/stock/summary/{_id}', [
            'as'         => 'admin.routes.e.commerce.warehouses.stock.summary.{_id}.index',
            'middleware' => ['acl:interactivesolutions_honeycomb_e_commerce_warehouse_routes_e_commerce_warehouses_stock_summary_list'],
            'uses'       => 'ecommerce\\warehouses\\stock\\HCECStockSummaryShowController@index']
    );
});