<?php
use App\User;
use SleepingOwl\Admin\Model\ModelConfiguration;
use Illuminate\Support\Facades\Input;

AdminSection::registerModel(User::class, function (ModelConfiguration $model) {
    $model->setTitle('Users');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('id')->setLabel('id'),
            AdminColumn::text('name')->setLabel('Username'),
            AdminColumn::text('admin')->setLabel('Admin'),
            AdminColumn::email('email')->setLabel('Email'),
            AdminColumn::datetime('created_at', 'Created')->setFormat('d.m.Y'),
            AdminColumn::datetime('updated_at', 'Updated')->setFormat('d.m.Y')
        ]);
        $display->paginate(15);
        return $display;
    });
    $model->onCreateAndEdit(function() {
        return $form = AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Name')->required(),
            AdminFormElement::text('email', 'E-mail')->required()->unique()->addValidationRule('email'),
            AdminFormElement::text('password', 'Password')->required()->addValidationRule('min:6'),
            /*TODO:add password bcrypt*/
            /*AdminFormElement::custom()->display(function ($instance) {
                return view('password', ['instance' => $instance]);
            })->callback(function ($instance)
            {
                $instance->password = Input::get('password');
            }),*/
            AdminFormElement::checkbox('admin', 'Admin')
        );

        return $form;
    });
});