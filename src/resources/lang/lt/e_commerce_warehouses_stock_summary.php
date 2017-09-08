<?php
return [
    'page_title'         => 'Sandėlio valdymas',
    'good_id'            => 'Prekė',
    'warehouse_id'       => 'Sandėlys',
    'ordered'            => 'Užsakyta',
    'in_transit'         => 'Transportuojama',
    'on_sale'            => 'Pardavime',
    'reserved'           => 'Rezervuota',
    'ready_for_shipment' => 'Paruošta siuntimui',
    'total'              => 'Iš viso',

    'errors' => [
        'cant_reserve'                => 'Nepakankamas prekių likutis sandėlyje.',
        'not_enough_items_to_reserve' => 'Nepakankamas prekių likutis sandėlyje. Negalite rezervuoti :r iš :left prekių.',
        'cant_remove_on_sale'         => 'Negalite minusuoti parduodamų prekių iš sandėlio. Galimas prekių kiekis minusavimui yra :count.',
        'cant_remove_reserved'        => 'Negalite minusuoti rezervuotų prekių iš sandėlio. Galimas prekių kiekis minusavimui yra :count.',
    ],
];