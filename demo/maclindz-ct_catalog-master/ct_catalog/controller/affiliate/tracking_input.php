<?php

class ControllerAffiliateTrackingInput extends Controller {

	public function check_tracking() {
		$_REQUEST['format'] = $_GET['format'] = 'raw';//+mod by yp mijoshop
		$ret = array('unique' => false);
		if($this->config->get('tracking_input_status') && isset($this->request->post['tracking_input']) && $this->_check($this->request->post['tracking_input'])) $ret['unique'] = true;

		$this->response->addHeader('Content-Type: application/json');		
		$this->response->setOutput(json_encode($ret));
	}

	public function update() {
		$_REQUEST['format'] = $_GET['format'] = 'raw';//+mod by yp mijoshop
		if($this->affiliate->isLogged() && $this->config->get('tracking_input_status') && $this->config->get('tracking_input_edit_code') && isset($this->request->post['tracking_input']) && $this->_check($this->request->post['tracking_input'])) {
			$out = '1';
			$this->load->model('affiliate/affiliate');	
			$this->model_affiliate_affiliate->editCode($this->request->post['tracking_input']);
		} else {
			$out = '0';
		}		
		$this->response->setOutput($out);		
	}
	
	private function _check($code) {
		if(!preg_match("/^[a-z0-9_\-]+\z/", $code) || strlen($code) > 20) return false;
		if($this->affiliate->isLogged() && $this->affiliate->getCode() == $code) return true;
		$this->load->model('affiliate/affiliate');
		$a = $this->model_affiliate_affiliate->getAffiliateByCode($code);	
		if(!$a && $this->config->get('tracking_input_allow_coupon')) {
			$_mpfx = version_compare(VERSION, '2.1', '<') ? 'checkout' : 'total';
			$this->load->model($_mpfx . '/coupon');
			$a = $this->{'model_' . $_mpfx . '_coupon'}->getCoupon($code);
		}
		if(!$a) {
			$this->load->model('checkout/marketing');
			$a = $this->model_checkout_marketing->getMarketingByCode($code);
		}
		return (!$a ? true : false);
	}

}