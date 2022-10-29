<?php

namespace App\Admin\Controllers;

use App\Models\Fund;
use App\Models\FundOwner;
use App\Models\FundValue;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Table;
use Illuminate\Support\Collection;

class FundOwnerController extends AdminController
{
    protected $title = '我的基金';

    protected function table()
    {
        $table = new Table(new FundOwner());

        $fundLatelyValues = FundValue::getFundLatelyValue();
        $table->model()->collection(function (Collection $collection) use ($fundLatelyValues) {
            foreach ($collection as $item) {
                $item->new_net_worth = $fundLatelyValues[$item->fund_id];
            }
            return $collection;
        });
        $table->column('fund.code', __('代码'));
        $table->column('fund.name', __('名称'));
        $table->column('own_amount', __('拥有份额'));
        $table->column('net_worth', __('每份成本'));
        $table->column('new_net_worth', __('最新净值'));

        return $table;
    }

    protected function form()
    {
        $form = new Form(new FundOwner());

        $form->select('fund_id', __('Fund id'))
            ->options(Fund::query()->pluck('name', 'id')->toArray());
        $form->decimal('own_amount', __('Own amount'))->default(0.0000);
        $form->decimal('net_worth', __('Net worth'))->default(0.0000);

        return $form;
    }
}
