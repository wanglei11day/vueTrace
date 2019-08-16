<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/7/26
 * Time: 19:00
 */

class NetPay{

    //测试地址
    var  $host = "https://b2b.cmbchina.com/CmbBank_B2B/UI/NetPay/DoBusiness.ashx"; //一网通支付api

    //商户秘钥
    var $key = '1234567890abcABC';

    function __construct($merch_key)
    {
        $this->key = $merch_key;
    }

    //post数据
    function httpPost($parasData, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "jsonRequestData=".$parasData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    //升序排序
    function dictSort($reqData){
        $keyArr = [];
        $keyArrSorted = [];
        foreach($reqData as $key => $val){
            array_push($keyArr, strtolower($key));
        }
        sort($keyArr);

        for($i = 0; $i < count($keyArr); $i++){
            foreach($reqData as $key => $val){
                if(!strcasecmp($key, $keyArr[$i])){
                    $keyArrSorted[$key] = $val;
                }
            }
        }
        return $keyArrSorted;
    }

    //签名
    function sign($reqData, $merKey){

        $strToSign = '';
        $reqData = $this->dictSort($reqData);
        foreach($reqData as $key => $val){
            $strToSign = $strToSign.$key."=".$val."&";
        }
        $strToSign = $strToSign.$merKey;

        //sha256加密
        $strEncrypt = hash('sha256', $strToSign);
        return $strEncrypt;

    }

    //验证签名
    function verifysign($notice_data,$sign,$pubkey){
        $strToSign = '';
        $reqData = $this->dictSort($notice_data);

        foreach($reqData as $key => $val){
            $strToSign = $strToSign.$key."=".$val."&";
        }
        $pem = chunk_split($pubkey, 64, "\n");
        $pem = "-----BEGIN PUBLIC KEY-----\n" . $pem . "-----END PUBLIC KEY-----\n";
        $pkid = openssl_pkey_get_public($pem);
        if (empty($pkid)) {
            return 0;
        }
        //验证
        $result = openssl_verify($strToSign, base64_decode($sign), $pkid, OPENSSL_ALGO_SHA1); //openssl_verify验签成功返回1，失败0，错误返回-1
        return $result;
    }

    //查询单笔订单
    function querySingleOrder($parasArr, $charset){
        $url = $this->host.'QuerySingleOrder';
        //编码
        $parasData = mb_convert_encoding(json_encode($parasArr, JSON_UNESCAPED_UNICODE),$charset);
        $result = $this->httpPost($parasData, $url);
        return $result;
    }

    //对账
    function queryAccountedOrder($parasArr, $charset){
        $url = $this->host.'QueryAccountList';
        //编码
        $parasData = mb_convert_encoding(json_encode($parasArr, JSON_UNESCAPED_UNICODE),$charset);
        $result = $this->httpPost($parasData, $url);

        return $result;
    }

    //查询已处理订单（按商户日期）
    function queryOrderByMerchantDate($parasArr, $charset){
        $url = $this->host.'QuerySettledOrderByMerchantDate';
        //编码
        $parasData = mb_convert_encoding(json_encode($parasArr,JSON_UNESCAPED_UNICODE),$charset);
        $result = $this->httpPost($parasData, $url);

        return $result;
    }

    //退款
    function doRefund($parasArr ,$charset){
        $url = $this->host.'DoRefund';
        //编码
        $parasData = mb_convert_encoding(json_encode($parasArr, JSON_UNESCAPED_UNICODE), $charset);
        $result = $this->httpPost($parasData, $url);

        return $result;
    }

    //查询退款记录（按日期查询）
    function QueryRefundByDate($parasArr, $charset){
        $url = $this->host.'QueryRefundByDate';
        //编码
        $parasData = mb_convert_encoding(json_encode($parasArr,JSON_UNESCAPED_UNICODE),$charset);
        $result = $this->httpPost($parasData, $url);

        return $result;
    }
}