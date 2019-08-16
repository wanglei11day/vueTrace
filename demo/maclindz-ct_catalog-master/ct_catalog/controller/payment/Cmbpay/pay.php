<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/7/27
 * Time: 19:17
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
        'expireTimeSpan' => $_POST['expireTimeSpan'],
        'payNoticeUrl' => $_POST['payNoticeUrl'],
        'payNoticePara' => $_POST['payNoticePara'],
        'returnUrl' => $_POST['returnUrl'],
        'clientIP' => $_POST['clientIP'],
        'cardType' => $_POST['cardType'],
        'agrNo' => $_POST['agrNo'],
        'merchantSerialNo' => $_POST['merchantSerialNo'],
        'userID' => $_POST['userID'],
        'mobile' => $_POST['mobile'],
        'lon' => $_POST['lon'],
        'lat' => $_POST['lat'],
        'riskLevel' => $_POST['riskLevel'],
        'signNoticeUrl' => $_POST['signNoticeUrl'],
        'signNoticePara' => $_POST['signNoticePara'],
        'merchantCssUrl' => $_POST['merchantCssUrl'],
        'merchantBridgeName' => $_POST['merchantBridgeName']
    )
);

$netpay->pay($parasArr,$_POST['charset']);
