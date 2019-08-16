<?php
/**
 * 通知
 */
$config = include_once '../config/wxh5pay.php';
include_once '../src/wxh5pay/Order.php';
include_once '../src/wxh5pay/lib/Core.php';
include_once '../src/wxh5pay/lib/Sign.php';

$xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents('php://input');
$resArray = \wxh5pay\lib\Core::xmlToArray($xml);
if($resArray['return_code']== 'SUCCESS'){
    //验证签名,判断是否是微信通知
    $sign = $resArray['sign'];
    unset($resArray['sign']);
    $wxSign = \wxh5pay\lib\Sign::makeSign($resArray,$config['KEY']);
    if($sign == $wxSign){
        //商户自己的业务


        //返回微信通知成功
        echo \wxh5pay\lib\Core::arrayToXml([
            'return_code' => 'SUCCESS',
            'return_msg' => 'OK'
        ]);
    }
}