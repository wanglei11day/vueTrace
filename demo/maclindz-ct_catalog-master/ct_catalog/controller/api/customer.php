<?php
class ControllerApiCustomer extends Controller {
	public function index() {
		$this->load->language('api/customer');

		// Delete past customer in case there is an error
		unset($this->session->data['customer']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'customer_id',
				'customer_group_id',
				'firstname',
				'lastname',
				'email',
				'telephone',
				'fax'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			// Customer
			if ($this->request->post['customer_id']) {
				$this->load->model('account/customer');

				$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

				if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
					$json['error']['warning'] = $this->language->get('error_customer');
				}
			}

			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email']))) {
				$json['error']['email'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			// Customer Group
			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

			foreach ($custom_fields as $custom_field) {
				if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}

			if (!$json) {
				$this->session->data['customer'] = array(
					'customer_id'       => $this->request->post['customer_id'],
					'customer_group_id' => $customer_group_id,
					'firstname'         => $this->request->post['firstname'],
					'lastname'          => $this->request->post['lastname'],
					'email'             => $this->request->post['email'],
					'telephone'         => $this->request->post['telephone'],
					'fax'               => $this->request->post['fax'],
					'custom_field'      => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : array()
				);

				$json['success'] = $this->language->get('text_success');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function batadd()
    {
        $secrect = $this->request->post['secrect'];
        if($secrect!='ewow13092lfwlj!@')
        {
            die('秘钥错误');
        }
        $this->load->model('account/customer');
        $this->load->model('account/quicksignup');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $emails = $this->request->post['emails'];
            $names = $this->request->post['names'];
            $emails = explode(',',$emails);
            $names = explode(',',$names);
            if(count($emails) != count($names)) die('邮箱和名字数量不匹配');
            foreach ($emails as $k=>$email)
            {

                $data['email'] = $email;
                $data['password'] = '123456';
                $data['name'] = $names[$k];
                $res = $this->validate($data);
                if(!$res)
                {
                    echo '添加 '.$email.' 失败！'.PHP_EOL;
                    continue;
                }
                $this->model_account_quicksignup->addCustomer($data,false);
                echo '添加 '.$email.' 成功！'.PHP_EOL;
            }
        }
    }

    private function validate($data) {

        if(isset($data['email'])) {
            $domain = strstr($data['email'], '@');
            if (empty($domain)) {
                return false;
            }
            if ($this->model_account_customer->getTotalCustomersByEmail($data['email'])) {
                return false;
            }
            return true;
        }
        return false;
    }
}
