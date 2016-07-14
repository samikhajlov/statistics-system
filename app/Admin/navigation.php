<?php

use SleepingOwl\Admin\Navigation\Page;


return [
    [
        'title' => 'Permissions',
        'icon' => 'fa fa-group',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0),
        ]
    ],
    [
        'title' => 'Statistic System',
        'icon' => 'fa fa-pie-chart',
        'pages' => [
            (new Page(\App\Model\SystemStats::class))
                ->setIcon('fa fa-circle-o')
                ->setPriority(0),
        ]
    ]
];
