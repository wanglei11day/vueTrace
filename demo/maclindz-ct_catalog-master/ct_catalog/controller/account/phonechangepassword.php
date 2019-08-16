<?php
class ControllerAccountPhoneChangePassword extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/customer');
		$this->load->model('account/password_reset');
		// Login override for admin users
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			

			$verify_code = $this->request->post['verify_code'];
			$password = $this->request->post['password'];
			
			if(!empty($this->request->post['telephone'])&&!empty($this->request->post['telephone_area_code'])){
				$telephone = $this->db->escape($this->request->post['telephone_area_code']).'-'.$this->db->escape($this->request->post['telephone']);
			}
			$customer_info = $this->model_account_customer->getCustomerByTelephone($telephone);
			if(!empty($customer_info)){
				
				$this->response->redirect($this->url->link('account/login', '', 'SSL'));
			
			}else{
				$this->response->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
		}else{
			if (!empty($this->request->get['token'])&&!empty($this->request->get['email'])) {
				$password_reset = $this->model_account_password_reset->getPasswordByToken($this->request->get['token']);
				
				if(empty($password_reset)){
					$this->error['token']=$this->language->get('token_error');
				}else{
					if($password_reset['date_added']-time()>24){
						$this->error['overtime']=$this->language->get('overtime');
					}
				}
				if(!$this->error){
					if ($this->customer->isLogged()) {
						$this->response->redirect($this->url->link('account/account', '', 'SSL'));
					}

					$this->load->language('account/login');

					$this->document->setTitle($this->language->get('heading_title'));

					

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
						'text' => $this->language->get('text_login'),
						'href' => $this->url->link('account/login', '', 'SSL')
					);
					$data['token']=$this->request->get['token'];
					$data['heading_title'] = $this->language->get('heading_title');

					$data['text_new_customer'] = $this->language->get('text_new_customer');
					$data['password_not_equal'] = $this->language->get('password_not_equal');
					
					$data['text_register'] = $this->language->get('text_register');
					$data['text_register_account'] = $this->language->get('text_register_account');
					$data['text_returning_customer'] = $this->language->get('text_returning_customer');
					$data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
					$data['text_forgotten'] = $this->language->get('text_forgotten');

					$data['entry_comfirm_password'] = $this->language->get('entry_comfirm_password');
					$data['entry_password'] = $this->language->get('entry_password');

					$data['button_continue'] = $this->language->get('button_continue');
					$data['button_login'] = $this->language->get('button_login');

					if (isset($this->error['warning'])) {
						$data['error_warning'] = $this->error['warning'];
					} else {
						$data['error_warning'] = '';
					}
					$data['action'] = $this->url->link('account/resetpassword', '', 'SSL');
					$data['register'] = $this->url->link('account/register', '', 'SSL');
					$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

					// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
					if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
						$data['redirect'] = $this->request->post['redirect'];
					} elseif (isset($this->session->data['redirect'])) {
						$data['redirect'] = $this->session->data['redirect'];

						unset($this->session->data['redirect']);
					} else {
						$data['redirect'] = '';
					}

					if (isset($this->session->data['success'])) {
						$data['success'] = $this->session->data['success'];

						unset($this->session->data['success']);
					} else {
						$data['success'] = '';
					}

					if (isset($this->request->post['comfirm_password'])) {
						$data['comfirm_password'] = $this->request->post['comfirm_password'];
					} else {
						$data['comfirm_password'] = '';
					}

					if (isset($this->request->post['password'])) {
						$data['password'] = $this->request->post['password'];
					} else {
						$data['password'] = '';
					}

					$data['column_left'] = $this->load->controller('common/column_left');
					$data['column_right'] = $this->load->controller('common/column_right');
					$data['content_top'] = $this->load->controller('common/content_top');
					$data['content_bottom'] = $this->load->controller('common/content_bottom');
					$data['footer'] = $this->load->controller('common/footer');
					$data['header'] = $this->load->controller('common/header');

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/resetpassword.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/resetpassword.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/account/resetpassword.tpl', $data));
					}
				}else{
				   $url = '';

					if (isset($this->request->get['path'])) {
						$url .= '&path=' . $this->request->get['path'];
					}

					if (isset($this->request->get['filter'])) {
						$url .= '&filter=' . $this->request->get['filter'];
					}

					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}

					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}

					if (isset($this->request->get['limit'])) {
						$url .= '&limit=' . $this->request->get['limit'];
					}

					$data['breadcrumbs'][] = array(
						'text' => $this->language->get('text_error'),
						'href' => $this->url->link('product/category', $url)
					);

					$this->document->setTitle($this->language->get('text_error'));

					$data['heading_title'] = $this->language->get('text_error');

					$data['text_error'] = $this->language->get('text_error');

					$data['button_continue'] = $this->language->get('button_continue');

					$data['continue'] = $this->url->link('common/home');

					$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

					$data['column_left'] = $this->load->controller('common/column_left');
					$data['column_right'] = $this->load->controller('common/column_right');
					$data['content_top'] = $this->load->controller('common/content_top');
					$data['content_bottom'] = $this->load->controller('common/content_bottom');
					$data['footer'] = $this->load->controller('common/footer');
					$data['header'] = $this->load->controller('common/header');

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
					}
				}
				
			}else{
				
				   $url = '';

					if (isset($this->request->get['path'])) {
						$url .= '&path=' . $this->request->get['path'];
					}

					if (isset($this->request->get['filter'])) {
						$url .= '&filter=' . $this->request->get['filter'];
					}

					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}

					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}

					if (isset($this->request->get['limit'])) {
						$url .= '&limit=' . $this->request->get['limit'];
					}

					$data['breadcrumbs'][] = array(
						'text' => $this->language->get('text_error'),
						'href' => $this->url->link('product/category', $url)
					);

					$this->document->setTitle($this->language->get('text_error'));

					$data['heading_title'] = $this->language->get('text_error');

					$data['text_error'] = $this->language->get('text_error');

					$data['button_continue'] = $this->language->get('button_continue');

					$data['continue'] = $this->url->link('common/home');

					$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

					$data['column_left'] = $this->load->controller('common/column_left');
					$data['column_right'] = $this->load->controller('common/column_right');
					$data['content_top'] = $this->load->controller('common/content_top');
					$data['content_bottom'] = $this->load->controller('common/content_bottom');
					$data['footer'] = $this->load->controller('common/footer');
					$data['header'] = $this->load->controller('common/header');

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
					}
			}

		
		}
		
		
	}

	protected function validate() {
		$this->event->trigger('pre.customer.login');

		// Check how many login attempts have been made.
		$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				$this->error['warning'] = $this->language->get('error_login');

				$this->model_account_customer->addLoginAttempt($this->request->post['email']);
			} else {
				$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

				$this->event->trigger('post.customer.login');
			}
		}

		return !$this->error;
	}
}