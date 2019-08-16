<?php
class ControllerAccountShare extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('account/share');

		$this->document->setTitle($this->language->get('heading_title'));

		if (!isset($this->session->data['shares'])) {
			$this->session->data['shares'] = array();
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if(isset($this->request->post['customer_creation_id'])){
					$this->load->model('account/diy_design');
					$project_info =  $this->model_account_diy_design->getDiyDesignByDiyId($this->request->post['customer_creation_id']);
					if(!empty($project_info)){
						try {
							$this->sendProjectToFriend($project_info);
							$this->response->redirect($this->url->link('account/share/success'));
						} catch (Exception $e) {
							echo $this->language->get('error_mail_send');
						}
						
						
					}

			}
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
			'text' => $this->language->get('text_share'),
			'href' => $this->url->link('account/share', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_description'] = $this->language->get('text_description');
		$data['entry_share_to_name'] = $this->language->get('entry_share_to_name');
		$data['entry_share_to_email'] = $this->language->get('entry_share_to_email');
		$data['entry_from_name'] = $this->language->get('entry_from_name');
		$data['entry_from_email'] = $this->language->get('entry_from_email');
		$data['entry_theme'] = $this->language->get('entry_theme');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_amount'] = $this->language->get('entry_amount');

		$data['help_message'] = $this->language->get('help_message');
		$data['help_amount'] = sprintf($this->language->get('help_amount'), $this->currency->format($this->config->get('config_share_min')), $this->currency->format($this->config->get('config_share_max')));

		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['share_to_name'])) {
			$data['error_share_to_name'] = $this->error['share_to_name'];
		} else {
			$data['error_share_to_name'] = '';
		}

		if (isset($this->error['share_to_email'])) {
			$data['error_share_to_email'] = $this->error['share_to_email'];
		} else {
			$data['error_share_to_email'] = '';
		}

		if (isset($this->error['from_name'])) {
			$data['error_from_name'] = $this->error['from_name'];
		} else {
			$data['error_from_name'] = '';
		}

		if (isset($this->error['from_email'])) {
			$data['error_from_email'] = $this->error['from_email'];
		} else {
			$data['error_from_email'] = '';
		}

		if (isset($this->error['theme'])) {
			$data['error_theme'] = $this->error['theme'];
		} else {
			$data['error_theme'] = '';
		}

		if (isset($this->error['amount'])) {
			$data['error_amount'] = $this->error['amount'];
		} else {
			$data['error_amount'] = '';
		}

		$data['action'] = $this->url->link('account/share', '', 'SSL');

		if (isset($this->request->post['share_to_name'])) {
			$data['share_to_name'] = $this->request->post['share_to_name'];
		} else {
			$data['share_to_name'] = '';
		}

		if (isset($this->request->post['share_to_email'])) {
			$data['share_to_email'] = $this->request->post['share_to_email'];
		} else {
			$data['share_to_email'] = '';
		}

		if (isset($this->request->post['from_name'])) {
			$data['from_name'] = $this->request->post['from_name'];
		} elseif ($this->customer->isLogged()) {
			$data['from_name'] = $this->customer->getFirstName() . ' '  . $this->customer->getLastName();
		} else {
			$data['from_name'] = '';
		}

		if (isset($this->request->post['from_email'])) {
			$data['from_email'] = $this->request->post['from_email'];
		} elseif ($this->customer->isLogged()) {
			$data['from_email'] = $this->customer->getEmail();
		} else {
			$data['from_email'] = '';
		}

		
		if (isset($this->request->get['customer_creation_id'])) {
			$data['customer_creation_id'] = $this->request->get['customer_creation_id'];
		} else {
			$data['customer_creation_id'] = '';
		}

		

		if (isset($this->request->post['message'])) {
			$data['message'] = $this->request->post['message'];
		} else {
			$data['message'] = '';
		}

		if (isset($this->request->post['amount'])) {
			$data['amount'] = $this->request->post['amount'];
		} else {
			$data['amount'] = $this->currency->format($this->config->get('config_share_min'), $this->config->get('config_currency'), false, false);
		}

		

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/share.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/share.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/share.tpl', $data));
		}
	}


	private function sendProjectToFriend($project_info){
				$this->load->language('mail/share');
				$data = array();
				

				$data['title'] = sprintf($this->language->get('text_subject'), $this->request->post['from_name']);

				$data['text_greeting'] = $this->language->get('text_greeting');
				$url=$this->url->link('account/share/confirmed','customer_creation_id='.$project_info['customer_creation_id'].'&key='.md5($project_info['customer_creation_id'].$project_info['customer_id'].$project_info['product_id']));
				$data['message'] = sprintf($this->language->get('text_message'),$this->request->post['from_name'],$this->request->post['message'],$url,$url);
				$data['message'] = html_entity_decode($data['message']);
				
				$data['text_footer'] = $this->language->get('text_footer');


				if (isset( $project_info['image'])) {
					$data['image'] = $project_info['image'];
				} else {
					$data['image'] = '';
				}
				$data['store_name'] = '';
				$data['store_url'] = '';
				$getpasstime = time();
				
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo( $this->request->post['share_to_email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->language->get('text_sender'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(sprintf($this->language->get('text_subject'), html_entity_decode($this->request->post['from_name'], ENT_QUOTES, 'UTF-8')));
				$mail->setHtml($this->load->view($this->config->get('config_template') . '/template/mail/share_project.tpl', $data));
				$mail->send();
	}

	public function success() {
		$this->load->language('account/share');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/share')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_message'] = $this->language->get('text_message');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('account/diydesign');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}

	protected function validate() {
		

		if ((utf8_strlen($this->request->post['share_to_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['share_to_email'])) {
			$this->error['share_to_email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
			$this->error['from_name'] = $this->language->get('error_from_name');
		}

		if ((utf8_strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['from_email'])) {
			$this->error['from_email'] = $this->language->get('error_email');
		}

		return !$this->error;
	}

	public function confirmed(){
		$this->session->data['force_redirect'] = $this->url->link('account/diydesign', '', 'SSL');
		$this->load->language('account/share');
		if(isset($this->request->get['customer_creation_id'])&&isset($this->request->get['key'])){
			$this->load->model('account/diy_design');
			$project_info =  $this->model_account_diy_design->getDiyDesignByDiyId($this->request->get['customer_creation_id']);
			if(!empty($project_info)){
				$validv = md5($project_info['customer_creation_id'].$project_info['customer_id'].$project_info['product_id']);
				if($validv==$this->request->get['key']){
					$this->session->data['share_customer_creation_id'] = $this->request->get['customer_creation_id'];
					if ($this->customer->isLogged()) {
						$this->model_account_diy_design->copyshare($this->request->get['customer_creation_id']);
						unset($this->session->data['share_customer_creation_id']);
						$this->response->redirect($this->url->link('account/diydesign', '', 'SSL'));
					}else{
						$this->response->redirect($this->url->link('account/login', '', 'SSL'));
					}

				}else{
					echo $this->language->get('error_key');
					exit;
				}
			}else{
				echo $this->language->get('error_project');
				exit;
			}	
		}else{
			echo $this->language->get('error_argments');
			exit;
		}	

	}

}