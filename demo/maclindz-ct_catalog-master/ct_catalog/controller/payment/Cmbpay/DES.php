<?php
/**
 * Created by PhpStorm.
 * User: 80374806
 * Date: 2016/8/31
 * Time: 14:24
 */

class DES{
    var $key;

    function DES($key) {
        //key长度8
        $this->key = $key;
    }

    /*
     * DES加密算法,使用mcrypt库，加密，返回大写十六进制字符串
     * $string 要加密的数据
     */
    function encrypt($string) {

        $size = mcrypt_get_block_size('des', 'ecb');
        $string = mb_convert_encoding($string, 'GBK', 'UTF-8');
        $pad = $size - (strlen($string) % $size);
        $string = $string . str_repeat(chr($pad), $pad);
        $td = mcrypt_module_open('des', '', 'ecb', '');
        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $this->key, $iv);
        $data = mcrypt_generic($td, $string);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = $this->String2Hex($data);
        return strtoupper($data);
    }

    //转成16进制
    function String2Hex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }
}