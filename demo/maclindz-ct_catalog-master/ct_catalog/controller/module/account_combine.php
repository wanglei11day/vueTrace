<?php

class ControllerModuleAccountCombine extends Controller {
	public function index() {
		if(!$this->config->get('account_combine_status')) return $this->load->controller('module/account');
		$this->language->load('module/account');
    	$data['heading_title'] = $this->language->get('heading_title');
    	
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_reward'] = $this->language->get('text_reward');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_recurring'] = $this->language->get('text_recurring');

		$data['logged'] = $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['edit'] = $this->url->link('account/edit', '', 'SSL');
		$data['password'] = $this->url->link('account/password', '', 'SSL');
		$data['address'] = $this->url->link('account/address', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['reward'] = $this->url->link('account/reward', '', 'SSL');
		$data['return'] = $this->url->link('account/return', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
		$data['recurring'] = $this->url->link('account/recurring', '', 'SSL');		
		
		$this->load->model('account/recurring');
		if($this->model_account_recurring->getTotalRecurring()) {
			$data['text_recurring'] = $this->language->get('text_recurring');
			$data['recurring'] = $this->url->link('account/recurring', '', 'SSL');
		} else {
			$data['recurring'] = false;
		}		
		
		$data['logged'] = $customer_id = $this->customer->isLogged();
		if(!$data['logged']) {
			$data['text_link_to_affiliate'] = false;
			$data['affiliate_logged'] = false;
		} else {	
			$this->language->load('affiliate/account_combine');		
			if($this->affiliate->isLogged()) {
				$data['text_link_to_affiliate'] = false;
				$data['affiliate_logged'] = true;
				
				$data['affiliate_account'] = $this->url->link('affiliate/account', '', 'SSL');
				$data['affiliate_info'] = $this->url->link('affiliate/info', '', 'SSL');
				$data['affiliate_transaction'] = $this->url->link('affiliate/transaction', '', 'SSL');
				$data['affiliate_tracking'] = $this->url->link('affiliate/tracking', '', 'SSL');

				$data['text_affiliate_account'] = $this->language->get('text_affiliate_account');
				$data['text_affiliate_info'] = $this->language->get('text_affiliate_info');
				$data['text_affiliate_tracking'] = $this->language->get('text_affiliate_tracking');
				$data['text_affiliate_transaction'] = $this->language->get('text_affiliate_transaction');
			} else {				
				$data['affiliate_logged'] = false;
				$this->load->model('affiliate/account_combine');
				$_aff_status = $this->model_affiliate_account_combine->getAccountStatus($customer_id);
				if($_aff_status === true) {
					$data['text_link_to_affiliate'] = $this->language->get('text_link_to_affiliate_in_customer');
				} else {
					$_map = array(
						'no_account' => 'text_link_to_create_affiliate_in_customer',
						'not_approved' => 'text_link_to_affiliate_not_approved_in_customer',
						'disabled' => 'text_link_to_affiliate_disabled_in_customer'
					);
					$data['text_link_to_affiliate'] = isset($_map[$_aff_status]) ? $this->language->get($_map[$_aff_status]) : '';
				}
				$data['link_to_affiliate'] = $this->url->link('affiliate/account_combine', '', 'SSL');
			}
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/account_combine.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/account_combine.tpl', $data);
		} else {
			return $this->load->view('default/template/module/account_combine.tpl', $data);
		}
	}
	
}

