<?php

namespace Dedemao\Payjs;

use Encore\Admin\Extension;

class Payjs extends Extension
{
    public $name = 'payjs';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public static function import()
    {
        parent::createMenu('Payjs', 'payjs', 'fa-cny',0,[
            ['title'=>'支付设置','path'=>'payjs/index','icon'=>'fa-cog'],
            ['title'=>'订单列表','path'=>'payjs/order','icon'=>'fa-bars'],
        ]);
//        parent::createMenu('Payjs', 'payjs', 'fa-cny');
        parent::createPermission('Payjs', 'Payjs', 'payjs*');
    }
}
