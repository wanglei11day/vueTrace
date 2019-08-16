<?php
class ControllerAffiliateInfo extends Controller {
	private $error = array();

	public function index() {
		if(!$this->config->get('account_combine_status')) $this->response->redirect($this->url->link('affiliate/payment', '', 'SSL'));
		$L = true;
		if (!$this->affiliate->isLogged()) {
			if(!isset($this->request->get['accc']) || $this->request->get['accc'] !== 'accc' || !$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('affiliate/info', '', 'SSL');
				$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'));
			} 
			$L = false;
		}
		
		$fields = $this->config->get('account_combine_fields');
		$rform = $this->config->get('account_combine_rform');
		$cform = $this->config->get('account_combine_cform');
		$payment_method_fields = $this->config->get('account_combine__payment_method_fields');
		$data['aff_fields'] = $this->config->get('account_combine__fields');
		$data['payment_methods'] = $this->config->get('account_combine__payment_methods');

		$data['website_textarea'] = $rform['website_textarea'] || $cform['website_textarea'];
		
		$this->language->load('affiliate/account_combine_info');
		
		if(isset($this->session->data['accc_force_required'])) {
			unset($this->session->data['accc_force_required']);
			$data['error_warning'] = $this->language->get('error_force_required');
		} else {
			$data['error_warning'] = false;
		}

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('affiliate/affiliate');
		$this->load->model('affiliate/account_combine');
		
		if ($L && $this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->model_affiliate_account_combine->editAffiliate(array('website' => $this->request->post['website']));
			$this->model_affiliate_affiliate->editPayment($this->request->post);
			
			if($this->config->get('tracking_input_status') && isset($this->request->post['tracking_input'])) $this->model_affiliate_affiliate->editCode($this->request->post['tracking_input']);//+mod by yp tracking input
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('affiliate/account', '', 'SSL'));
		}

