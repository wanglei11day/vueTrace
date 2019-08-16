<?php
class ControllerAccountRole extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/role', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/role');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/role');

		$this->getList();
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/role', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/role');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/role');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_role->addRole($this->request->post);

			$this->session->data['success'] = $this->language->get('text_add');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('role_add', $activity_data);

			$this->response->redirect($this->url->link('account/role', '', 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/role', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/role');

		$this->document->setTitle($this->language->get('heading_title'));


		$this->load->model('account/role');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_role->editRole($this->request->get['role_id'], $this->request->post);

			// Default Shipping Role
			if (isset($this->session->data['shipping_role']['role_id']) && ($this->request->get['role_id'] == $this->session->data['shipping_role']['role_id'])) {
				$this->session->data['shipping_role'] = $this->model_account_role->getRole($this->request->get['role_id']);

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Role
			if (isset($this->session->data['payment_role']['role_id']) && ($this->request->get['role_id'] == $this->session->data['payment_role']['role_id'])) {
				$this->session->data['payment_role'] = $this->model_account_role->getRole($this->request->get['role_id']);

				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$this->session->data['success'] = $this->language->get('text_edit');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('role_edit', $activity_data);

			$this->response->redirect($this->url->link('account/role', '', 'SSL'));
		}

		$this->getForm();
	}

    public function get() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            //$result['msg'] = 'please login';
           // die(json_encode($result));
        }
        $this->load->language('account/role');
        $this->load->model('account/role');
        $result['data'] = $this->model_account_role->getAllRoles();
        $result['status'] = 1;
        die(json_encode($result));
    }


    public function join() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/role');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/role');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_role->joinRole($this->request->post);
            $result['msg'] = $res?$this->language->get('text_joined'):$this->language->get('text_join_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }


    public function roleList() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }


        $filter_name = $this->request->post['filter_name'];
        $this->load->language('account/role');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/role');


        $data['rolees'] = array();

        $result['data'] = $this->model_account_role->getAllRolees($filter_name);

        $result['status'] = 1;
        die(json_encode($result));
    }

	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/role', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/role');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/role');

		if (isset($this->request->get['role_id']) && $this->validateDelete()) {
			$this->model_account_role->deleteRole($this->request->get['role_id']);



			$this->session->data['success'] = $this->language->get('text_delete');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('role_delete', $activity_data);

			$this->response->redirect($this->url->link('account/role', '', 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/role', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_role_book'] = $this->language->get('text_role_book');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_new_role'] = $this->language->get('button_new_role');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['rolees'] = array();

		$results = $this->model_account_role->getJoinRolees();

		foreach ($results as $result) {

			$data['rolees'][] = array(
				'role_id' => $result['role_id'],
				'name'    => $result['name'],
                'logo'    => $result['logo'],
                'telephone'    => $result['telephone'],
				'update'     => $this->url->link('account/role/edit', 'role_id=' . $result['role_id'], 'SSL'),
				'delete'     => $this->url->link('account/role/delete', 'role_id=' . $result['role_id'], 'SSL')
			);
		}

		$data['add'] = $this->url->link('account/role/add', '', 'SSL');
		$data['back'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/role_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/role_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/role_list.tpl', $data));
		}
	}

	protected function getForm() {
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/role', '', 'SSL')
		);

		if (!isset($this->request->get['role_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_role'),
				'href' => $this->url->link('account/role/add', '', 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_role'),
				'href' => $this->url->link('account/role/edit', 'role_id=' . $this->request->get['role_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit_role'] = $this->language->get('text_edit_role');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_logo'] = $this->language->get('entry_logo');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_logo'] = $this->language->get('entry_logo');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_address'] = $this->language->get('entry_address');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['logo'])) {
			$data['error_logo'] = $this->error['logo'];
		} else {
			$data['error_logo'] = '';
		}



		
		if (!isset($this->request->get['role_id'])) {
			$data['action'] = $this->url->link('account/role/add', '', 'SSL');
		} else {
			$data['action'] = $this->url->link('account/role/edit', 'role_id=' . $this->request->get['role_id'], 'SSL');
		}

		if (isset($this->request->get['role_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$role_info = $this->model_account_role->getRole($this->request->get['role_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($role_info)) {
			$data['name'] = $role_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($role_info)) {
			$data['logo'] = $role_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($role_info)) {
			$data['telephone'] = $role_info['telephone'];
		} else {
			$data['telephone'] = '';
		}



		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($role_info)) {
			$data['logo'] = $role_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($role_info)) {
			$data['description'] = $role_info['description'];
		} else {
			$data['description'] = '';
		}


        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($role_info)) {
            $data['address'] = $role_info['address'];
        } else {
            $data['address'] = '';
        }






		$data['back'] = $this->url->link('account/role', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/role_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/role_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/role_form.tpl', $data));
		}
	}

	protected function validateForm() {
		if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen(trim($this->request->post['telephone'])) < 1) || (utf8_strlen(trim($this->request->post['telephone'])) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}



		if ((utf8_strlen(trim($this->request->post['logo'])) < 3) || (utf8_strlen(trim($this->request->post['logo'])) > 128)) {
			$this->error['logo'] = $this->language->get('error_logo');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if ($this->model_account_role->getTotalRolees() == 1) {
			$this->error['warning'] = $this->language->get('error_delete');
		}

		if ($this->customer->getRoleId() == $this->request->get['role_id']) {
			$this->error['warning'] = $this->language->get('error_default');
		}

		return !$this->error;
	}
}
