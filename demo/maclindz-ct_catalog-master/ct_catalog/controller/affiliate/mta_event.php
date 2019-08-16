<?php

class ControllerAffiliateMtaEvent extends Controller {

	public function eventAddCustomerPre($data) {
		if(isset($data['tracking'])) $this->config->set('_mta_tmp_tracking_', $data['tracking']);	
	}	

	public function eventAddCustomerPost($customer_id) {
		if($this->config->get('mta_ypx_no_aff_in_cust_acc') && !$this->config->get('mta_ypx_db_perm')) return;
		$dt = $this->config->get('_mta_tmp_tracking_');
		$this->config->set('_mta_tmp_tracking_', 0);
		$aff_id = 0;
		if (isset($this->request->cookie['tracking']) || $dt || isset($this->request->get['tracking'])) $this->load->model('affiliate/affiliate');
		if (isset($this->request->cookie['tracking'])) {			
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);				
			if ($affiliate_info) $aff_id = $affiliate_info['affiliate_id'];
		}
		if(!$aff_id && $dt) {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($dt);		
			if ($affiliate_info) $aff_id = $affiliate_info['affiliate_id'];
		}		
		if(!$aff_id && isset($this->request->get['tracking'])) {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->get['tracking']);		
			if ($affiliate_info) $aff_id = $affiliate_info['affiliate_id'];
		}		
		
		if($aff_id) $this->db->query("UPDATE " . DB_PREFIX . "customer SET affiliate_id='" . (int) $aff_id . "' WHERE customer_id='" . (int) $customer_id . "'");		
	}

	public function eventAddAffiliatePost($affiliate_id) {
		$this->load->model('affiliate/mta_affiliate');
		$this->model_affiliate_mta_affiliate->addAffiliate($affiliate_id, $this->model_affiliate_mta_affiliate->find_parent(), (isset($this->request->cookie['mta']) ? $this->request->cookie['mta'] : ''));	
	}

	public function eventAddOrderHistoryPre($order_id) {
		if(is_array($order_id) && isset($order_id['order_id'])) $order_id = $order_id['order_id'];
		$this->config->set('config_affiliate_auto', false);
	}	
	
	public function eventAutoAddCommissions($order_id) {
		$_autoadd_statuses = $this->config->get('mta_ypx_autoadd_statuses');
		if(!is_array($_autoadd_statuses)) $_autoadd_statuses = $this->config->get('config_complete_status');
		$_res = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND order_status_id IN (" . implode(',', $_autoadd_statuses) . ")");
		if($_res->num_rows < 1) return;		
		$this->load->model('affiliate/mta_affiliate');
		$this->model_affiliate_mta_affiliate->autoAddCommissions($order_id);		
	}	
	
	public function eventAddOrderPre($data) {
		$this->config->set('_mta_order_data_', $data);
	}

	public function eventAddOrderPost($order_id) {
		$data = $this->config->get('_mta_order_data_');
		if(!$data) return;
		$this->config->set('_mta_order_data_', 0);
		$this->load->model('affiliate/mta_affiliate');
		$this->model_affiliate_mta_affiliate->addOrder($order_id, $data);
	}	

	public function eventEditOrderPre($data) {
		$this->config->set('_mta_order_data_', $data);
	}

	public function eventEditOrderPost($order_id) {
		$data = $this->config->get('_mta_order_data_');
		if(!$data) return;
		$this->config->set('_mta_order_data_', 0);
		$this->load->model('affiliate/mta_affiliate');
		$this->model_affiliate_mta_affiliate->editOrder($order_id, $data);
	}		

	public function eventAddTransactionPost($affiliate_transaction_id) {
		$descr = $this->config->get('_mta_transaction_description_');
		if(!$descr) return;
		$this->config->set('_mta_transaction_description_', '');
		$this->db->query("UPDATE " . DB_PREFIX . "affiliate_transaction SET description = '" . $this->db->escape($descr) . "' WHERE affiliate_transaction_id = '" . (int)$affiliate_transaction_id . "'");
	}

	public function eventDeleteOrder($order_id) {
		$this->load->model('affiliate/mta_affiliate');
		$this->model_affiliate_mta_affiliate->deleteOrder($order_id);
	}
	
}