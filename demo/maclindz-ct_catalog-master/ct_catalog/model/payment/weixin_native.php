<?php
class ModelPaymentweixinnative extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/weixin_native');

		if ($this->config->get('weixin_native_status')) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'weixin_native',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('weixin_native_sort_order')
			);
		}

		return $method_data;
	}
}