<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/8/1
 * Time: 9:54
 */

include("./netPay.php");
$netpay = new NetPay();


$parasArr = array(
    'version' => $_POST['version'],
    'charset' => $_POST['charset'],
    'sign' => $_POST['sign'],
    'signType' => $_POST['signType'],
    'reqData' => array(
        'dateTime' => $_POST['dateTime'],
        'branchNo' => $_POST['branchNo'],
        'merchantNo' => $_POST['merchantNo'],
        'date' => $_POST['date'],
        'orderNo' => $_POST['orderNo'],
        'amount' => $_POST['amount'],
        'desc' => $_POST['desc'],
        'refundSerialNo' => $_POST['refundSerialNo'],
        'operatorNo' => $_POST['operatorNo'],
        'encrypType' => $_POST['encrypType'],
        'pwd' => $_POST['pwdEncrypt']
));

//获取响应报文
$result = $netpay->doRefund($parasArr, $_POST['charset']);
$resArr = json_decode($result, true);
echo $result."<br><br>";

//解析Json报文
echo "<link href='./bootstrap-3.3.5-dist/css/bootstrap.min.css' rel='stylesheet' type='text/css' />";
echo "接口版本号：".$resArr['version']."<br>";
echo "参数编码：".$resArr['charset']."<br>";
echo "应答数据如下"."<br>";
echo "--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>";

$rspData = $resArr['rspData'];
echo "处理结果：".$rspData['rspCode']."<br>";

if($rspData['rspCode']=="SUC0000"){
    echo "响应时间：".$rspData['dateTime']."<br>";
    echo "退款流水号：".$rspData['bankSerialNo']."<br>";
    echo "退款币种：".$rspData['currency']."<br>";
    echo "退款金额：".$rspData['amount']."<br>";
    echo "退款参考号：".$rspData['refundRefNo']."<br>";
    echo "银行受理日期：".$rspData['bankDate']."<br>";
    echo "银行受理时间：".$rspData['bankTime']."<br>";

}else{
    echo "失败信息：".$rspData["rspMsg"];
}

echo"<br><a href='./index.html' class='btn btn-link'>返回</a>";