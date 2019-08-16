<?php
class ModelAccountWeixinLogin extends Model {
	public function getCustomerInfo($openid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE openid = '" . $this->db->escape($openid) . "'");
		return $query->row;
	}
	
	public function bindCustomer($customer_id, $openid) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET openid = NULL WHERE openid = '" . $this->db->escape($openid) . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET openid = '" . trim($openid) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		$this->updateCustomerHeadimgurl($this->session->data['weixin_openid'], $this->session->data['headimgurl']);
	}

	public function isOpenidExist($openid) {
		$query = $this->db->query("SELECT openid FROM " . DB_PREFIX . "customer WHERE openid = '" . $this->db->escape($openid) . "'");
		if ($query->num_rows) {
			return $query->row['openid'];
		} else {
			return 0;
		}
	}

	public function addWeixinCustomer($openid, $firstname) {
		$this->event->trigger('pre.customer.add', $openid);

		$customer_group_id = $this->config->get('config_customer_group_id');

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
        $rd = rand(1,10000);
        $email='fake_'.$rd.'_'.time().'@icootoo.com';

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', headimgurl = '" . $this->session->data['headimgurl'] . "', email = '" . $this->db->escape($email). "', no_email = '1', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . trim($firstname) . "', telephone = '', fax = '', custom_field = '', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($openid)))) . "', newsletter = '0', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', openid = '" . $openid . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");
		
		$customer_id = $this->db->getLastId();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$this->load->language('mail/customer');
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->event->trigger('post.customer.add', $customer_id);

		return $customer_id;
	}

	public function updateCustomerHeadimgurl($openid, $headimgurl) {
		$query = $this->db->query("SELECT headimgurl FROM " . DB_PREFIX . "customer WHERE openid = '" . $this->db->escape($openid) . "'");

		if($query->row['headimgurl'] !== $headimgurl){
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET headimgurl = '" . $headimgurl . "' WHERE openid = '" . $openid . "'");
		}
	}

}
?>