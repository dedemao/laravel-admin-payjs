<?php

namespace Dedemao\Payjs\Http\Controllers\Admin;

use Dedemao\Payjs\Models\PayjsOrders;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Dedemao\Payjs\Models\PayjsConfigs;
use Illuminate\Support\Facades\Cache;

class PayjsController extends AdminController
{

    protected $title = '支付配置';

    protected $description = [
        'index' => 'Payjs接口信息配置',
    ];

    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new PayjsConfigs());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('mchid', '商户号');
        $grid->column('appkey', '通信密钥')->display(function ($appkey) {
            return getMask($appkey,10,-10);
        });
        $grid->column('pay_channel', '支付通道')->display(function () {
            return getPayChannelName($this->pay_channel);
        });
        $grid->column('notify_url', '回调地址');
        $grid->footer(function ($query) {
            return '<a target="_blank" href="'.url('pay/test').'" class="btn btn-sm btn-success" title="支付测试"><span class="hidden-xs">支付测试</span></a>';
        });

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
        });

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new PayjsConfigs());

        $form->setTitle($this->title());
        $form->text('mchid','商户号')->rules('required')->help('在payjs会员中心查看');
        $form->text('appkey','通信密钥')->rules('required')->help('在payjs会员中心查看');
        $form->select('pay_channel','支付通道')->default('all')->options(['all'=>'支付宝和微信','alipay'=>'支付宝','weixin'=>'微信']);
        $form->text('notify_url','回调地址')->default(url("pay/notify"))->help('接收支付异步通知的回调地址（确保可以外网访问）');

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
//            $tools->disableList();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        $form->saved(function (Form $form) {
            Cache::forever('payjs.config', ['mchid' => $form->model()->mchid, 'appkey' => $form->model()->appkey, 'pay_channel' => $form->model()->pay_channel, 'notify_url' => $form->model()->notify_url]);
        });
        return $form;
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
        $data = PayjsConfigs::findOrFail($id);
        $show = new Show($data);

        $show->field('id', 'ID');
        $show->field('mchid', '商户号');
        $show->field('appkey', '通信密钥')->as(function ($appkey){
            return getMask($appkey,10,-10);
        });
        $show->field('pay_channel', '支付通道');
        $show->field('notify_url', '回调地址');
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));


        $show->panel()->tools(function (Show\Tools $tools) {
            $tools->disableDelete();
            $tools->disableEdit();
        });;

        return $show;
    }
}
