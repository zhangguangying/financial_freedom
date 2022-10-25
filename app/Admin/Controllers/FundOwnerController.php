<?php

namespace App\Admin\Controllers;

use App\Models\Fund;
use App\Models\FundOwner;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Show;
use Encore\Admin\Table;

class FundOwnerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'FundOwner';

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $table = new Table(new FundOwner());

        $table->column('id', __('Id'));
        $table->column('fund_id', __('基金ID'));
        $table->column('fund.code', __('代码'));
        $table->column('fund.name', __('名称'));
        $table->column('own_amount', __('拥有份额'));
        $table->column('net_worth', __('每份成本'));

        return $table;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(FundOwner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('fund_id', __('Fund id'));
        $show->field('own_amount', __('Own amount'));
        $show->field('net_worth', __('Net worth'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
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
