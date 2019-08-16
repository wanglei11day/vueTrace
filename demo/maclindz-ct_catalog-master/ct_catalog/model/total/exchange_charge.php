<?php
class ModelTotalExchangeCharge extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {

		$this->load->language('total/exchange_charge');
		$total_data[] = array(
				'code'       => 'exchange_charge',
				'title'      => $this->language->get('exchange_charge'),
				'value'      =>$charge,
				'sort_order' => $this->config->get('hurry_sort_order')
		);
		$total += $charge;
	}

	
}