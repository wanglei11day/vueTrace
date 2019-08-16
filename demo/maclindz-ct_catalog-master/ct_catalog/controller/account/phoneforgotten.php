<?php
class ControllerAccountPhoneForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}
		$type=isset($this->request->get['type'])?$this->request->get['type']:'';
		
		$this->load->language('account/forgotten');
		if($this->session->data['telephone']){
            $data['telephone'] = $this->session->data['telephone'];
        }
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');
		$this->load->model('account/password_reset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $data['telephone'] = $this->request->post['telephone'];
            $password = $this->request->post['password'];
            $this->model_account_customer->editPasswordByPhone($this->request->post['telephone'], $password);
            $this->session->data['success'] = $this->language->get('change_password_success');
            $customer_info = $this->model_account_customer->getCustomerByTelephone($this->request->post['telephone']);
            if ($customer_info) {
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $customer_info['customer_id'],
                    'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
                );

                $this->model_account_activity->addActivity('forgotten', $activity_data);
            }
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('account/forgotten', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_email'] = $this->language->get('text_your_email');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_your_phone'] = $this->language->get('text_your_phone');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_email_holder'] = $this->language->get('entry_email_holder');
		
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_area_code'] = $this->language->get('entry_area_code');
		$data['entry_verify_code'] = $this->language->get('entry_verify_code');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['tip_message_empty'] = $this->language->get('tip_message_empty');
		$data['tip_message_send_success'] = $this->language->get('tip_message_send_success');
		$data['entry_comfirmpassword'] = $this->language->get('entry_comfirmpassword');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['tip_changepassword_message_empty'] = $this->language->get('tip_changepassword_message_empty');
		$data['tip_message_password_not_match'] = $this->language->get('tip_message_password_not_match');
		$data['text_mail_send_success'] = $this->language->get('text_success');
		
		$data['change_password_success'] = $this->language->get('change_password_success');
		$data['email'] = isset($this->request->get['email'])?$this->request->get['email']:'';
		$data['type']=$type;
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
        $data['action'] =  $this->url->link('account/phoneforgotten', '', 'SSL');
		
		
		
		$data['changepassword'] = $this->url->link('account/changepassword', '', 'SSL');
		$data['back'] = $this->url->link('account/login', '', 'SSL');

		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/phone_forgotten.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/phone_forgotten.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/phone_forgotten.tpl', $data));
		}

	}
	
	
	public function app_sendmail(){
		$this->load->language('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');
		$this->load->model('account/password_reset');
		$response = array('success'=>false);
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$customer_info = array();

			if(!empty($this->request->post['email'])){
				$customer_info =$this->model_account_customer->getCustomerByEmail($this->request->post['email']);
				
				if ($customer_info) {
					$this->sendmail();
					$this->load->model('account/activity');
					$activity_data = array(
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
					);
					$response['success'] = true;
					$this->model_account_activity->addActivity('forgotten', $activity_data);
				}else{
					$response['error'] =$this->language->get('customer_error');
				}
			}else{
				$response['error'] =$this->language->get('email_error');
			}
		}else{
			$response['error'] =$this->language->get('request_method_error');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}
	protected function sendmail(){
		$this->load->language('mail/forgotten');
		$this->load->model('account/password_reset');
		$customer = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		
		/*
		$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

		$this->model_account_customer->editPassword($this->request->post['email'], $password);
		*/
		
		
		$getpasstime = time();
		$uid = $customer['customer_id'];
		$token = md5($uid.$customer['firstname'].$customer['password']);
		$this->model_account_password_reset->addPasswordReset(array('customer_id'=>$uid,'token'=>$token));
		$url=$this->url->link('account/resetpassword','email='.$this->request->post['email'].'&token='.$token);

		$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

		$message  = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
		$message .= $this->language->get('text_password') . "\n\n";
		$message .= $url;
		//$this->sendphpmail($message);
		//return;
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		
		$mail->setTo($this->request->post['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject($subject);
		$mail->setText($message);
		$mail->send();

	}
	public function sendsnsmessage(){
		$json = array();

		$forgetpassworduri = API_SERVER.'/rest/v1/customer/forget_password';
		$telephone = '';
		if(!empty($this->request->post['telephone'])&&!empty($this->request->post['telephone_area_code'])){
			$telephone = $this->db->escape($this->request->post['telephone_area_code']).'-'.$this->db->escape($this->request->post['telephone']);
		}
		$response = post_by_curl($forgetpassworduri,array('email'=>'','phone'=>$telephone));
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
	
	public function send_verify_code(){
		$json = array();

		$forgetpassworduri = API_SERVER.'/rest/v1/Message/send_cod_vercode';
		$address_id = $this->session->data['shipping_address']['address_id'];
		$token = $this->session->data['api_token'];
		$response = post_by_curl($forgetpassworduri,array('address_id'=>$address_id,'token'=>$token));
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
	
	public function verify_code(){
		$json = array();

		$forgetpassworduri = API_SERVER.'/rest/v1/Message/verify_cod_vercode';
		$address_id = $this->session->data['shipping_address']['address_id'];
		$token = $this->session->data['api_token'];
		$response = post_by_curl($forgetpassworduri,array('address_id'=>$address_id,'token'=>$token,'vercode'=>$this->request->post['verify_code']));
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
	
	
	public function phonechangepassword(){
		$json = array();
		$this->load->model('account/customer');
		$changepassworduri = API_SERVER.'/rest/v1/customer/modify_password_by_phone';
		$verify_code = $this->request->post['verify_code'];
		$password = $this->request->post['password'];
		$telephone = isset($this->request->post['synctelephone'])?$this->request->post['synctelephone']:'';
		
		$customer_info = $this->model_account_customer->getCustomerByTelephone($telephone);
		$response = '';
		if(!empty($customer_info)){
			$shapass = sha1($password);
			$response = post_by_curl($changepassworduri,array('customer_id'=>$customer_info['customer_id'],'vercode'=>$verify_code,'new_pwdsha1'=>$shapass));
		}else{
			$response = json_encode(array('status'=>9999,'info'=>'customer not found!'));
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
	
	protected function validate() {


        if (!isset($this->request->post['telephone'])) {
            $this->error['warning'] = $this->language->get('error_telephone');
        } else {
            if(!$this->model_account_customer->getTotalCustomersByTelephone($this->request->post['telephone']))
                $this->error['warning'] = $this->language->get('error_not_found_telephone');
        }

        $verify_code = $this->request->post['verify_code'];
        $session_code = $this->session->data['verify_code'];
        if($verify_code != $session_code){
            $this->error['warning'] = $this->language->get('error_verify_code');
        }
		

		return !$this->error;
	}
}