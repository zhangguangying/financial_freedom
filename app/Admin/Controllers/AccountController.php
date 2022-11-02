<?php

namespace App\Admin\Controllers;

use App\Models\Account;
use App\Models\FundOwner;
use App\Models\FundValue;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Table;

class AccountController extends AdminController
{
    protected $title = '我的账户';

    protected $types = [
        1 => '应急资金',
        2 => '生活日用',
        3 => '投资账户',
        4 => '待投入基金',
        5 => '基金账户',
        6 => '公积金账户'
    ];

    protected function table()
    {
        $table = new Table(new Account());

        // 更新基金净值
        $ownFunds         = FundOwner::query()
            ->get(['fund_id', 'own_amount'])
            ->toArray();
        $fundSum          = '0';
        $fundLatelyValues = FundValue::getFundLatelyValue();
        foreach ($ownFunds as $fund) {
            $nowNetWorth = $fundLatelyValues[$fund['fund_id']];
            $fundSum     = bcadd($fundSum, bcmul((string)$nowNetWorth, (string)$fund['own_amount'], 4), 4);
        }
        Account::query()
            ->where('type', 5)
            ->update(['value' => $fundSum]);

        $table->column('id', __('Id'));
        $table->column('name', __('账户名称'));
        $table->column('type', __('账户类型'))->using($this->types);
        $table->column('value', __('金额'))->totalRow();

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
