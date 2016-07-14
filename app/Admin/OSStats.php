<?php
use App\Model\OSStats;
use SleepingOwl\Admin\Model\ModelConfiguration;


AdminSection::registerModel(OSStats::class, function (ModelConfiguration $model) {
    $model->setTitle('OS');
    $model->onDisplay(function () {
        $stats = new \App\Http\Controllers\StatsController();
        $osStats = $stats->mergeOSStats();
        return view('statsTable', ['stats' => $osStats, 'type' => 'OS']);
    });
});
