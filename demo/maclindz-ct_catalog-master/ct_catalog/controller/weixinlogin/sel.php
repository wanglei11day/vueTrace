<?php
class ControllerWeixinloginSel extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/weixin_login');

		$this->load->language('weixinlogin/sel');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['operate'])) {
			if ($this->request->get['operate'] == "new") {
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
						header('Location: ' . $this->url->link('common/home', '', 'SSL'));
						//$this->response->redirect($this->url->link('common/home', '', 'SSL'));
					}
			  }
			} else {  //$this->request->get['operate'] == "old" //跳转到账号绑定页面（功能相当于登陆页面）
				$this->response->redirect($this->url->link('weixinlogin/bind', '', 'SSL'));
			}
		}

		$data['action_old'] = $this->url->link('weixinlogin/sel', 'operate=old', 'SSL');
		$data['action_new'] = $this->url->link('weixinlogin/sel', 'operate=new', 'SSL');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_new_customer'] = $this->language->get('button_new_customer');
		$data['button_old_customer'] = $this->language->get('button_old_customer');

		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('weixinlogin/footer');
		$data['header'] = $this->load->controller('weixinlogin/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/weixinlogin/sel.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/weixinlogin/sel.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/weixinlogin/sel.tpl', $data));
		}
	}

}