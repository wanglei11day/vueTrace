<?php
class ModelPaymentPaysali extends Model {
	public function getMethod($address, $total) {
        $this->load->language('payment/paysali');

        if ($this->config->get('weih5pay_status')) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'paysali',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('weixin_native_sort_order')
            );
        }

        return $method_data;
	}
}