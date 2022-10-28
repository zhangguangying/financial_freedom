<?php

namespace App\Admin\Controllers;

use App\Models\Fund;
use App\Models\FundFlow;
use App\Models\FundOwner;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Table;

class FundFlowController extends AdminController
{
    protected $title = '基金流水';

    public $types = [
        1 => '买入',
        2 => '卖出',
    ];

    protected function table()
    {
        $table = new Table(new FundFlow());

        $table->column('id', __('Id'));
        $table->column('fund.code', __('基金代码'));
        $table->column('fund.name', __('基金名称'));
        $table->column('type', __('操作类型'))->using($this->types);
        $table->column('amount', __('份数'));
        $table->column('net_worth', __('净值'));
        $table->column('service_charge', __('手续费'));

        return $table;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new FundFlow());

        $form->select('fund_id', __('基金'))
            ->options(Fund::query()->pluck('name', 'id')->toArray());
        $form->select('type', __('Type'))->options($this->types);
        $form->decimal('price', __('价格'));
        $form->decimal('amount', __('份数'));
        $form->decimal('net_worth', __('净值'));
        $form->decimal('service_charge', __('手续费'));
        $form->date('create_time', __('创建时间'));
        $form->saved(function (Form $form) {
            if ($form->type == 1) {
                // 原来的份额及净值
                $fund         = FundOwner::query()->where('fund_id', $form->fund_id)->first(['own_amount', 'net_worth']);
                $own_amount   = 0;
                $origin_price = 0;
                if (!empty($fund)) {
                    // 原来的份额*净值是原来的价格 = 原来的总价格
                    $origin_price = bcmul((string)$fund->own_amount, (string)$fund->net_worth, 4);
                    $own_amount   = $fund->own_amount;
                }
                // 当前的份额 + 以前的份额
                $own_amount = bcadd((string)$form->amount, (string)$own_amount, 4);
                // 原来的总价格 + 当前的价格 / 总份额
                $net_worth  = bcdiv(bcadd($origin_price, (string)$form->price, 4), $own_amount, 4);
                FundOwner::query()
                    ->updateOrCreate([
                        'fund_id' => $form->fund_id,
                    ], [
                        'own_amount' => $own_amount,
                        'net_worth'  => $net_worth,
                    ]);
            } else {
                FundOwner::query()
                    ->where('fund_id', $form->fund_id)
                    ->decrement('own_amount', $form->amount);
            }
        });

        return $form;
    }
}
