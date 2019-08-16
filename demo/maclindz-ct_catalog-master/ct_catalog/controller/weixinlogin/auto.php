<?php
class ControllerWeixinloginAuto extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/weixin_login');

		$this->load->language('weixinlogin/sel');

		$this->document->setTitle($this->language->get('heading_title'));

		
		$openid = $this->session->data['weixin_openid'];
		if(isset($this->session->data['weixin_nickname'])){
			$firstname = $this->session->data['weixin_nickname'];
		}else{
			$firstname = '';
		}
		//下面进行登陆
		$this->load->model('account/weixin_login');
		if($openid){
			if(!$this->model_account_weixin_login->isOpenidExist($this->session->data['weixin_openid'])) { //当前openid没有账号

				$this->model_account_weixin_login->addWeixinCustomer($openid, $firstname);
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
				$url = $this->session->data['wxredirect'];
				unset($this->session->data['wxredirect']);
				header('Location: ' . $url);
				//$this->response->redirect($this->url->link('common/home', '', 'SSL'));
			}
	  }
	}

}