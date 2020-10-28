<?php

namespace Dedemao\Payjs\Http\Controllers\Admin;

use Dedemao\Payjs\Actions\Refund;
use Dedemao\Payjs\Models\PayjsOrders;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{

    protected $title = '订单列表';

    protected $description = [
        'index' => '订单列表',
    ];


    public function grid()
    {
        $grid = new Grid(new PayjsOrders());

        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('out_trade_no','订单号');
            $filter->like('outer_tid','PAYJS 订单号');
            $filter->like('transaction_tid','支付流水号');
            $filter->between('created_at', '订单创建时间')->datetime();

            // 多条件查询
            $filter->scope('today_order', '当日订单')
                ->whereDate('created_at', date('Y-m-d'))
                ->orWhere('updated_at', date('Y-m-d'));
            $filter->scope('today_pay_order', '当日已支付订单')
                ->where('status', 0)
                ->whereDate('pay_at', date('Y-m-d'));

        });
        $grid->model()->orderBy('id', 'DESC');

        $grid->column('id', 'ID')->sortable();
        $grid->column('type', '支付类型')->display(function ($type){
            switch ($type){
                case 'alipay':
                    return "<span class=\"label label-info\">支付宝</span>";
                case 'weixin':
                    return "<span class=\"label label-success\">微信</span>";
            }
        })->filter([
            'alipay' => '支付宝',
            'weixin' => '微信',
        ]);
        $grid->column('out_trade_no', '订单号')->copyable()->filter();
        $grid->column('subject', '订单标题')->filter();
        $grid->column('transaction_tid', '支付流水号')->filter();
        $grid->column('total_fee', '订单金额')->sortable();
        $grid->column('pay_at', '支付时间')->filter('range', 'datetime');
        $grid->column('created_at', trans('admin.created_at'))->display(function ($time){
            return date('Y-m-d H:i:s', strtotime($time));
        })->filter('range', 'datetime');
        $grid->column('status', '订单状态')->display(function ($status){
            switch ($status){
                case 0:
                    return "<span class=\"badge label-success\">已支付</span>";
                case 1:
                    return "<span class=\"badge label-grey\">待支付</span>";
                case 2:
                    return "<span class=\"badge label-danger\">已退款</span>";
            }
        })->filter([
            0 => '已支付',
            1 => '待支付',
            2 => '已退款',
        ]);

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if($actions->row->status!=1){
                $actions->disableDelete();
            }
            if($actions->row->status==0){
                $actions->add(new Refund);
            }
            $actions->disableEdit();
        });

        $grid->disableCreateButton();

        return $grid;
    }


    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $data = PayjsOrders::findOrFail($id);
        $show = new Show($data);

        $show->field('id', 'ID');
        $labelStyle = $data->type=='alipay' ? 'info' : 'success';
        $show->field('type', '支付类型')->as(function ($type){
            return getPayChannelName($type);
        })->label($labelStyle);
        $show->field('out_trade_no', '订单号');
        $show->field('subject', '订单标题');
        $show->field('outer_tid', 'PAYJS 平台订单号');
        $show->field('transaction_tid', '支付流水号');
        $show->field('total_fee', '订单金额');
        $show->field('status', '订单状态')->as(function ($status){
            switch ($status){
                case 0:
                    return "已支付";
                case 1:
                    return "待支付";
                case 2:
                    return "已退款";
            }
        });
        $show->field('buyer_info', '支付者信息');
        $show->field('pay_at', '支付时间');
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));


        $show->panel()->tools(function (Show\Tools $tools) {
            $tools->disableDelete();
            $tools->disableEdit();
        });;

        return $show;
    }
}
