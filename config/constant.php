<?php

return [
    'base_url' => 'http://wellington.leagueofclicks.com/uploads',
    'roles' => [
        'Admin' => 1,
        'Admin_Manager' => 2,
        'Driver' => 3,
        'Customer' => 4,
        'Customer_Manager' => 5,
        'Haulers' => 6,
        'Hauler_driver' => 7,
        'Mechanic' => 8
    ],
    'vehicle_type' => [
        'truck' => 1,
        'skidsteer' => 2
    ],
    'vehicle_status' => [
        'available' => 1,
        'unavailable' => 2
    ],
    'driver_status' => [
        'available' => 1,
        'unavailable' => 0
    ],
    'payment_methods' => [
        'stripe' => 1,
        'authorizenet' => 2
    ],
    'payment_status_reverse' => [
        'succeeded' => 1
    ],
    'repeating_job' => [
        'no' => 1,
        'yes' => 2
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
        'by_weight' => 1,
        'by_trip' => 2,
        'by_bucket' => 3
    ],
    'service_for' => [
        'customer' => 4,
        'haulers' => 6,
    ],
    'payment_mode' => [
        'online' => 0,
        'cheque' => 1,
        'cash' => 2
    ],
    'payment_mode_inverse' => [
        0 => 'online',
        1 => 'cheque',
        2 => 'cash',
    ],
    'job_status' => [
        'open' => 0,
        'assigned' => 1,
        'completed' => 2,
        'close' => 3,
        'cancelled' => 4,
    ],
    'payment_status' => [
        'unpaid' => 0,
        'paid' => 1,
    ],
    'quick_book' => [
        'Not_Sync' => 0,
        'Sync' => 1,
    ],
    'driver_type' => [
        'truck_driver' => 1,
        'skidsteer' => 2,
    ],
    'salary_type' => [
        'per_hour' => 1,
        'per_month' => 2,
    ],
    'time_slots' => [
        'total_time_slots' => 11
    ],
    'range_cover' => [
        'distance' => 4
    ],
    'service_slot_type' => [
        'morning' => 1,
        'afternoon' => 2,
        'evening' => 3
    ],
    'service_slot_type_inverse' => [
        1 => 'morning',
        2 => 'afternoon',
        3 => 'evening',
    ],
    'service_slot_type_timings' => [
        '09-12' => 1, //Morning, 9AM-12PM
        '12-15' => 2, //Afternoon, 12PM-3PM 
        '15-18' => 3 //Evening, 3PM-6PM
    ],
    'time_taken_to_complete_service' => [
        '15_mins' => 1,
        '30_mins' => 2,
        '45_mins' => 3,
        '60_mins' => 4,
        '75_mins' => 5,
        '90_mins' => 6,
    ],
    'time_taken_to_complete_service_reverse' => [
        1 =>'15',
        2 =>'30',
        3 =>'45',
        4 =>'60',
        5 =>'75',
        6 =>'90',
    ],
    'average_truck_speed' => 60,
    'zipcodes' => [
        0 => '62365',
        1 => '52869',
        2 => '12345',
        3 => '12234',
        4 => '78451',
        5 => '16006',
    ],
    'days' => [
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday',
    ],
    'warehouse' => [
        'lat' => '26.695145',
        'lon' => '-80.244859'
    ],
    'dumping_area' => [
        'lat' => '31.30',
        'lon' => '34.36'
    ],
    'repeating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
    'report_of' => [
        "this_week"     => 0,
        "this_month"    => 1,
        "last_month"    => 2,
        "this_year"     => 3,
        "last_year"     => 4,
        "last_twelve_month" => 5,
        "custom"        => 6,
        "last_week"        => 7,
        "last_two_week"        => 8,
    ],
    'inverse_report_of' => [
        0 =>"this_week",
        1 =>"this_month",
        2 =>"last_month",
        3 =>"this_year",
        4 =>"last_year",
        5 =>"last_twelve_month",
        6 =>"custom",
        7 =>"last_week",
        8 =>"last_two_week",
    ],
];
