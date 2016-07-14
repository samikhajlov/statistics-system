<?php
use App\Model\HostStats;
use SleepingOwl\Admin\Model\ModelConfiguration;


AdminSection::registerModel(HostStats::class, function (ModelConfiguration $model) {
    $model->setTitle('Hosts');
    $model->onDisplay(function () {
        $stats = new \App\Http\Controllers\StatsController();
        $hostStats = $stats->mergeHostStats();
        return view('statsTable', ['stats' => $hostStats, 'type' => 'Hosts']);
    });
});
