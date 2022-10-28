<?php

namespace App\Admin\Controllers;

use App\Models\FundValue;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Show;
use Encore\Admin\Table;

class FundValueController extends AdminController
{
    protected $title = '基金净值';

    protected function table()
    {
        $table = new Table(new FundValue());
        $table->model()
            ->orderByDesc('date')
            ->orderByDesc('id');

        $table->column('fund.code', __('代码'));
        $table->column('fund.name', __('名称'));
        $table->column('date', __('日期'));
        $table->column('net_worth', __('净值'));

        return $table;
    }
}
