<?php
use App\Model\BrowserStats;
use SleepingOwl\Admin\Model\ModelConfiguration;


AdminSection::registerModel(BrowserStats::class, function (ModelConfiguration $model) {
    $model->setTitle('Browser');
    $model->onDisplay(function () {
        $stats = new \App\Http\Controllers\StatsController();
        $browserStats = $stats->mergeBrowserStats();
        return view('statsTable', ['stats' => $browserStats, 'type' => 'Browser']);
    });
});
