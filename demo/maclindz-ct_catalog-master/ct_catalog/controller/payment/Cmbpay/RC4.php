<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/8/31
 * Time: 14:32
 */

class RC4 {
    var $merKey;

    function RC4($key){
        $this->merKey = $key;
    }

    /*
     * rc4加密算法
     * $data 要加密的数据
     */
    function encrypt($data)//$pwd密钥　$data需加密字符串
    {
        $key[] ="";
        $box[] ="";
        $cipher = "";

        $pwd_length = strlen($this->merKey);
        $data_length = strlen($data);

        for ($i = 0; $i < 256; $i++)
        {
            $key[$i] = ord($this->merKey[$i % $pwd_length]);
            $box[$i] = $i;
        }

        for ($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $data_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;

            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;

            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }

        return strtoupper($this->String2Hex($cipher));
    }

    //转成16进制
    function String2Hex($string){
        $hex='';
        for ($i=0; $i < strlen($string); $i++){
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }
}