<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/7/28
 * Time: 15:21
 */

include("./netPay.php");
$netpay = new NetPay();
$parasArr = array(
    'version' => $_POST['version'],
    'charset' => $_POST['charset'],
    'sign' => $_POST['sign'],
    'signType' => $_POST['signType'],
    'reqData' => array(
        'bankSerialNo' => $_POST['bankSerialNo'],
        'branchNo' => $_POST['branchNo'],
        'date' => $_POST['date'],
        'dateTime' => $_POST['dateTime'],
        'merchantNo' => $_POST['merchantNo'],
        'operatorNo' => $_POST['operatorNo'],
        'orderNo' => $_POST['orderNo'],
        'orderRefNo' => $_POST['orderRefNo'],
        'type' => $_POST['type']
    )
);

//获取响应报文
$result = $netpay->querySingleOrder($parasArr, $_POST['charset']);
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
    echo "时间戳：".$rspData['dateTime']."<br>";
    echo "分行号：".$rspData['branchNo']."<br>";
    echo "商户号：".$rspData['merchantNo']."<br>";
    echo "商户日期：".$rspData['date']."<br>";
    echo "订单号：".$rspData['orderNo']."<br>";
    echo "订单流水号：".$rspData['bankSerialNo']."<br>";
    echo "交易币种：".$rspData['currency']."<br>";
    echo "订单金额：".$rspData['orderAmount']."<br>";
    echo "费用金额：".$rspData['fee']."<br>";
    echo "银行受理日期：".$rspData['bankDate']."<br>";
    echo "结算金额：".$rspData['settleAmount']."<br>";
    echo "优惠金额：".$rspData['discountAmount']."<br>";
    echo "订单状态：".$rspData['orderStatus']."<br>";
    echo "处理日期：".$rspData['settleDate']."<br>";
    echo "处理时间：".$rspData['settleTime']."<br>";

    if($rspData['cardType'] == "02"){
        echo "卡类型：一卡通(".$rspData['cardType'].")<br>";
    }
    if($rspData['cardType'] == "03"){
        echo "卡类型：信用卡(".$rspData['cardType'].")<br>";
    }
    if($rspData['cardType'] == "07"){
        echo "卡类型：他行卡(".$rspData['cardType'].")<br>";
    }
    echo "商户自定义参数：".$rspData['merchantPara']."<br>";

//    print_r($title);
}else{
    echo "失败信息：" . $rspData["rspMsg"];
}
echo"<br><a href='./index.html' class='btn btn-link'>返回</a>";