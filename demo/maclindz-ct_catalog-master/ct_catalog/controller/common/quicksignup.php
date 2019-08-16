<?php
class ControllerCommonQuicksignup extends Controller {
	public function index() {
		$this->load->language('common/quicksignup');

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');

		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_details'] = $this->language->get('text_details');
		$data['text_signin_register'] = $this->language->get('text_signin_register');
		$data['text_new_customer'] = $this->language->get('text_new_customer');
		$data['text_returning'] = $this->language->get('text_returning');
		$data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['button_sendverifycode'] = $this->language->get('button_sendverifycode');
        $data['entry_quick_verify_code'] = $this->language->get('entry_quick_verify_code');
        $data['is_not_number'] = $this->language->get('is_not_number');
		$data['text_weixin'] = $this->language->get('text_weixin');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
        $data['entry_email_phone'] = $this->language->get('entry_email_phone');

        $data['button_sendverifycode'] = $this->language->get('button_sendverifycode');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_comfirm_password'] = $this->language->get('entry_comfirm_password');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['error_password_empty'] = $this->language->get('error_password_empty');
        $data['error_password_notequal'] = $this->language->get('error_password_notequal');
        $data['error_already_registered'] = $this->language->get('error_already_registered');
        $data['tip_message_send_success'] = $this->language->get('tip_message_send_success');
        $data['tip_message_empty'] = $this->language->get('tip_message_empty');


		$data['button_login'] = $this->language->get('button_login');
		$data['button_continue'] = $this->language->get('button_continue');
		
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$data['is_weixin'] = $this->session->data['weixinbrower'];
		$data['weixinlogin'] = $this->url->link('tool/test', '', 'SSL');
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/quicksignup.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/quicksignup.tpl', $data);
		} else {
			return $this->load->view('default/template/common/quicksignup.tpl', $data);
		}
	}
	
	public function register() {
		$json =array();
		$this->language->load('common/quicksignup');
		$this->language->load('account/success');
		$this->load->model('account/customer');
		$this->load->model('catalog/information');
		$this->load->model('account/quicksignup');
		
		if ($this->customer->isLogged()) {
			$json['islogged'] = true;
		}else if(isset($this->request->post)) {
			if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
				$json['error'] = $json['error_name'] = $this->language->get('error_name');
			}

			if(isset($this->request->post['email'])){
                $domain = strstr($this->request->post['email'],'@');
                if(empty($domain)) {
                    $this->request->post['telephone'] = $this->request->post['email'];
                    if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
                        $json['error'] = $json['error_email'] = $this->language->get('error_telephone');
                    }
                    if (utf8_strlen(trim($this->request->post['verify_code'])) !=4) {
                        $json['error'] = $json['error_verify_code'] = $this->language->get('error_verify_code');
                    }else{
                        $this->load->model('account/verify_code');
                        $code = $this->model_account_verify_code->getCodeByCode($this->request->post['verify_code']);
                        if(!isset($this->error['telephone'])&&$code){
                            $phone = $this->request->post['telephone'];
                            $code_phone = $code['phone'];
                            $date_added = $code['date_added'];
                            if($code_phone!=$phone){
                                $json['error'] = $json['error_verify_code'] = $this->language->get('error_verify_code');
                            }else{
                                if((time()-strtotime($date_added))>10*60){
                                    $json['error'] = $json['error_verify_code'] = $this->language->get('error_sns_invalid');
                                }
                            }
                        }
                    }
                }else{
                    if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
                        $json['error'] = $json['error_email'] = $this->language->get('error_email');
                    }
                    if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
                        $json['error'] = $json['error_email'] = $this->language->get('error_exists');
                    }
                }
            }
			
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$json['error'] = $json['error_password'] = $this->language->get('error_password');
			}
			
			if ($this->request->post['password']!=$this->request->post['comfirm']) {
				$json['error'] = $json['error_password'] = $this->language->get('error_password_match');
			}
			
			// Agree to terms
			if ($this->config->get('config_account_id')) {
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
				if ($information_info && !isset($this->request->post['agree'])) {
					$json['error'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
		}else{
			$json['error'] = $this->language->get('error_warning');
		}
		
		if(!$json) {
		
			$this->model_account_quicksignup->addCustomer($this->request->post);
			$json['success'] = true;
			$this->customer->login($this->request->post['email'], $this->request->post['password']);

			unset($this->session->data['guest']);

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->request->post['name']
			);

			if(isset($this->session->data['force_redirect'])){
				$json['redirect'] = $this->session->data['force_redirect'];
				unset($this->session->data['force_redirect']);
			}
			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);
			setcookie("customer_id_cookie", $activity_data['customer_id'], time() + (10 * 365 * 24 * 60 * 60), "/");

			$this->model_account_activity->addActivity('register', $activity_data);
			
			if ($this->customer->isLogged()) {
				$json['now_login'] = true;
			}
			
			
			$json['heading_title'] = $this->language->get('heading_title');

			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->config->get('config_customer_group_id'));

			if ($customer_group_info && !$customer_group_info['approval']) {
				$json['text_message'] = sprintf($this->language->get('text_message'), $this->config->get('config_name'), $this->url->link('information/contact'));
			} else {
				$json['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), $this->url->link('information/contact'));
			}
			
			if ($this->cart->hasProducts()) {
				$json['continue'] = $this->url->link('checkout/cart');
			} else {
				$json['continue'] = $this->url->link('account/account', '', 'SSL');
			}

			$json['button_continue'] = $this->language->get('button_continue');
			
		}
		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function login() {
		
		$json =array();
		$this->language->load('common/quicksignup');
		$this->load->model('account/customer');
		
		if ($this->customer->isLogged()) {
			$json['islogged'] = true;
		}else if(isset($this->request->post)) {
			if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				$json['error'] = $this->language->get('error_login');
			}
			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);
			setcookie("customer_id_cookie", $activity_data['customer_id'], time() + (10 * 365 * 24 * 60 * 60), "/");
			
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
			if ($customer_info && !$customer_info['approved']) {
				$json['error'] = $this->language->get('error_approved');
			}
		}else{
			$json['error'] = $this->language->get('error_warning');
		}
		
		if(!$json) {
			$json['success'] = true;
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
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('login', $activity_data);
		}
		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}