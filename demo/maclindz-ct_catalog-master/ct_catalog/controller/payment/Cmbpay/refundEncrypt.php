<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/8/31
 * Time: 10:39
 */

/*
 * 加密算法
 */
include("./DES.php");
include("./RC4.php");

$keyStr = $_POST['input'];
$resArr = json_decode($keyStr, true);
// 商户秘钥
$merKey = '1234567890abcABC';


if($resArr['encrypType'] == "RC4"){ // RC4加密

    $rc4 = new RC4($merKey);
    $result = $rc4->encrypt($resArr['data']);
} elseif($resArr['encrypType'] == "DES") { //DES加密

    //先补8个0，再截取前8位
    $merKey .= "00000000";
    $des = new DES(substr($merKey, 0, 8));
    $result = $des->encrypt($resArr['data']);
} else { //不加密
    $result = $resArr['data'];
}

echo json_encode(json_encode(array("data" => $result)));
