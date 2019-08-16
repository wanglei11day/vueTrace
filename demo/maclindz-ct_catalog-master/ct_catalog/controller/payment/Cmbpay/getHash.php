<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/8/15
 * Time: 16:05
 */

include("./netPay.php");
//商户秘钥
$key = "1234567890abcABC";
$jsonStr = $_POST['input'];
$netpay = new NetPay();

$jsonArr = json_decode($jsonStr, true);

$sign = $netpay->sign($jsonArr, $key);
$rspData = json_encode(json_encode(array("data" => $sign)));

echo $rspData;