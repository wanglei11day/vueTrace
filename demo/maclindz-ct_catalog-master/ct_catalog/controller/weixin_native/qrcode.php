<?php
/*
	微信扫码支付 二维码付款页面
	QQ群 50415210 群共享有免费插件,请加群查看
	技术支持 30171310@qq.com
	如有其他做站需求,请联系,谢谢.
*/
class Controllerweixinnativeqrcode extends Controller {
	public function index() {
		
		error_reporting(E_ERROR);
		require_once 'phpqrcode/phpqrcode.php';
		
		
		$url = urldecode($_GET["data"]);
		//QRcode::png($url);
		

		$errorCorrectionLevel = "L";
		//设定生成的二维码的大小 默认是4 调高点
		$matrixPointSize = "8";
		QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
		exit;
		
		
		
	}
}
