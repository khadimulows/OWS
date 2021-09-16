<?php

return [
    'API_DEBUG' => TRUE,
    'NOTIFICATIONS'  =>  [
        'REGISTER' => [
            'ENABLE' => TRUE,
            'SUBJECT' => 'Register.',
        ],
        'REQUEST_PASSWORD' => [
            'ENABLE' => TRUE,
            'SUBJECT' => 'Reset your password.',
        ],
        'RESET_PASSWORD' => [
            'ENABLE' => TRUE,
            'SUBJECT' => 'Your password succesfully reset.',
        ],
        'CHANGE_PASSWORD' => [
            'ENABLE' => TRUE,
            'SUBJECT' => 'Your password successfully change.',
        ],

    ]
];
