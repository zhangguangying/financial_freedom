<?php

namespace App\Admin\Controllers;

use App\Models\Fund;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Show;
use Encore\Admin\Table;

class FundController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Fund';

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $table = new Table(new Fund());

        $table->column('id', __('Id'));
        $table->column('code', __('代码'));
        $table->column('name', __('名称'));
        $table->column('create_time', __('创建时间'));

        return $table;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Fund());

        $form->text('code', __('代码'));
        $form->text('name', __('名称'));

        return $form;
    }
}
