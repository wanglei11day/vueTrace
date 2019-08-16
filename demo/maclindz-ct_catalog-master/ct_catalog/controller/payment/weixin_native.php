<?php
class ControllerPaymentweixinnative extends Controller {
	public function index() {
			
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		//$data['continue'] = $this->url->link('checkout/success');
		$data['continue'] = $this->url->link('weixin_native/pending');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/weixin_native.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/weixin_native.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/weixin_native.tpl', $data);
		}
	}

	public function confirm() {
		/*if ($this->session->data['payment_method']['code'] == 'weixin_native') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('weixin_native_order_pending_status_id'));
		}*/
	}
	
	public function callback() {

		$this->load->model('checkout/order2');
		
		$order_id = $this->request->post['order_id'];
		$order_status_id = $this->request->post['order_status_id'];

		//查看是否发送过数据
		$sql = $this->db->query("SELECT if_email_weixin_native FROM " . DB_PREFIX . "order WHERE order_id = '".$order_id."' LIMIT 1 ");
		if($sql->num_rows){
			if($sql->row['if_email_weixin_native'] == '0'){
				
				$this->db->query("UPDATE " . DB_PREFIX . "order SET if_email_weixin_native = '1' WHERE order_id = '".$order_id."' ");
				//发送邮件
				$this->model_checkout_order2->addOrderHistory($order_id, $order_status_id);	
				
			}else{
				//nothing
				
			}
		}
		
	}
}