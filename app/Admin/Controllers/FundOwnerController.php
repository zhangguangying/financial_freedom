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

    protected function table(): Table
    {
        $table = new Table(new FundOwner());
        $table->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->equal('fund.code', __('代码'));
        });

        $fundLatelyValues = FundValue::getFundLatelyValue();
        // 最新净值
        $table->model()->collection(function (Collection $collection) use ($fundLatelyValues) {
            foreach ($collection as $item) {
                $item->new_net_worth = $fundLatelyValues[$item->fund_id];
                $item->profit_rate   = bcdiv(bcsub(strval($item->new_net_worth), strval($item->net_worth), 4), strval($item->net_worth), 4) * 100 . '%';
                $item->profit        = bcmul(bcsub(strval($item->new_net_worth), strval($item->net_worth), 4), strval($item->own_amount), 2);
            }
            return $collection->sortByDesc('profit');
        });
        $table->column('fund.code', __('代码'));
        $table->column('fund.name', __('名称'));
        $table->column('own_amount', __('拥有份额'));
        $table->column('net_worth', __('每份成本'));
        $table->column('new_net_worth', __('最新净值'));
        $table->column('profit_rate', __('盈利比例'))->display(function ($value) {
            if ($value > 0) {
                return "<span style='color: red'>{$value}</span>";
            }
            return "<span style='color: green'>{$value}</span>";
        });
        $table->column('profit', __('盈利金额'))->display(function ($value) {
            if ($value > 0) {
                return "<span style='color: red'>{$value}</span>";
            }
            return "<span style='color: green'>{$value}</span>";
        });

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
