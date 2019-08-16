<?php
class ControllerPaymentCmbpay extends Controller {
   // protected  $api_gate = "http://121.15.180.66:801/NetPayment/BaseHttp.dll?MB_EUserPay";
    protected  $api_gate = "https://netpay.cmbchina.com/netpayment/BaseHttp.dll?MB_EUserPay"; //一网通支付api

    //protected  $api_pubkey_gate = "http://121.15.180.72/CmbBank_B2B/UI/NetPay/DoBusiness.ashx";
    protected  $api_pubkey_gate = "https://b2b.cmbchina.com/CmbBank_B2B/UI/NetPay/DoBusiness.ashx";//一网通公钥api
	public function index() {

        $this->language->load('payment/cmbpay');

        $data['button_confirm'] = $this->language->get('button_confirm');

        //安全检验码，以数字和字母组成的32位字符
        $key		=	$this->config->get('cmbpay_security_code');

        //签名方式 不需修改
        $alipay_config['sign_type']    = strtoupper('MD5');

        $this->load->model('checkout/order');

        $order_id = $this->session->data['order_id'];

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $item_name = $this->config->get('config_name');
        $first_name = $order_info['payment_firstname'];
        $last_name = $order_info['payment_lastname'];
        $customer_id = $order_info['customer_id'];
        // Totals
        //get shipping fee
        $this->load->model('account/order');

        $shipping_cost = 0;

        $totals = $this->model_account_order->getOrderTotals($order_id);

        foreach ($totals as $total) {

            if($total['title'] == 'shipping') {
                $shipping_cost = $total['value'];
            }

        }

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = HTTPS_SERVER.'catalog/controller/payment/cmbpay_callback.php';
        //$notify_url = 'http://54.222.209.199/test.php';

        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = $this->url->link('checkout/success');
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //卖家支付宝帐户
        $cmbpay_no = $this->config->get('cmbpay_no');
        //必填

        //商户订单号
        $out_trade_no = 'cootoo'.$this->session->data['order_id'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $item_name . ' ' . $this->language->get('text_order') .' '. $order_id;
        //必填

        //付款金额
        $amount = $order_info['total'];

        $currency_value = $this->currency->getValue('CNY');
        $price = $amount * $currency_value;
        $price = number_format($price,2,'.','');

        $total_fee = $price;

        //必填
        $body =  $this->language->get('text_owner') . ' ' . $first_name .' '. $last_name;
        //商品展示地址
        $show_url = $this->url->link('common/home', '', 'SSL');
        //需以http://开头的完整路径，如：http://www.商户网站.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1
        include("Cmbpay/netPay.php");
        $netpay = new NetPay($key);
        $agrno = $this->customer->getAgrNo();
        $agrno = md5('1q@W3e'.$agrno);
        $bank_no = $this->config->get('cmbpay_bankno');//分行号
        $merchant_no = $this->config->get('cmbpay_merchant_no');;//商户号
        $ip = $this->getIP();
        $parameter = array(
            'dateTime' => date('YmdHis'),
            'branchNo' => $bank_no,
            'merchantNo' => $merchant_no,
            'date' =>  date('Ymd'),
            'orderNo' => $out_trade_no,
            'amount' => $total_fee,
            'expireTimeSpan' => '30',
            'payNoticeUrl' => $notify_url,
            'payNoticePara' => '支付',
            'returnUrl' => $return_url,
            'clientIP' => $ip,
            'cardType' => '',
            'agrNo' => $agrno,
            'merchantSerialNo' => '20170804143807',
            'userID' => $customer_id,
            'mobile' => '',
            'lon' => '',
            'lat' => '',
            'riskLevel' => '3',
            'signNoticeUrl' => 'http://www.baidu.com/notice.aspx?',
            'signNoticePara' => '',
            'merchantCssUrl' => '',
            'merchantBridgeName' => '',
        );
        $sign = $netpay->sign($parameter, $key);

        $post_para = array(
            "version"=>"1.0",
            "charset"=>"UTF-8",
            "sign"=>$sign,
            "signType"=>"SHA-256",
            "reqData"=>$parameter,
        );

        $form_data = array('jsonRequestData'=>json_encode($post_para));
        $data['html_text'] = $this->_buildForm($form_data,$this->language->get('button_confirm'));


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cmbpay.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/cmbpay.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/cmbpay.tpl', $data);
        }

	}

