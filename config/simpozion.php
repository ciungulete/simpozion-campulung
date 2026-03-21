<?php

return [
    'event_name' => env('SIMPOZION_EVENT_NAME', 'Simpozion'),

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

    'pdf_program_path' => env('SIMPOZION_PDF_PATH', 'program.pdf'),

    'max_event_count' => 20,
];
