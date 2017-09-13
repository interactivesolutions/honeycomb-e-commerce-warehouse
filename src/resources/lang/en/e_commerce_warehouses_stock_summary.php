<?php
return [
    'page_title'         => 'Stock management',
    'good_id'            => 'Good',
    'warehouse_id'       => 'Warehouse',
    'ordered'            => 'Ordered',
    'in_transit'         => 'In transit',
    'on_sale'            => 'On sale',
    'reserved'           => 'Reserved',
    'ready_for_shipment' => 'Ready for shipment',
    'total'              => 'Total',

    'errors' => [
        'cant_reserve'                   => 'Can\'t reserve items. Available "on sale" items count is zero.',
        'not_enough_items_to_reserve'    => 'You don\'t have enough on sale items. Cannot reserve :r items of :left.',
        'cant_remove_on_sale'            => 'Can\'t remove "on sale" items. Available to remove "on sale" items count is :count.',
        'cant_remove_reserved'           => 'Can\'t remove "reserved". Available to remove "reserved" items count is :count.',
        'cant_move_for_shipment'         => 'Can\'t move to "ready-for-shipment". Available move to "ready-for-shipment" items count is :count.',
        'cant_remove_ready_for_shipment' => 'Can\'t remove "ready-for-shipment". Available to remove "ready-for-shipment" items count is :count.',
    ],
];