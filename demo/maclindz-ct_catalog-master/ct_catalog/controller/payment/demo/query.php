<?php
/**
 * 订单查询测试
 */
$config = include_once '../config/wxh5pay.php';
include_once '../src/wxh5pay/Order.php';
include_once '../src/wxh5pay/lib/Core.php';
include_once '../src/wxh5pay/lib/Sign.php';

$order = new \wxh5pay\Order($config);
$order->setParams([
    'out_trade_no' => date('YmdHis') . rand(10000, 9999)
]);
$res = $order->orderquery();
if(!$res){
    var_dump($order->errMsg);
}else{
    var_dump($res);
}