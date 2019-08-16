<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/8/1
 * Time: 11:14
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
        'beginDate' => $_POST['beginDate'],
        'endDate' => $_POST['endDate'],
        'operatorNo' => $_POST['operatorNo'],
        'nextKeyValue' => $_POST['nextKeyValue']
    )
);

//获取响应报文
$result = $netpay->QueryRefundByDate($parasArr, $_POST['charset']);
$resArr = json_decode($result, true);
echo $result."<br><br>";

//解析Json报文
echo "<link href='./bootstrap-3.3.5-dist/css/bootstrap.min.css' rel='stylesheet' type='text/css' />";
echo "接口版本号：".$resArr['version']."<br>";
echo "参数编码：".$resArr['charset']."<br>";
echo "应答数据如下"."<br>";
echo "--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>";

$rspData = $resArr['rspData'];
echo "处理结果：".$rspData['rspCode']."</span><br>";

if($rspData['rspCode']=="SUC0000"){
    echo "时间戳：".$rspData['dateTime']."<br>";
    echo "续传标志：".$rspData['hasNext']."<br>";
    echo "续传键值：".$rspData['nextKeyValue']."<br>";
    echo "记录条数：".$rspData['dataCount']."<br>";

    $dataList = explode("\r\n",$rspData['dataList']);
    $title = explode(",`",$dataList[0]);

    echo "<table class='table table-bordered table-hover'>";
    echo "<tr>";
    echo "<th>#</th>";
    for($i=0; $i<count($title);$i++){
        echo "<th>".$title[$i]."</th>";
    }
    echo "</tr>";

    for($i=1;$i<count($dataList);$i++){
        $data = explode(",`",$dataList[$i]);
        echo "<tr>";
        echo "<td>".$i."</td>";
        for($j=0;$j<count($data);$j++){
            echo "<td>".$data[$j]."</td>";
        }
        echo "</tr>";
    }

    echo "</table>";

//    print_r($title);
}else{
    echo "失败信息：".$rspData["rspMsg"];
}

echo"<br><a href='./index.html' class='btn btn-link'>返回</a>";