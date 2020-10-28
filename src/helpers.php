<?php

if ( ! function_exists('getLocalDomain')) {
    function getPayChannelName($channel)
    {
        switch ($channel){
            case 'all':
                return '支付宝和微信';
            case 'alipay':
                return '支付宝';
            case 'weixin':
                return '微信';
        }
    }
}
if ( ! function_exists('isWeixin')) {
    function isWeixin() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('generateOutTradeNo')) {
    function generateOutTradeNo()
    {
        return date('YmdHis').(microtime(true) % 1) * 1000 .mt_rand(0, 9999);
    }
}


if ( ! function_exists('getMask')) {
    function getMask($str,$len=4,$start=-3,$mask='*')
    {
        $size = strlen($str);
        $size = $size > $len ? $len : ceil($size*0.6);
        $str = substr_replace($str,str_pad('',$size,$mask),$start,$len);
        return $str;
    }
}
