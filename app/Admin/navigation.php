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
    ]
];
