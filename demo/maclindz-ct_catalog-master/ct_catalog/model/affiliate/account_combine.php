<?php

class ModelAffiliateAccountCombine extends Model {

	private $_mta = -1;
	private $_agreement = -1;

	public function addAffiliateFromCustomer($customer_id, $add_fields=array()) {
		if(!$this->config->get('account_combine_status')) return;		
		$this->load->model('account/customer');
		$data = $this->model_account_customer->getCustomer($customer_id);
		if(!$data || !isset($data['email'])) return false;
		$_res = $this->db->query("SELECT affiliate_id FROM " . DB_PREFIX . "affiliate WHERE email = '" . $this->db->escape($data['email']) . "'");
		if($_res->num_rows > 0) return false;
		$this->load->model('account/address');
		if($this->customer->isLogged()) {
			$_addr = $this->model_account_address->getAddress($data['address_id']);
		} else {
			$_res = $this->db->query("select company, address_1, address_2, city, postcode, country_id, zone_id from `" . DB_PREFIX . "address` where address_id = '" . (int)$data['address_id'] . "'");
			$_addr = $_res->row;
		}
		foreach(array('company', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id') as $_f) {
			$_def = $_f === 'country_id' || $_f === 'zone_id' ? 0 : '';
			$data[$_f] = isset($_addr[$_f]) ? $_addr[$_f] : $_def;
		}	
		foreach(array('website', 'tax', 'payment', 'cheque', 'paypal', 'bank_name', 'bank_branch_number', 'bank_swift_code', 'bank_account_name', 'bank_account_number') as $_f) {
			if(!isset($add_fields[$_f])) $add_fields[$_f] = '';
		}
		$data = array_merge($add_fields, $data);
		if(!$data['payment']) $data['payment'] = 'cheque';
		$this->load->model('affiliate/affiliate');
		$this->request->post['email'] = $data['email'];
		$this->model_affiliate_affiliate->addAffiliate($data);
		$_res = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE email = '" . $this->db->escape($data['email']) . "'");
		if($_res->num_rows < 1) return false;
		$affiliate = $_res->row;
		 $this->db->query("INSERT INTO " . DB_PREFIX . "accc_customer_affiliate (customer_id, affiliate_id) values ('" . (int) $customer_id . "', '" . (int) $affiliate['affiliate_id'] . "')");
		$this->db->query("UPDATE " . DB_PREFIX . "affiliate set password='" . $this->db->escape($data['password']) . "', salt='" . $this->db->escape($data['salt']) . "' where affiliate_id='" . (int) $affiliate['affiliate_id'] . "'");		 
		if($this->config->get('config_affiliate_approval')) $this->approve($affiliate['affiliate_id']);
		//for mta start

		if($this->config->get('account_combine_own_parent')) {
			if(isset($this->request->cookie['tracking'])) setcookie('tracking', '', time() - 3600, '/');
			setcookie('tracking', $affiliate['code'], time() + 3600 * 24 * 1000, '/');
		}
		
		/*if($this->_has_mta()) {
			$this->load->model('affiliate/mta_affiliate');
			$this->model_affiliate_mta_affiliate->addAffiliate($affiliate['affiliate_id'], $this->_mta_find_parent($data['email']), (isset($this->request->cookie['mta']) ? $this->request->cookie['mta'] : ''));
			if($this->config->get('account_combine_own_parent')) $this->model_affiliate_mta_affiliate->setCustomerAffiliateId($customer_id, $affiliate['affiliate_id'], true);
		}*/
		if($this->_has_mta() && $this->config->get('account_combine_own_parent')) {
			$this->load->model('affiliate/mta_affiliate');
			$this->model_affiliate_mta_affiliate->setCustomerAffiliateId($customer_id, $affiliate['affiliate_id'], true);
		}
		//for mta end
		if($this->config->get('account_combine_customer_group_id')) $this->db->query("update " . DB_PREFIX . "customer set customer_group_id = '" . (intval($this->config->get('account_combine_customer_group_id'))) . "' where customer_id = '" . (int)$customer_id . "'");
		return $affiliate['affiliate_id'];
	}

	public function editAffiliate($data) {
		$customer_id = $this->customer->getId();
		if(!$customer_id) return;
		$affiliate_id = $this->affiliate->getId();
		if(!$affiliate_id) return;		
		$aff = $this->getAffiliateByCustomerId($customer_id);
		if(!$aff || $aff['affiliate_id'] != $affiliate_id) return;
		$changed = false;
		foreach($data as $k => $v) {
			if(isset($aff[$k]) && $aff[$k] != $v)  {
				if($changed === false) $changed = true;				
				$aff[$k] = $v;
			}
		}
		if($changed === true) {
			$this->load->model('affiliate/affiliate');
			$this->model_affiliate_affiliate->editAffiliate($aff);			
		}		
	}
	
	public function getAccountStatus($customer_id) {
		if(!$this->config->get('account_combine_status')) return false;
		$aff = $this->getAffiliateByCustomerId($customer_id, 'include_disabled');
		if(!$aff) return 'no_account';
		if(!$aff['status']) return 'disabled';
		if(!$aff['approved']) return 'not_approved';	
		return true;
	}
	
	public function getAffiliateByCustomerId($customer_id, $include_disabled=false) {
		if(!$this->config->get('account_combine_status')) return false;
		$_q = "SELECT " .  DB_PREFIX . "affiliate.* FROM " .  DB_PREFIX . "affiliate,  " .  DB_PREFIX . "accc_customer_affiliate WHERE " .  DB_PREFIX . "affiliate.affiliate_id = " .  DB_PREFIX . "accc_customer_affiliate.affiliate_id AND " .  DB_PREFIX . "accc_customer_affiliate.customer_id = '" . (int) $customer_id . "'";
		if(!$include_disabled) $_q .= " AND " .  DB_PREFIX . "affiliate.status = '1' AND " .  DB_PREFIX . "affiliate.approved = '1'";
		$res = $this->db->query($_q);		
		if($res->num_rows < 1) return false;
		return $res->row;
	}
	
	public function getCustomerIdByAffiliateId($affiliate_id) {
		if(!$this->config->get('account_combine_status')) return false;
		$_res = $this->db->query("select customer_id from " . DB_PREFIX . "accc_customer_affiliate where affiliate_id = '" . (int)$affiliate_id . "'");
		if($_res->num_rows < 1) return false;
		return $_res->row['customer_id'];
	}
	
	public function getTransaction($affiliate_transaction_id) {
		$_res = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_transaction_id = '" . (int)$affiliate_transaction_id . "'");
		return $_res->row;
	}
	
	public function approve($affiliate_id) {
		if(!$this->config->get('account_combine_status')) return;
		$this->load->model('affiliate/affiliate');
		$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($affiliate_id);			
		if ($affiliate_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "affiliate SET approved = '1' WHERE affiliate_id = '" . (int)$affiliate_id . "'");
			
			$this->language->load('affiliate/account_combine');
	
			$message  = sprintf($this->language->get('text_approve_welcome'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= HTTP_SERVER . 'index.php?route=affiliate/login' . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= $this->config->get('config_name');
	
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');							
			$mail->setTo($affiliate_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}
	
	public function autoCredit() {
		if (!$this->config->get('account_combine_status') || !$this->config->get('account_combine_auto_credit') || !$this->config->get('account_combine_allow_funds_transfer')) return false;
		$fields = $this->config->get('account_combine_fields');
		if(!isset($fields['payment']) || $fields['payment'] != 'n') return false;
		return true;
	}
	
	public function transferEarningsToCustomer($affiliate_id, $customer_id, $aff_amount, $cust_amount, $affiliate_transaction_description, $customer_transaction_description) {
		if($aff_amount > 0) $aff_amount = -$aff_amount;
		if($cust_amount < 0) $cust_amount = -$cust_amount;
		$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_transaction SET affiliate_id = '" . (int)$affiliate_id . "', order_id = '0', description = '" . $this->db->escape($affiliate_transaction_description) . "', amount = '" . $aff_amount . "', date_added = NOW()");
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '0', description = '" . $this->db->escape($customer_transaction_description) . "', amount = '" . $cust_amount . "', date_added = NOW()");		
	}

	private function _has_mta() {
		if($this->_mta === -1) {
			$this->_mta = file_exists('vqmod/xml/multi_tier_affiliate_system.xml') && file_exists(DIR_APPLICATION . 'model/affiliate/mta_affiliate.php') ? true : false;
			if(!$this->_mta) {
				$this->load->model('setting/setting');
				$_s = $this->model_setting_setting->getSetting('mta_ypx');
				$this->_mta = $_s && defined('MTA_YPX_HAS_MTA') && MTA_YPX_HAS_MTA ? true : false;
			}
		}
		return $this->_mta;
	}
	
	private function _mta_find_parent($email) {
		$this->load->model('affiliate/mta_affiliate');
		if (isset($this->request->cookie['tracking'])) {
			$this->load->model('affiliate/affiliate');				
			$res = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
			if($res && $this->model_affiliate_mta_affiliate->check_aff_active($res['affiliate_id'])) return $res['affiliate_id'];
		}			
		if ($this->customer->isLogged()) {
			$_aff_id = $this->model_affiliate_mta_affiliate->getCustomerAffiliateId($this->customer->getId(), 'active_only');
			if($_aff_id) return $_aff_id;
		}			
		return $this->model_affiliate_mta_affiliate->findParentByEmail($email, 'active_only');
		return false;				
	}		
	
	public function forceRequired($aff = false) {
		if(!$this->config->get('account_combine_force_required')) return false;
		if($this->config->get('tracking_input_status')) {
			if($aff === false) {
				$this->load->model('affiliate/affiliate');		
				$aff = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
				$aff['tracking_input'] = $aff['code'];
			}		
			if(is_array($aff) && isset($aff['tracking_input']) && strlen($aff['tracking_input']) > 0 && (strlen($aff['tracking_input']) > 20 || !preg_match("/^[\w\-]+$/", $aff['tracking_input']))) {
				return true;
			} else if ($this->config->get('tracking_input_require_code') && (!is_array($aff) || !isset($aff['tracking_input']) || strlen($aff['tracking_input']) < 1)) {
				return true;
			}
		}
		$fields = $this->config->get('account_combine_fields');
		if($fields['tax'] != 'r' && $fields['website'] != 'r' && $fields['payment'] != 'r') return false;
		if($aff === false) {
			$this->load->model('affiliate/affiliate');		
			$aff = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
		}
		if($fields['website'] == 'r' && (!isset($aff['website']) || strlen($aff['website']) < 1)) return true;
		if($fields['tax'] == 'r' && (!isset($aff['tax']) || strlen($aff['tax']) < 1)) return true;
		if($fields['payment'] == 'r') {			
			if(!isset($aff['payment']) || strlen($aff['payment']) < 1) return true;			
			if(isset($aff[$aff['payment']]) && strlen($aff[$aff['payment']]) < 1) return true;
			if(isset($fields[$aff['payment']])) {
				foreach($fields[$aff['payment']] as $k => $v) {					
					if($v && isset($aff[$aff['payment'] . '_' . $k]) && strlen($aff[$aff['payment'] . '_' . $k]) < 1) return true;				
				}			
			}		
		}		
		return false;
	}
	
	public function getAgreement() {
		if($this->_agreement !== -1) return $this->_agreement;
		$this->_agreement = false;
		if ($this->config->get('config_affiliate_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_affiliate_id'));
			
			if ($information_info) $this->_agreement = $information_info;
		}
		return $this->_agreement;	
	}
	
}

