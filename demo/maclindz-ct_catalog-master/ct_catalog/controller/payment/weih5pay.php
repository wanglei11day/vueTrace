<?php
require_once(DIR_APPLICATION."controller/payment/WxPayPubHelper/WxPayPubHelper.php");
require_once(DIR_APPLICATION."controller/payment/WxPayPubHelper/WxPay.pub.config.php");

class ControllerPaymentWeih5pay extends Controller {
	public function index() {

        $this->language->load('payment/alipay_direct');

        $data['button_confirm'] = $this->language->get('button_confirm');



        $this->load->model('checkout/order');


        $order_id = $this->session->data['order_id'];
        $data['order_id'] = $order_id;
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $item_name = $this->config->get('config_name');
        $first_name = $order_info['payment_firstname'];
        $last_name = $order_info['payment_lastname'];

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


        $notify_url = HTTPS_SERVER.'catalog/controller/payment/weih5pay_callback.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数


        //商户订单号
        $out_trade_no = $this->session->data['order_id'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $item_name . ' ' . $this->language->get('text_order') .' '. $order_id;
        //必填

        //付款金额
        $amount = $order_info['total'];

        $currency_value = $this->currency->getValue('CNY');
        $price = $amount * $currency_value;
        $price = number_format($price,2,'.','');

        $total_fee = $price*100;
        //必填
        //$body =  $this->language->get('text_owner') . ' ' . $first_name .' '. $last_name;


        $APPID = 'wxa293777bc7b3f981';
        //受理商ID，身份标识
        $MCHID = '1270825001';
        //商户支付密钥Key。审核通过后，在微信发送的邮件中查看
        $KEY = 'afkqorueirdkfadfq187379faldfiti2';
        //JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
        $config = array(
            //公众账号ID
            'APP_ID' =>$APPID,

            //商户ID
            'MCH_ID' => $MCHID,

            //支付密钥
            'KEY' => $KEY,

            //私钥证书地址(通信使用证书才需要配置)
            'sslkey_path' => '',

            //公钥证书地址(通信使用证书才需要配置)
            'sslcert_path' => '',

            //设备ID
            'device_info' => 'WEB',

            //回调地址
            'notify_url' => $notify_url,

            //下单接口
            'unifiedorder_url' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',

            //订单查询接口
            'orderquery_url' => 'https://api.mch.weixin.qq.com/pay/orderquery',

            //订单关闭接口
            'closeorder_url' => 'https://api.mch.weixin.qq.com/pay/closeorder',
        );
        require_once ('wxh5pay/Order.php');
        require_once ('wxh5pay/Lib/Core.php');
        require_once ('wxh5pay/Lib/Sign.php');
        /*
        $config = include_once './config/wxh5pay.php';
        include_once './wxh5pay/Order.php';
        include_once './wxh5pay/lib/Core.php';
        include_once './wxh5pay/lib/Sign.php';*/
        $order = new \wxh5pay\Order($config);
        $ip = $this->get_client_ip();
        $order->setParams([
            'body' => $subject,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee,
            'trade_type' => 'MWEB',
            'device_info' => $config['device_info'],
            'spbill_create_ip' => $ip,
            'notify_url' => $config['notify_url'],
            'scene_info' => '{"h5_info": {"type":"Wap","wap_url": "https://pay.qq.com","wap_name": "腾讯充值"}}'
        ]);
        $res = $order->unifiedorder();
        if(!$res){
            var_dump($order->errMsg);
            $data['html_text'] = '';
        }else{
            $data['html_text'] = "<div class='pull-right'><a class='btn btn-primary' href='".$res."'>点击支付</a></div>";
        }
		$data['redirect'] = $this->url->link('checkout/success');


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wxh5pay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/wxh5pay.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/wxh5pay.tpl', $data);
		}
	}


    public function get_client_ip(){
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) {

            $ip = getenv('HTTP_CLIENT_IP');

        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')) {

            $ip = getenv('HTTP_X_FORWARDED_FOR');

        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'),'unknown')) {

            $ip = getenv('REMOTE_ADDR');

        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {

            $ip = $_SERVER['REMOTE_ADDR'];

        }

        return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    }


	public function notifycallback() {
        $notify_url = HTTPS_SERVER.'catalog/controller/payment/weih5pay_callback.php';
        $APPID = 'wxa293777bc7b3f981';
        //受理商ID，身份标识
        $MCHID = '1270825001';
        //商户支付密钥Key。审核通过后，在微信发送的邮件中查看
        $KEY = 'afkqorueirdkfadfq187379faldfiti2';
        //JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
        $config = array(
            //公众账号ID
            'APP_ID' =>$APPID,

            //商户ID
            'MCH_ID' => $MCHID,

            //支付密钥
            'KEY' => $KEY,

            //私钥证书地址(通信使用证书才需要配置)
            'sslkey_path' => '',

            //公钥证书地址(通信使用证书才需要配置)
            'sslcert_path' => '',

            //设备ID
            'device_info' => 'WEB',

            //回调地址
            'notify_url' => $notify_url,

            //下单接口
            'unifiedorder_url' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',

            //订单查询接口
            'orderquery_url' => 'https://api.mch.weixin.qq.com/pay/orderquery',

            //订单关闭接口
            'closeorder_url' => 'https://api.mch.weixin.qq.com/pay/closeorder',
        );
        require_once ('wxh5pay/Order.php');
        require_once ('wxh5pay/Lib/Core.php');
        require_once ('wxh5pay/Lib/Sign.php');

        $log_name="weixinh5_notify_url.log";//log文件路径
        $log_ = new Log($log_name);
        $log_->write("【进入回调程序】:\n");
        $xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents('php://input');
        $resArray = \wxh5pay\lib\Core::xmlToArray($xml);
        if($resArray['return_code']== 'SUCCESS') {
            //验证签名,判断是否是微信通知
            $sign = $resArray['sign'];
            unset($resArray['sign']);
            $wxSign = \wxh5pay\lib\Sign::makeSign($resArray, $config['KEY']);
            if ($sign == $wxSign) {
                //商户自己的业务
                //此处应该更新一下订单状态，商户自行增删操作
                $log_->write("【支付成功】:\n".$xml."\n");

                $log_->write(print_r($resArray,true));
                //支付成功，进行相关处理
                $order_id = $resArray["out_trade_no"];//获取订单id.
                $log_->write( $order_id." get!!!.\n");
                $this->load->model('checkout/order');
                $order_info = $this->model_checkout_order->getOrder($order_id);
                if ($order_info) {
                    $order_status_id = $order_info["order_status_id"];
                    // 确定订单没有重复支付
                    $config_fraud_status_id = $this->config->get('config_fraud_status_id');
                    if ($order_status_id == $config_fraud_status_id) {
                        $log_->write( $order_id.':'.$this->config->get('weih5pay_order_status_id')." begin Pay.\n");
                        //此处需要设置订单状态为“已付款”或“待处理”，根据具体情况定
                        $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('weih5pay_order_status_id'));
                    }else{
                        $log_->write( $order_id." Order Has bean Payed.\n");
                    }
                }else{
                    $log_->write($order_id."Alipaywap No Order Found.\n");
                }
                //返回微信通知成功
                echo \wxh5pay\lib\Core::arrayToXml([
                    'return_code' => 'SUCCESS',
                    'return_msg' => 'OK'
                ]);

            }
        }
                //以log文件形式记录回调信息

        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======

        $log_->write("【接收到的notify通知】:\n".$xml."\n");


        $log_->write("【退出回调程序】:\n");
		
	}
}
?>