      	$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),     	
        	'separator' => false
      	); 

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('affiliate/account', '', 'SSL'),        	
        	'separator' => $this->language->get('text_separator')
      	);

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_info'),
			'href'      => $this->url->link('affiliate/info', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_your_info'] = $this->language->get('text_your_info');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (!$L && ($rform['agreement'] || $cform['agreement'])) {			
			
			$information_info = $this->model_affiliate_account_combine->getAgreement();
			
			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_affiliate_id'), 'SSL'), $information_info['title'], $information_info['title']);
				$data['error_agree'] = str_replace(array("\r\n", "\r", "\n"), array('\\n', '\\n', '\\n'), addslashes(html_entity_decode(sprintf($this->language->get('error_agree'), $information_info['title']), ENT_QUOTES, 'UTF-8')));
			} else {
				$data['text_agree'] = '';
				$data['error_agree'] = false;
			}			
		} else {
			$data['error_agree'] = false;
			$data['text_agree'] = '';
		}
		
		$data['action'] = ($L ? $this->url->link('affiliate/info', '', 'SSL') : $this->url->link('affiliate/account_combine', ($data['text_agree'] ? '' : 'confirm=1'), 'SSL'));
		
		if ($L && $this->request->server['REQUEST_METHOD'] != 'POST') {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());		
		} else {
			$affiliate_info = array();
		}
		
		$data['errors'] = array();
		foreach($data['aff_fields'] as $field) {
			$data[$field] = ($field == 'payment' ? $fields['payment_default'] : '');
			if($fields[$field] == 'n') {
				$data['entry_' . $field] = '';
				$data['use_' . $field] = false;				
				$data[$field . '_required'] = false;
				$data['error_' . $field] = '';
				$data['errors'][$field] = '';				
			} else {
				$data['entry_' . $field] = $this->language->get('entry_' . $field);
				$data['use_' . $field] = true;
				if (isset($this->request->post[$field])) {
					$data[$field] = $this->request->post[$field];
				} else if (isset($affiliate_info[$field])) {
					if ($field == 'payment' && (!isset($fields['use_' . $affiliate_info[$field]]) || !$fields['use_' . $affiliate_info[$field]])) {
						$data[$field] = $fields['payment_default'];
					} else {
						$data[$field] = $affiliate_info[$field];
					}
				}				
				$data[$field . '_required'] = ($fields[$field] == 'r');
				$data['error_' . $field] = $this->language->get('error_' . $field);
				$data['errors'][$field] = (isset($this->error[$field]));			
			}		
		}

		foreach($data['payment_methods'] as $method) {
			if($fields['payment'] == 'n') $fields['use_' . $method] = false;
			if($fields['use_' . $method]) {
				$data['use_' . $method] = true;
				$data['text_' . $method] = $this->language->get('text_' . $method);
				if(isset($fields[$method]) && !$fields[$method]) continue;
				if(!isset($fields[$method]) || !is_array($fields[$method])) $fields[$method] = array('_' => 1);
				foreach($fields[$method] as $mfield => $_use) {					
					$mfield = $method . ($mfield == '_' ? '' : '_' . $mfield);
					if(!$_use) {
						$data['use_' . $mfield] = false;
						continue;
					}
					$data['use_' . $mfield] = true;
					$data[$mfield . '_required'] = $data['payment_required'];
					$data['entry_' . $mfield] = $this->language->get('entry_' . $mfield);
					if (isset($this->request->post[$mfield])) {
						$data[$mfield] = $this->request->post[$mfield];
					} else if (isset($affiliate_info[$mfield])) {
						$data[$mfield] = $affiliate_info[$mfield];		
					} else {
						$data[$mfield] = '';
					}
					$data['error_' . $mfield] = $this->language->get('error_' . $mfield);
					$data['errors'][$mfield] = (isset($this->error[$mfield]));
				}			
			} else {
				$data['use_' . $method] = false;				
			}		
		}		
		
		if (isset($this->request->post['confirm'])) {
      		$data['agree'] = $this->request->post['confirm'];
		} else {
			$data['agree'] = false;
		}		
		
		$data['back'] = $this->url->link('affiliate/account', '', 'SSL');
		
		//+mod by yp tracking input start
		if ($this->config->get('tracking_input_status') && ($this->config->get('tracking_input_edit_code') || (($this->config->get('tracking_input_choose_code') || $this->config->get('tracking_input_choose_code_checkout')) && (!$L || !isset($affiliate_info['code']) || strlen($affiliate_info['code']) < 1 || (preg_match("/^[a-f0-9]{" . strlen(uniqid()) . "}$/", $affiliate_info['code'])))))) {
			if(isset($this->request->post['tracking_input'])) {
				$_tiv = $this->request->post['tracking_input'];
			} else if(isset($affiliate_info['code'])) {
				if(($this->config->get('tracking_input_choose_code') || $this->config->get('tracking_input_choose_code_checkout')) && $L && preg_match("/^[a-f0-9]{" . strlen(uniqid()) . "}$/", $affiliate_info['code'])) {
					$_tiv = '';
				} else {
					$_tiv = $affiliate_info['code'];
				}
			} else {
				$_tiv = '';
			}
			$data['tracking_input_show'] = true;
			$data['tracking_input_settings_json'] = array(
				'accc' => true,
				'tracking_input_value' => $_tiv,
				'error' => ''
			);
			if(isset($this->error['tracking_input'])) $data['tracking_input_settings_json']['error'] = is_array($this->error['tracking_input']) ? implode('<br />', $this->error['tracking_input']) : $this->error['tracking_input'];			
			$this->language->load('affiliate/tracking_input');
			foreach(array('exists', 'invalid', 'long', 'required') as $_v) {
				$data['tracking_input_settings_json']['error_' . $_v] = $this->language->get('error_tracking_code_' . $_v);
			}
			$data['tracking_input_settings_json']['required'] = $this->config->get('tracking_input_require_code') ? 1 : 0;			
			$data['tracking_input_settings_json']['entry'] = $this->language->get('entry_tracking_input');
			$data['tracking_input_settings_json']['link_check'] = html_entity_decode($this->url->link('affiliate/tracking_input/check_tracking', '', 'SSL'), ENT_QUOTES, 'UTF-8');
			$data['tracking_input_settings_json'] = json_encode($data['tracking_input_settings_json']);			
			$this->document->addScript('catalog/view/javascript/trifyp.min.js');
		} else {
			$data['tracking_input_show'] = false;
		}
		//+mod by yp tracking input end

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/account_combine_info.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/affiliate/account_combine_info.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/affiliate/account_combine_info.tpl', $data));
		}
		
	}
	
	protected function validate() {

		//+mod by yp tracking input start
		if ($this->config->get('tracking_input_status') && isset($this->request->post['tracking_input'])) {
			if($this->affiliate->isLogged() && preg_match("/^[a-f0-9]{" . strlen(uniqid()) . "}$/", $this->request->post['tracking_input']) && $this->request->post['tracking_input'] == $this->affiliate->getCode()) $this->request->post['tracking_input'] = '';
			$this->language->load('affiliate/tracking_input');
			$_er = array();
			if(strlen($this->request->post['tracking_input']) < 1) {
				if($this->config->get('tracking_input_require_code')) $_er[] = $this->language->get('error_tracking_code_required');
			} else {
				if(!preg_match("/^[\w\-]+$/", $this->request->post['tracking_input'])) $_er[] = $this->_jsstr($this->language->get('error_tracking_code_invalid'));
				if(strlen($this->request->post['tracking_input']) > 20) $_er[] = $this->language->get('error_tracking_code_long');
				if(sizeof($_er) < 1) {
					$this->load->model('affiliate/affiliate');
					if((!$this->affiliate->isLogged() || $this->affiliate->getCode() != $this->request->post['tracking_input']) && $this->model_affiliate_affiliate->getAffiliateByCode($this->request->post['tracking_input']))  $_er[] = $this->language->get('error_tracking_code_exists');
				}
				if(sizeof($_er) < 1 && $this->config->get('tracking_input_allow_coupon')) {
					$_mpfx = version_compare(VERSION, '2.1', '<') ? 'checkout' : 'total';
					$this->load->model($_mpfx . '/coupon');
					if($this->{'model_' . $_mpfx . '_coupon'}->getCoupon($this->request->post['tracking_input'])) $_er[] = $this->language->get('error_tracking_code_exists');
				}				
			}
			if(sizeof($_er) > 0) $this->error['tracking_input'] = sizeof($_er) > 1 ? $_er : $_er[0];			
		}
		//+mod by yp tracking input end	
	
		$fields = $this->config->get('account_combine_fields');
		if(!isset($data['aff_fields'])) $data['aff_fields'] = $this->config->get('account_combine__fields');
		foreach($data['aff_fields'] as $field) {
			if(isset($this->request->post[$field])) $this->request->post[$field] = trim($this->request->post[$field]);
			if($fields[$field] == 'r' && (!isset($this->request->post[$field]) || utf8_strlen($this->request->post[$field]) < 1)) $this->error[$field] = true;
		}
		if($fields['payment'] == 'r') {
			$method = $this->request->post['payment'];
			if(!isset($fields['use_' . $method]) || !$fields['use_' . $method]) {
				$this->error['payment'] = true;
			} else {				
				if(!isset($fields[$method]) || ($fields[$method] && !is_array($fields[$method]))) $fields[$method] = array('_' => 1);
				foreach($fields[$method] as $mfield => $_use) {					
					$mfield = $method . ($mfield == '_' ? '' : '_' . $mfield);
					if(!$_use) continue;
					if(isset($this->request->post[$mfield])) $this->request->post[$mfield] = trim($this->request->post[$mfield]);
					if(!isset($this->request->post[$mfield]) || utf8_strlen($this->request->post[$mfield]) < 1) $this->error[$mfield] = true;					
				}
			}		
		}
		if ($this->error) {
			return false;
		} else {
			return true;
		}	
	}
	
}

