<?php
/*
	微信扫码支付 二维码付款页面
	QQ群 50415210 群共享有免费插件,请加群查看
	技术支持 30171310@qq.com
	如有其他做站需求,请联系,谢谢.
*/
class Controllerweixinnativepending extends Controller {
	public function index() {
		
		
		//如果session 没有order_id 去重新checkout
		if(isset($this->session->data['order_id'])){
			
			
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			
			$order_id = $this->session->data['order_id'];
			
			
			//商户订单号
        	$out_trade_no = $this->session->data['order_id'];
			
			
			$body = $this->config->get('config_name').' Order:' . $order_id;
			
			$data['order_id'] = $order_id;
			
			 //交易金额，单位分
		 	$total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
			
			
		}else{
			$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));	
		}
		
		
		//加载语言包
		$this->load->language('weixin_native/pending');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');


		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		
		//二维码的生成
		ini_set('date.timezone','Asia/Shanghai');
		//error_reporting(E_ERROR);
		
		
		//appid
		$appid = $this->config->get('weixin_native_appid');
		
		
		//mchid
		$mchid = $this->config->get('weixin_native_mchid');
		
		
		//key
		$key = $this->config->get('weixin_native_key');
		
		
		//
		$SSLCERT_PATH = '../cert/apiclient_cert.pem';
		
		$SSLKEY_PATH = '../cert/apiclient_key.pem';
		
		require_once "lib/WxPay.Api.php";
		require_once "WxPay.NativePay.php";
		require_once 'log.php';
		
		//模式一
		/**
		 * 流程：
		 * 1、组装包含支付信息的url，生成二维码
		 * 2、用户扫描二维码，进行支付
		 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
		 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
		 * 5、支付完成之后，微信服务器会通知支付成功
		 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
		 */
		$notify = new NativePay();
		//$url1 = $notify->GetPrePayUrl("112233445566");
		
		//模式二
		/**
		 * 流程：
		 * 1、调用统一下单，取得code_url，生成二维码
		 * 2、用户扫描二维码，进行支付
		 * 3、支付完成之后，微信服务器会通知支付成功
		 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
		 */
		$input = new WxPayUnifiedOrder();
		$input->SetBody($this->config->get('config_name'));
		$input->SetAttach($this->config->get('config_name'));
		$input->SetOut_trade_no($mchid.date("YmdHis").'-'.$out_trade_no);
		//总价格
		$input->SetTotal_fee($total * 100);
		$input->SetTime_start(date("YmdHis"));
		//$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($this->config->get('config_name'));
		
		//异步通知url
		$input->SetNotify_url(HTTP_SERVER . "weixin_native/notify.php");
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($out_trade_no);
		
		//print_r($input);
		
		$result = $notify->GetPayUrl($input);
		
		//print_r($result);
		
		$url2 = $result["code_url"];
		
		
		//公用连接
		//http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url1);
		//$data['my_url'] = HTTP_SERVER . 'index.php?route=weixin_native/qrcode&data='.urlencode($url1);
		
		
		$data['my_url2'] = HTTP_SERVER . 'index.php?route=weixin_native/qrcode&data='.urlencode($url2);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/weixin_native/pending.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/weixin_native/pending.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/weixin_native/pending.tpl', $data));
		}
	}
	
	//获取订单的实时状态 ,然后目的是 使页面跳转到支付成功页面
	function get_order_status(){
		
		$json = array();
		
		if (isset($this->session->data['order_id'])) {
			$order_id = $this->request->get['order_id'];	
			
			//获取微信支付成功后的订单状态
			$order_status_id = $this->config->get('weixin_native_order_paid_status_id');
			//echo $order_status_id;
			//查看此订单的状态是否为 后台设定的支付成功的订单状态
			$query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order WHERE order_id = '".$order_id."' LIMIT 1 ");
			
			if($query->num_rows){
				if($query->row['order_status_id'] == $order_status_id){
					$json['success'] = 'ok';	
				}else{
					$json['error'] = 'error';		
				}
			}else{
				$json['error'] = 'error';		
			}
			
			
		}else{
			$json['error'] = 'error';	
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}