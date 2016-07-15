<?php
use App\Model\LocationStats;
use SleepingOwl\Admin\Model\ModelConfiguration;


AdminSection::registerModel(LocationStats::class, function (ModelConfiguration $model) {
    $model->setTitle('Location');
    $model->onDisplay(function () {
        $stats = new \App\Http\Controllers\StatsController();
        $locationStats = $stats->mergeLocationStats();
        return view('statsTable', ['stats' => $locationStats['pages'], 'allstats' => $locationStats['all'],  'type' => 'Location']);
    });

});
