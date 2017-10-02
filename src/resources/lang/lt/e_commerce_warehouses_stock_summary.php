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
    'pre_ordered'        => 'Nupirkta į minusą',

    'errors' => [
        'cant_reserve'                   => 'Nepakankamas prekių likutis sandėlyje.',
        'not_enough_items_to_reserve'    => 'Nepakankamas prekių likutis sandėlyje. Negalite rezervuoti :r iš :left prekių.',
        'cant_remove_on_sale'            => 'Negalite minusuoti parduodamų prekių iš sandėlio. Galimas prekių kiekis minusavimui yra :count.',
        'cant_remove_reserved'           => 'Negalite minusuoti rezervuotų prekių iš sandėlio. Galimas prekių kiekis minusavimui yra :count.',
        'cant_cancel_reserved'           => 'Negalite atšaukti rezervuotų prekių iš sandėlio. Galimas prekių kiekis atšaukimui yra :count.',
        'cant_cancel_pre_ordered'        => 'Negalite atšaukti į minusą nupikrtų prekių iš sandėlio. Galimas į minusą nupirktų prekių kiekis atšaukimui yra :count.',
        'cant_move_for_shipment'         => 'Negalite perkelti į "paruošta siuntimui". Galimas prekių kiekis perkėlimui į siuntimą yra :count.',
        'cant_remove_ready_for_shipment' => 'Negalite minusuoti "paruošta siuntimui" prekių. Galimas prekių kiekis minusavimui iš "paruošta siuntimui" yra :count.',
    ],
];