<?php
namespace Dedemao\Payjs\Actions;

use Dedemao\Payjs\Facades\OrderService;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Refund extends RowAction
{
    public $name = '退款';

    public function form(Model $model)
    {
        $this->text('total_fee', '退款金额（元）')->default($model->total_fee)->readonly()->rules('required');
    }

    public function handle(Model $model, Request $request)
    {
        $fefundFee = floatval($request->get('total_fee'));
        if($fefundFee<0.01){
            return $this->response()->error('退款金额需大于等于0.01元')->refresh();
        }
        if($fefundFee>$model->total_fee){
            return $this->response()->error('退款金额不能超过订单金额')->refresh();
        }
        $result = OrderService::refund($model->out_trade_no);
        if($result['status']!='success'){
            return $this->response()->error($result['msg'])->refresh();
        }else{
            return $this->response()->success($result['msg'])->refresh();
        }
    }
}
