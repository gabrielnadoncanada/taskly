<?php

return [
    'item_status' => [
        'awaiting_receipt' => 'En attente de réception',
        'stored' => 'Stocké',
        'awaiting_shipment' => 'En attente d\'expédition',
        'shipped' => 'Expédié',
    ],
    'order_status' => [
        'new' => 'Nouveau',
        'processing' => 'En cours',
        'cancelled' => 'Annulé',
    ],
    'receipt_status' => [
        'new' => 'Nouveau',
        'processing' => 'En cours',
        'received' => 'Reçu',
        'cancelled' => 'Annulé',
    ],
    'shipment_status' => [
        'new' => 'Nouveau',
        'processing' => 'En cours',
        'shipped' => 'Expédié',
        'delivered' => 'Livré',
        'cancelled' => 'Annulé',
    ],
    'weight_units' => [
        'lb' => 'Livre',
        'kg' => 'Kilogramme',
    ],
    'dimension_units' => [
        'cm' => 'Centimètre',
        'inch' => 'Pouce',
    ],
    'language' => [
        'fr' => 'Français',
        'en' => 'Anglais',
    ],

    'measurement_system' => [
        'imperial' => 'Impérial',
        'metric' => 'Métrique',
    ],

    'currency' => [
        'usd' => 'Dollar américain (USD)',
        'cad' => 'Dollar canadien (CAD)',
    ],
    'asset_condition' => [
        'new' => 'Neuf',
        'used' => 'Usagé',
        'refurbished' => 'Reconditionné',
    ],
];
