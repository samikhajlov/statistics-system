<?php
use App\Model\SystemStats;
use SleepingOwl\Admin\Model\ModelConfiguration;


AdminSection::registerModel(SystemStats::class, function (ModelConfiguration $model) {
    $model->setTitle('Browser');
    // Display
    $model->onDisplay(function () {
        return view('statsTable');

//        $display = AdminDisplay::table()->setColumns([
//            AdminColumn::custom('id1')->setLabel('id')->setCallback(function ($instance) {
//                return $instance->password = Input::get('password');
//            }),
//        ]);
//        $display->paginate(15);
//        return $display;
    });
});

