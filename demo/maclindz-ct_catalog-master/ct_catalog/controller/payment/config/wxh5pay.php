<?php
/**
 * 微信H5支付
 * User: adcbguo
 * Date: 2017/10/12
 * Time: 9:49
 */

return [

    //公众账号ID
    'APP_ID' => '',

    //商户ID
    'MCH_ID' => '',

    //支付密钥
    'KEY' => '',

    //私钥证书地址(通信使用证书才需要配置)
    'sslkey_path' => '',

    //公钥证书地址(通信使用证书才需要配置)
    'sslcert_path' => '',

    //设备ID
    'device_info' => 'WEB',

    //回调地址
    'notify_url' => 'http://api.boshangquan.com/wxh5pay',

    //下单接口
    'unifiedorder_url' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',

    //订单查询接口
    'orderquery_url' => 'https://api.mch.weixin.qq.com/pay/orderquery',

    //订单关闭接口
    'closeorder_url' => 'https://api.mch.weixin.qq.com/pay/closeorder',

];