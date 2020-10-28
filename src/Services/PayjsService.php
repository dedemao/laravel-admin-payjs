<?php

namespace Dedemao\Payjs\Services;

class PayjsService
{
    private $url = 'https://payjs.cn/api';  //接口地址
    private $key;            // 填写通信密钥
    private $mchid;          // 商户号
    private $body;          // 订单标题
    private $outTradeNo;          // 订单号
    private $payChannel;          // 支付通道
    private $openid;          // openid
    private $totalFee;          // 订单金额
    private $notifyUrl;          // 接收微信支付异步通知的回调地址
    private $payTip;          // 支付提示信息
    private $postUrl;
    private $payMode = 'weixin';        //支付模式

    public function payment(array $configs)
    {
        $this->mchid = $configs['mchid'];;
        $this->key = $configs['appkey'];
        $this->notifyUrl = $configs['notify_url'];
        $this->payChannel = $configs['pay_channel'];
        return $this;
    }

    public function getQrcode($data)
    {
        $this->payMode = $data['type'];
        $this->totalFee = $data['total_fee'];
        $this->outTradeNo = $data['out_trade_no'];
        $this->body = $data['body'] ?? '订单号：'.$this->outTradeNo;
        return $this->native();
    }

    public function native()
    {
        $data = array(
            'mchid' => $this->mchid,
            'total_fee' => $this->totalFee * 100,      // 金额,单位:分
            'out_trade_no' => $this->outTradeNo,       // 订单号
            'body' => $this->body ?: '订单号：'.$this->outTradeNo, // 订单标题
            'notify_url' => $this->notifyUrl,             // 回调地址
        );
        if($this->payMode=='alipay'){
            $data['type'] = 'alipay';
        }

        $data['sign'] = $this->sign($data);
        return $this->curlPost($this->url.'/native', $data);
    }

    public function refund($orderid)
    {
        $data = array(
            'payjs_order_id' => $orderid,
        );
        $data['sign'] = $this->sign($data);
        return $this->curlPost($this->url.'/refund', $data);
    }

    public function orderquery($orderid)
    {
        $data = [
            "payjs_order_id" => $orderid,
        ];
        $data['sign'] = $this->sign($data);
        return $this->curlPost($this->url.'/check',$data);
    }

    private function sign(array $attributes)
    {
        ksort($attributes);
        $sign = strtoupper(md5(urldecode(http_build_query($attributes)) . '&key=' . $this->key));
        return $sign;
    }

    private function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 检查签名
     * @param $data
     * @return bool
     */
    public function check($data)
    {
        $_sign = $data['sign'];
        unset($data['sign']);
        $sign = $this->sign($data);
        return $sign == $_sign;
    }
}
