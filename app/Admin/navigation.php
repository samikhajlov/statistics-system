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
            (new Page(\App\Model\BrowserStats::class))
                ->setIcon('fa fa-circle-o')
                ->setPriority(0),
            (new Page(\App\Model\OSStats::class))
                ->setIcon('fa fa-circle-o')
                ->setPriority(0),
            (new Page(\App\Model\LocationStats::class))
                ->setIcon('fa fa-circle-o')
                ->setPriority(0),
            (new Page(\App\Model\HostStats::class))
                ->setIcon('fa fa-circle-o')
                ->setPriority(0),
        ],
    ]
];
