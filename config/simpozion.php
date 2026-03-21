<?php

return [
    'event_name' => env('SIMPOZION_EVENT_NAME', 'Simpozionul'),
    'event_title' => env('SIMPOZION_EVENT_TITLE', 'Ziditori ai Spiritului Românesc'),
    'event_subtitle' => env('SIMPOZION_EVENT_SUBTITLE', 'Personalități istorice și culturale'),
    'event_location' => env('SIMPOZION_EVENT_LOCATION', 'Câmpulung Muscel'),
    'event_edition' => env('SIMPOZION_EVENT_EDITION', 'Ediția a XII-a'),
    'event_date' => env('SIMPOZION_EVENT_DATE', '23 mai 2026'),

    'prices' => [
        'friday_dinner' => 200,
        'symposium_lunch' => 200,
        'ball' => 350,
    ],

    'payment' => [
        'iban' => env('SIMPOZION_IBAN', 'RO49AAAA1B31007593840000'),
        'bank_name' => env('SIMPOZION_BANK_NAME', 'Banca Transilvania'),
        'account_holder' => env('SIMPOZION_ACCOUNT_HOLDER', 'Numele Titularului'),
        'revolut_link' => env('SIMPOZION_REVOLUT_LINK', 'https://revolut.me/example'),
    ],

    'whatsapp_group_link' => env('SIMPOZION_WHATSAPP_GROUP', ''),

    'max_event_count' => 20,
];
