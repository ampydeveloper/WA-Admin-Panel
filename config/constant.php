<?php

return [
    'roles' => [
        'Admin' => 1,
        'Admin_Manager' => 2,
        'Driver' => 3,
        'Customer' => 4,
        'Customer_Manager' => 5,
        'haulers' => 6,
        'vehicle_service_provider' => 7
    ],
    'slot_type' => [
        'morning' => 1,
        'afternoon' => 2,
    ],
    'vehicle_type' => [
        'truck' => 1,
        'skidsteer' => 2
    ],
    'payment_methods' => [
        'stripe' => 1,
        'cheque' => 2,
        'cash' => 3
    ],
    'payment_status' => [
        'succeeded' => 'succeeded'
    ],
    'payment_status_reverse' => [
        'succeeded' => 1
    ],
    'job_status' => [
        'open' => 0,
        'close' => 1
    ],
    'repeating_job' => [
        'true' => 1,
        'false' => 0
    ],
    'payment_history' => [
        'pending' => 0,
        'complete' => 1
    ],
    'login_providers' => [
        'google' => 'google',
        'facebook' => 'facebook'
    ],
    'hubspot' => [
        'api_url' => 'https://api.hubapi.com/contacts/v1/contact?hapikey=',
        'api_key' => 'c6fd7eb1-da62-4717-9ceb-8c6516b1831f'
    ],
    'service_type' => [
        'by_round' => 1,
        'by_weight' => 2
    ],
];