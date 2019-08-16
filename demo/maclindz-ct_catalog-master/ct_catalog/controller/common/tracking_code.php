<?php
class ControllerCommonTrackingCode extends Controller {
	public function index() {
        if (isset($this->request->get['tracking_key']) && $this->request->get['tracking_key']) {
            $tracking_key = $this->request->get['tracking_key'];
        } elseif (isset($this->session->data['tracking_key']) && $this->session->data['tracking_key']) {
            $tracking_key = $this->session->data['tracking_key'];
        }elseif (isset($this->request->get->cookie['tracking_key']) && $this->request->get->cookie['tracking_key']) {
            $tracking_key = $this->request->get->cookie['tracking_key'];
        } else {
            $tracking_key = '';
        }

        if($tracking_key){
            $this->session->data['tracking_key'] = $tracking_key;
            if (!isset($this->request->get->cookie['tracking_key']) || $this->request->get->cookie['tracking_key'] != $tracking_key) {
                setcookie('tracking_key', $tracking_key, time()+ 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
            }
            $customer_id = $this->customer->getId();
            if($customer_id){
                $this->load->model('account/customer');
                $this->load->model('account/customer_group');
                $customer_group_info = $this->model_account_customer_group->getCustomerGroupByTrackingCode($tracking_key);
                //print_r($customer_group_info);
                $customer_info = $this->model_account_customer->getCustomer($customer_id);
                $user_customer_group_id = $customer_info['customer_group_id'];
                if($user_customer_group_id ==  $this->config->get('config_customer_group_id')){
                    $customer_group_id = $customer_group_info['customer_group_id'];
                    $this->model_account_customer->updateCustomerGroup($customer_id,$customer_group_id);
                    setcookie('tracking_key', '', time() - 3600, '/', $this->request->server['HTTP_HOST']);
                    unset($this->session->data['tracking_key']);
                }
            }
        }

	}


}
