<?php

class ModelTotalTrackingDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if($this->config->get('tracking_discount_no_coupon')) {
			foreach($total_data as $_v) {
				if($_v['code'] == 'coupon' && $_v['value'] < 0) return;
			}
		}
	
		$this->load->model('account/order');
		$this->load->model('affiliate/affiliate');
		if (!$this->config->get('tracking_discount_status') || !isset($this->request->cookie['tracking']) || !$this->cart->getSubTotal()) return;
		
		$apply_to = $this->config->get('tracking_discount_apply_to');
		if (!$apply_to) $apply_to = 'a';
		$aff = $mark = false;
		if (strpos($apply_to, 'a') !== false) $aff = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
		if (!$aff || !$aff['status'] || !$aff['approved']) {
			if (strpos($apply_to, 'm') === false) return;
			$this->load->model('checkout/marketing');
			$mark = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);
			if (!$mark) return;
		} else if($this->config->get('tracking_discount_per_aff')) {
			$_res = $this->db->query("SELECT `amount`, `type` FROM " . DB_PREFIX . "affiliate_tracking_discount WHERE affiliate_id = '" . (int)$aff['affiliate_id'] . "'");
			if($_res->num_rows > 0) {
				$AMOUNT = floatval($_res->row['amount']);				
				$this->config->set('tracking_discount_type', ($_res->row['type'] === 'fixed' ? 'fixed' : 'percent'));			
			}
		}
		
		if(!isset($AMOUNT)) $AMOUNT = floatval($this->config->get('tracking_discount_amount'));
		if($AMOUNT < 0.01) return;
		
		if(in_array($this->config->get('tracking_discount_when'), array('first', 'period'))) {
			if(!$this->customer->isLogged()) return;
			$_q = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0' AND affiliate_id > '0'";
			if($this->config->get('tracking_discount_when') == 'period') $_q .= " AND date_added > '" . $this->db->escape(date('Y-m-d H:i:s', time() - 86400 * $this->config->get('tracking_discount_period'))) . "'";
			$_res = $this->db->query($_q);
			if($_res->row['total'] > 0) return;
		}				
		
		$this->language->load('total/tracking_discount');
		
		if($this->config->get('tracking_discount_type') != 'fixed' && $this->config->get('tracking_discount_what') != 'total') {
			$what = explode('_', $this->config->get('tracking_discount_what'));
			$field = $what[1] == 'one' ? 'price' : 'total';
			$_products = $this->cart->getProducts();
			$products = array();
			foreach($_products as $_p) {
				$products[] = $_p[$field];
			}
			if($what[0] == 'all') {
				$st = array_sum($products);
			} else {
				sort($products);
				$_index = $what[0] == 'min' ? 0 : (sizeof($products) - 1);
				$st = $products[$_index];
			}		
		} else {		
			$st = $total;
		}

		if($this->config->get('tracking_discount_type') == 'fixed') {
			if($this->config->get('tracking_discount_fixed_per_item')) {
				$q = 0;
				foreach($this->cart->getProducts() as $_p) {
					$q += $_p['quantity'];
				}
				$AMOUNT *= $q;
			}
			$_fixed_max = $st * ((float) $this->config->get('tracking_discount_fixed_max')) * 0.01;
			$dsc =  $AMOUNT < $_fixed_max ?  $AMOUNT : $_fixed_max;
		} else {
			$_perc = $AMOUNT * 0.01;
			$dsc = $st - ($st * (1 - $_perc));
		}
		$sub_total = $this->cart->getSubTotal();
		foreach ($this->cart->getProducts() as $product) {
			$discount = $dsc * ($product['total'] / $sub_total);
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);
							
				foreach ($tax_rates as $tax_rate) {
					if ($tax_rate['type'] == 'P') {
						$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
					}
				}
			}
		}			
		
		$total_data[] = array(
                            'code'       => 'tracking_discount',
                            'title'      => $this->language->get('text_tracking_discount'),
                            'value'      => -$dsc,
                            'sort_order' => $this->config->get('tracking_discount_sort_order')
		);
        $total -= $dsc;		
	}
}

