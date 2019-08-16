<?php
class ControllerWeixinloginIndex extends Controller {
	public function index() {
		$route = '';

		if (isset($this->request->get['route'])) {
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}
		}

		$logger = new Log('weixin.log');
		$logger->write("begin:---------------------------\n");
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && $route != 'weixinlogin' && (!isset($this->request->get['route']) || ($this->request->get['route'] != 'account/bind' && $this->request->get['route'] != 'account/logout'))) {
			if (!$this->customer->isLogged()) {
				$logger->write(print_r($this->request,true));
				if(isset($this->request->get['openid'])) {  //非认证账号通过get传递Openid
					$this->session->data['weixin_openid'] = $this->request->get['openid'];
				} 
				if (!isset($this->session->data['wxredirect'])) {
					//$http = 'http://';//(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off')?'https://':'http://';  
					//$port = $_SERVER["SERVER_PORT"]==80 ? '' : ':' . $_SERVER["SERVER_PORT"];  
					$url = HTTP_SERVER;
					$this->session->data['wxredirect'] = $url;
				}
				if (!isset($this->session->data['weixin_openid'])) {
					$this->weixinhome();
				}
				$this->load->model('account/weixin_login');
				//执行到此已经可以保证有$this->session->data['weixin_openid']的值了
				if($this->model_account_weixin_login->isOpenidExist($this->session->data['weixin_openid'])) { //当前openid已经有账号
					$this->weixinlogin();
					$this->model_account_weixin_login->updateCustomerHeadimgurl($this->session->data['weixin_openid'], $this->session->data['headimgurl']);
					$url = $this->session->data['wxredirect'];
					unset($this->session->data['wxredirect']);
					$logger->write("loging successs:$url");
					header('Location: ' . $url);
				} else {
					//弹出微信登陆选择页面，让用户选择“PC端已注册账号”还是“首次访问本站”。
					$this->response->redirect($this->url->link('weixinlogin/sel', '', 'SSL'));
				}
			}
			$this->session->data['weixinbrower'] = 1;
		}else{
			$this->session->data['weixinbrower'] = 0;
			
		}
	}

	public function weixinhome() {
		include_once("catalog/controller/payment/WxPayPubHelper/WxPayPubHelper.php");
		//设置参数 
		if($this->config->get('weipay_appid')){
			WxPayConf_pub::$APPID = $this->config->get('weipay_appid');
		}
		if($this->config->get('weipay_mchid')){
			WxPayConf_pub::$MCHID = $this->config->get('weipay_mchid');
		}
		if($this->config->get('weipay_key')){
			WxPayConf_pub::$KEY = $this->config->get('weipay_key');
		}
		if($this->config->get('weipay_appsecret')){
			WxPayConf_pub::$APPSECRET = $this->config->get('weipay_appsecret');
		}
		//使用jsapi接口
		$jsApi = new JsApi_pub();
	
		//=========步骤1：网页授权获取用户openid============
		//通过code获得openid
		if (!isset($_GET['code']))
		{
			//触发微信返回code码
			$url = $jsApi->createOauthUrlForCode($this->url->link('login/weixin', '', 'SSL'));
			Header("Location: $url"); 
			exit();
		}else
		{
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$jsApi->setCode($code);
			$openid = $jsApi->getOpenId();
			$userinfo = $jsApi->getUserinfo();
			//preg_replace('~\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]~', '', $userinfo['nickname'])改函数删除昵称中的表情，否则会导致乱码
			$this->session->data['weixin_nickname'] = preg_replace('~\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]~', '', $userinfo['nickname']);
			$this->session->data['headimgurl'] = $userinfo['headimgurl'];
		}
		$this->session->data['weixin_openid'] = $openid;
	}

	/*
	* 如果openid用户存在则登陆，否则不登陆
	* 需在customer表中增加字段openid
	*/
	public function weixinlogin() {
		//如果openid用户存在则登陆，否则不登陆
		$openid = $this->session->data['weixin_openid'];
		$this->load->model('account/weixin_login');
		if($openid){
			$this->customer->logout();
			$this->cart->clear();

			unset($this->session->data['wishlist']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['guest']);
			$this->customer->loginWithOpenid($openid);
	  }
  }
}