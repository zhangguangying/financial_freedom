<?php

namespace App\Admin\Controllers;

use App\Models\Account;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Show;
use Encore\Admin\Table;

class AccountController extends AdminController
{
    protected $title = '我的账户';

    protected $types = [
        1 => '应急资金',
        2 => '生活日用',
        3 => '投资账户'
    ];

    protected function table()
    {
        $table = new Table(new Account());

        $table->column('id', __('Id'));
        $table->column('name', __('账户名称'));
        $table->column('type', __('账户类型'))->using($this->types);
        $table->column('value', __('金额'));

        return $table;
    }

    protected function form()
    {
        $form = new Form(new Account());

        $form->text('name', __('账户名称'));
        $form->select('type', __('账户类型'))->options($this->types);
        $form->decimal('value', __('金额'));

        return $form;
    }
}
