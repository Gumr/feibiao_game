<?php

return [
    'signin' => [
        1 => ['key' => 'signin_one_day','default' => 2],
        2 => ['key' => 'signin_two_day', 'default' => 2],
        3 => ['key' => 'signin_three_day','default' => 2],
        4 => ['key' => 'signin_four_day','default' => 2],
        5 => ['key' => 'signin_five_day',  'default' => 2],
        6 => ['key' => 'signin_six_day',  'default' => 2],
        7 => ['key' => 'signin_seven_day',  'default' => 2],
    ],
    'bag' => [
        ['key' => 'ordinary_bag_start', 'default' => 0.01],
        ['key' => 'ordinary_bag_end', 'default' => 0.05],
        ['key' => 'bag_currency', 'default' => 5],
        ['key' => 'bag_step_number', 'default' => 500],
        ['key' => 'bag_switch', 'default' => 1]
    ],
    'ordinary' => [
        ['key' => 'invitation_currency_start', 'default' => 1],
        ['key' => 'invitation_currency_end', 'default' => 2],
        ['key' => 'invitation_effective_number', 'default' => 7],
        ['key' => 'step_currency', 'default' => 1000],
        ['key' => 'effective_step_currency', 'default' => 20000],
    ]
];