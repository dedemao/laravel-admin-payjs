适用于laravel admin的payjs支付插件
======
## 安装

前提：已安装好laravel及laravel admin

```
composer require dedemao/laravel-admin-payjs
php artisan vendor:publish --provider=Dedemao\Payjs\PayjsServiceProvider
php artisan payjs:install -m
````

安装后，后台将自动生成payjs相关菜单，填写好配置信息后，即可使用。

## 卸载
```
composer remove dedemao/laravel-admin-payjs
php artisan payjs:uninstall -m
````

## 使用
### 如何支付
指定订单金额：
http://yourname/pay/index?total_fee=0.01

指定订单号：
http://yourname/pay/index?total_fee=0.01out_trade_no=123456

指定订单标题：
http://yourname/pay/index?total_fee=0.01&subject=测试

指定支付通道：
http://yourname/pay/index?total_fee=0.01&pay_channel=weixin

全都指定：
http://yourname/index?out_trade_no=123456&total_fee=0.01&subject=测试&pay_channel=weixin

### 异步通知
异步通知在pay/notify中

### 退款
退款请参考OrderService中的refund方法
