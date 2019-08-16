<?php
class ControllerWeixinloginBind extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/weixin_login');

		$this->load->language('weixinlogin/bind');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			unset($this->session->data['guest']);

			// Default Shipping Address
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName()
			);

			$this->model_account_activity->addActivity('login', $activity_data);

			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$url = HTTP_SERVER;
				unset($this->session->data['wxredirect']);
				header('Location: ' . $url);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_new_customer'] = $this->language->get('text_new_customer');
		$data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['text_login'] = $this->language->get('text_login');

		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_password'] = $this->language->get('entry_password');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('weixinlogin/bind', '', 'SSL');
		$data['forgotten'] = $this->url->link('weixin/forgotten', '', 'SSL');

		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);
		} else {
			$data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('weixinlogin/footer');
		$data['header'] = $this->load->controller('weixinlogin/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/weixinlogin/bind.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/weixinlogin/bind.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/weixinlogin/bind.tpl', $data));
		}
	}

	protected function validate() {
		// Check how many login attempts have been made.
		$this->load->model('account/customer');
		// Check if customer has been approved.
		if (preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {  //邮箱
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		} else {  //手机
			$customer_info = $this->model_account_customer->getCustomerByTelephone($this->request->post['email']);
		}

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}
		
		if (!$this->error) {
			// Check how many login attempts have been made.
			$login_info = $this->model_account_customer->getLoginAttempts($customer_info['customer_id']);
	
			if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
				$this->error['warning'] = $this->language->get('error_attempts');
			}

			if (!$this->customer->login($customer_info['email'], $this->request->post['password'])) {
				$this->error['warning'] = $this->language->get('error_login');
			
				$this->model_account_customer->addLoginAttempt($customer_info['customer_id']);
			} else {
				$this->model_account_customer->deleteLoginAttempts($customer_info['customer_id']);
				$this->load->model('account/weixin_login');
				$this->model_account_weixin_login->bindCustomer($customer_info['customer_id'],$this->session->data['weixin_openid']);
			}			
		}
		
		return !$this->error;
	}
}