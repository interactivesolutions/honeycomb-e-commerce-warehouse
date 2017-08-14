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
        'cant_reserve'                => 'Can\'t reserve items. Available "on sale" items count is zero.',
        'not_enough_items_to_reserve' => 'You don\'t have enough on sale items. Cannot reserve :r items of :left.',
    ],
];