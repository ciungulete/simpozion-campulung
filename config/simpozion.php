<?php

return [
    'event_name' => [
        'ro' => env('SIMPOZION_EVENT_NAME', 'Simpozionul'),
        'en' => env('SIMPOZION_EVENT_NAME_EN', 'The Symposium'),
    ],
    'event_title' => [
        'ro' => env('SIMPOZION_EVENT_TITLE', 'Ziditori ai Spiritului Românesc'),
        'en' => env('SIMPOZION_EVENT_TITLE_EN', 'Builders of the Romanian Spirit'),
    ],
    'event_subtitle' => [
        'ro' => env('SIMPOZION_EVENT_SUBTITLE', 'Personalități istorice și culturale'),
        'en' => env('SIMPOZION_EVENT_SUBTITLE_EN', 'Historical and cultural personalities'),
    ],
    'event_location' => env('SIMPOZION_EVENT_LOCATION', 'Câmpulung Muscel'),
    'event_edition' => [
        'ro' => env('SIMPOZION_EVENT_EDITION', 'Ediția a XII-a'),
        'en' => env('SIMPOZION_EVENT_EDITION_EN', '12th Edition'),
    ],
    'event_date' => [
        'ro' => env('SIMPOZION_EVENT_DATE', '23 mai 2026'),
        'en' => env('SIMPOZION_EVENT_DATE_EN', 'May 23, 2026'),
    ],

    'events' => [
        'friday_dinner' => [
            'price' => 200,
            'datetime' => [
                'ro' => env('SIMPOZION_FRIDAY_DINNER_DATETIME', 'Vineri, 22 mai 2026, ora 19:00'),
                'en' => env('SIMPOZION_FRIDAY_DINNER_DATETIME_EN', 'Friday, May 22, 2026, 7:00 PM'),
            ],
        ],
        'symposium_lunch' => [
            'price' => 200,
            'datetime' => [
                'ro' => env('SIMPOZION_SYMPOSIUM_DATETIME', 'Sâmbătă, 23 mai 2026, ora 10:00'),
                'en' => env('SIMPOZION_SYMPOSIUM_DATETIME_EN', 'Saturday, May 23, 2026, 10:00 AM'),
            ],
        ],
        'ritual' => [
            'datetime' => [
                'ro' => env('SIMPOZION_RITUAL_DATETIME', 'Sâmbătă, 23 mai 2026, ora 16:00'),
                'en' => env('SIMPOZION_RITUAL_DATETIME_EN', 'Saturday, May 23, 2026, 4:00 PM'),
            ],
        ],
        'ball' => [
            'price' => 350,
            'datetime' => [
                'ro' => env('SIMPOZION_BALL_DATETIME', 'Sâmbătă, 23 mai 2026, ora 20:00'),
                'en' => env('SIMPOZION_BALL_DATETIME_EN', 'Saturday, May 23, 2026, 8:00 PM'),
            ],
        ],
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
