<?php

class ControllerAffiliateAccountCombine extends Controller {
	public function index() {		
		if(!$this->config->get('account_combine_status') || $this->affiliate->isLogged()) $this->response->redirect($this->url->link('affiliate/account', '', 'SSL'));
		$customer_id = $this->customer->isLogged();		
		if(!$customer_id) {
			$this->session->data['redirect'] = $this->url->link('affiliate/account', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));		
		}
		$this->load->model('affiliate/account_combine');
		$account_status = $this->model_affiliate_account_combine->getAccountStatus($customer_id);
		if($account_status === true && $this->affiliate->login('', '', $this->customer->getId())) $this->response->redirect($this->url->link('affiliate/account', '', 'SSL'));
		$this->language->load('affiliate/account_combine');
		if($account_status === 'no_account') {			
			$data['text_create_affiliate_account_confirm'] = $this->language->get('text_create_affiliate_account_confirm');
			if(isset($this->request->request['confirm']) || !$data['text_create_affiliate_account_confirm']) {
				if($this->model_affiliate_account_combine->forceRequired($this->request->post)) {
					$this->session->data['accc_force_required'] = 1;
					$this->response->redirect($this->url->link('affiliate/info', 'accc=accc', 'SSL'));
				}
				$_add_fields = isset($this->request->post) && is_array($this->request->post) ? $this->request->post : array();
				$affiliate_id = $this->model_affiliate_account_combine->addAffiliateFromCustomer($customer_id, $_add_fields);
				if(!$affiliate_id) $this->response->redirect($this->url->link('account/account', '', 'SSL'));
				if($this->config->get('config_affiliate_approval')) {
					$this->response->redirect($this->affiliate->login('', '', $customer_id) ? $this->url->link('affiliate/account', '', 'SSL') : $this->url->link('affiliate/logout', '', 'SSL'));
				}
				$account_status = 'not_approved';
			}
		}
		
		switch($account_status) {
			case 'no_account':
				$data['heading_title'] = $this->language->get('heading_title_create_affiliate_account');
				$data['text_message'] = '
<script language="javascript">
<!--
	$(document).ready(function() {
		if(confirm("' . addcslashes(preg_replace("/[\n\r\s]+/", ' ', strip_tags($data['text_create_affiliate_account_confirm'])), '"') . '")) {
			document.location.href = \'' . html_entity_decode($this->url->link('affiliate/account_combine', 'confirm=1', 'SSL'), ENT_QUOTES, 'UTF-8') . '\';
		} else {
			document.location.href = \'' . html_entity_decode($this->url->link('account/account', '', 'SSL'), ENT_QUOTES, 'UTF-8') . '\';	
		}
	});
//-->
</script>		
				';
				break;
			case 'disabled':
				$data['heading_title'] = $this->language->get('heading_title_account_disabled');
				$data['text_message'] = sprintf($this->language->get('text_account_disabled'), $this->url->link('information/contact'));
				break;
			default:
				$data['heading_title'] = $this->language->get('heading_title_account_not_approved');
				$data['text_message'] = sprintf($this->language->get('text_account_not_approved'), $this->config->get('config_name'), $this->url->link('information/contact'));	
				break;		
		}
		////////////////////////////////
    	$this->document->setTitle($data['heading_title']);

		$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	); 

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$data['button_continue'] = $this->language->get('button_continue');
		
		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}	
	}	
	
	public function transaction() {
		if(!$this->config->get('account_combine_status') || !$this->config->get('account_combine_allow_funds_transfer') || !($affiliate_id = $this->affiliate->isLogged()) || !($customer_id = $this->customer->isLogged()) || !isset($this->request->post['amount'])) $this->response->redirect($this->url->link('affiliate/account', '', 'SSL'));
		$this->language->load('affiliate/account_combine');
		$amount = str_replace(',', '.', preg_replace("/[\n\r\s]+/", '', trim($this->request->post['amount'])));		
		
		if(preg_match("/^\d+(?:\.\d+)?$/", $amount)) {
			if($this->currency->getCode() != $this->config->get('config_currency')) $amount = $this->currency->convert($amount, $this->currency->getCode(), $this->config->get('config_currency'));			
			$this->load->model('affiliate/transaction');
			if(($amount - $this->model_affiliate_transaction->getBalance()) < 0.01) {
				if(abs($amount - $this->model_affiliate_transaction->getBalance()) < 0.01) $amount = $this->model_affiliate_transaction->getBalance();
				$aff_amount = round(floatval($amount) * 10000) * 0.0001;
				$_m = $this->config->get('account_combine_transfer_multiplier') ? $this->config->get('account_combine_transfer_multiplier') : 1;
				$cust_amount = round(floatval($aff_amount * $_m) * 10000) * 0.0001;
				$this->load->model('affiliate/account_combine');		
				$this->model_affiliate_account_combine->transferEarningsToCustomer($affiliate_id, $customer_id, $aff_amount, $cust_amount, $this->language->get('text_affiliate_transaction_description'), $this->language->get('text_customer_transaction_description'));		
				$this->response->redirect($this->url->link('account/transaction', '', 'SSL'));
			}
		}
		$data['error_invalid_transfer_amount'] = $this->language->get('error_invalid_transfer_amount');
		$data['error_invalid_transfer_amount_js'] = addcslashes(str_replace(array("\r\n", "\n", "\r"), array(' ', ' ', ' '), strip_tags(html_entity_decode($data['error_invalid_transfer_amount'], ENT_COMPAT))), "'");		
		$data['text_transactions'] = $this->language->get('text_transactions');

		$this->document->setTitle($data['text_transactions']);
		$data['heading_title'] = $data['text_transactions'];
		$data['text_message'] = '
<script language="javascript">
<!--
	$(document).ready(function() {
		alert(\'' . $data['error_invalid_transfer_amount_js'] . '\');
		document.location.href = \'' . html_entity_decode($this->url->link('affiliate/transaction', '', 'SSL'), ENT_QUOTES, 'UTF-8') . '\';
	});
//-->
</script>		
';
		$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	); 

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_transactions'),
			'href'      => $this->url->link('affiliate/transaction', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$data['button_continue'] = $this->language->get('button_continue');
		
		$data['continue'] = $this->url->link('affiliate/transaction', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}
	
	public function eventAutoCredit($affiliate_transaction_id) {
		if(!$this->config->get('account_combine_status')) return;
		$this->load->model('affiliate/account_combine');
		if(!$this->model_affiliate_account_combine->autoCredit()) return;
		$t = $this->model_affiliate_account_combine->getTransaction($affiliate_transaction_id);
		if(!$t || $t['amount'] <= 0) return;
		$customer_id = $this->model_affiliate_account_combine->getCustomerIdByAffiliateId($t['affiliate_id']);
		if(!$customer_id) return;
		$this->language->load('affiliate/account_combine');
		$_m = $this->config->get('account_combine_transfer_multiplier') ? $this->config->get('account_combine_transfer_multiplier') : 1;
		$cust_amount = round(floatval($t['amount'] * $_m) * 10000) * 0.0001;
		$this->model_affiliate_account_combine->transferEarningsToCustomer($t['affiliate_id'], $customer_id, $t['amount'], $cust_amount, $this->language->get('text_affiliate_transaction_description'), $this->language->get('text_customer_transaction_description'));			
	}

}

