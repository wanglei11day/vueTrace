<?php
class ModelPaymentWeih5pay extends Model {
	public function getMethod($address, $total) {
        $this->load->language('payment/weih5pay');

        if ($this->config->get('weih5pay_status')) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'weih5pay',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('weixin_native_sort_order')
            );
        }

        return $method_data;
	}
}