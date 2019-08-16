<?php
/**
 * 支付测试
 */
$config = include_once '../config/wxh5pay.php';
include_once '../src/wxh5pay/Order.php';
include_once '../src/wxh5pay/lib/Core.php';
include_once '../src/wxh5pay/lib/Sign.php';

$order = new \wxh5pay\Order($config);
$order->setParams([
    'body' => '测试产品',
    'out_trade_no' => date('YmdHis') . rand(10000, 9999),
    'total_fee' => '1',
    'trade_type' => 'MWEB',
    'device_info' => $config['device_info'],
    'spbill_create_ip' => '127.0.0.1',
    'notify_url' => $config['notify_url'],
    'scene_info' => '{"h5_info": {"type":"Wap","wap_url": "https://pay.qq.com","wap_name": "腾讯充值"}}'
]);
$res = $order->unifiedorder();
if(!$res){
    var_dump($order->errMsg);
}else{
    echo "<a href='{$res}'>点击支付</a>";
}