    public function callback() {
        ini_set('date.timezone','Asia/Shanghai');
        $logger = new Log('cmbpay_callback.log');
        $logger->write(date('Y-m-d H:i:s').'cmb callback'.PHP_EOL);
        $this->load->helper('alipay_dt_core');
        $this->load->helper('alipay_dt_md');

        //合作身份者id，以2088开头的16位纯数字
        $alipay_config['partner']	=	$this->config->get('cmbpay_partner');

        //安全检验码，以数字和字母组成的32位字符
        $key		=	$this->config->get('cmbpay_security_code');
        include("Cmbpay/netPay.php");
        $netpay = new NetPay($key);

        //签名方式 不需修改
        $alipay_config['sign_type']    = strtoupper('MD5');

        //字符编码格式 目前支持 gbk 或 utf-8
        $alipay_config['input_charset']= strtolower('utf-8');

        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['cacert']    = getcwd().'\\cacert.pem';

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $alipay_config['transport']    = HTTPS_SERVER;


        if(empty($_POST)){
        }else{
            $jsonRequestData = $_POST['jsonRequestData'];
            if(!empty($jsonRequestData)){
                $this->load->model('account/order');
                $pubkey = $this->getPubKey($netpay);
                $respons_arr = json_decode($jsonRequestData,true);
                $logger->write(print_r($respons_arr,true));
                $notice_data = $respons_arr['noticeData'];
                $order_id_arr = explode('cootoo',$notice_data['orderNo']);
                $order_id = $order_id_arr[1];
                $res_sign = $respons_arr['sign'];
                $verify_result=$netpay->verifysign($notice_data,$res_sign,$pubkey);
                if($verify_result){
                    $order_info = $this->model_checkout_order->getOrder($order_id);
                    if ($order_info) {
                        $order_status_id = $this->config->get('alipay_direct_trade_finished_status_id');
                        $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
                    }
                }
            }
        }

    }

    public function getPubKey($netpay){
        $file = 'cmb_pub_key.txt';
        if (is_file($file) == false) {
            $key		=	$this->config->get('cmbpay_security_code');
            $bank_no = $this->config->get('cmbpay_bankno');//分行号
            $merchant_no = $this->config->get('cmbpay_merchant_no');;//商户号
            $time = date('YmdHis');
            $sign_arr = array(
                'dateTime' => $time,
                'branchNo' => $bank_no,
                'merchantNo' => $merchant_no,
                'txCode' => 'FBPK',
            );
            $sign_pub = $netpay->sign($sign_arr, $key);
            $dd = array(
                'version'=>'1.0',
                "charset"=>"UTF-8",
                "sign"=>$sign_pub,
                "signType"=>"SHA-256",
                "reqData"=>array(
                    "dateTime"=>$time,
                    "txCode"=>"FBPK",
                    "branchNo"=>$bank_no,
                    "merchantNo"=>$merchant_no
                )
            );
            $post_data = array(
                'jsonRequestData'=>json_encode($dd)
            );
            $response = post_by_curl($this->api_pubkey_gate,$post_data);
            if($response){
                $response_arr = json_decode($response,true);
                $rspCode = $response_arr['rspData']['rspCode'];
                if($rspCode == 'SUC0000'){
                    $pubkey = $response_arr['rspData']['fbPubKey'];
                    file_put_contents($file,$pubkey);
                }

            }
        }
        $pubkey = file_get_contents($file);
        return $pubkey;

    }

    function getIP()
    {
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (@$_SERVER["HTTP_CLIENT_IP"])
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (@$_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (@getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (@getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (@getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknown";
        return $ip;
    }

    private  function _buildForm($para,$button_name){
	    $method = 'post';
        $sHtml = "<form id='cmbpay' name='cmbpaysubmit' action='".$this->api_gate."' method='".$method."'>";
        while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<div class='buttons'><div class='pull-right'><input type='submit' value='".$button_name."' class='btn btn-primary' /></div></div></form>";
        return $sHtml;
	}
	private function _getSign(){
        include("./netPay.php");


        $rspData = json_encode(json_encode(array("data" => $sign)));

        echo $rspData;
    }

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'cod') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
		}
	}
}
