<?php
class ControllerAccountSchool extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/school', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/school');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/school');

		$this->getList();
	}

    public function add() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/school');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/school');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_school->addSchool($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }

    public function modifyName() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/school');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/school');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $res = $this->model_account_school->modifyName($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/school', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/school');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/school');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_school->editSchool($this->request->get['school_id'], $this->request->post);

			// Default Shipping School
			if (isset($this->session->data['shipping_school']['school_id']) && ($this->request->get['school_id'] == $this->session->data['shipping_school']['school_id'])) {
				$this->session->data['shipping_school'] = $this->model_account_school->getSchool($this->request->get['school_id']);

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment School
			if (isset($this->session->data['payment_school']['school_id']) && ($this->request->get['school_id'] == $this->session->data['payment_school']['school_id'])) {
				$this->session->data['payment_school'] = $this->model_account_school->getSchool($this->request->get['school_id']);

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

			$this->model_account_activity->addActivity('school_edit', $activity_data);

			$this->response->redirect($this->url->link('account/school', '', 'SSL'));
		}

		$this->getForm();
	}


    public function get() {

        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
           // die(json_encode($result));
        }

        $this->load->language('account/school');

        $this->load->model('account/school');

        // Default Payment School
        $school = array();

        if (isset($this->request->get['school_id'])) {
            $school = $this->model_account_school->getSchool($this->request->get['school_id']);

        }
        $result['status'] = 1;
        $result['data'] = $school;
        die(json_encode($result));
    }


    public function leave() {

        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            // die(json_encode($result));
        }

        $this->load->language('account/school');

        $this->load->model('account/school');

        // Default Payment School
        $res = 0;
        if (isset($this->request->post['school_id'])) {
            $res = $this->model_account_school->leaveSchool($this->request->post['school_id']);
        }
        $result['status'] = $res;
        die(json_encode($result));
    }


    public function join() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/school');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/school');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_school->joinSchool($this->request->post);
            $result['msg'] = $res?$this->language->get('text_joined'):$this->language->get('text_join_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }


    public function schoolList() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }


        $filter_name = $this->request->post['filter_name'];
        $this->load->language('account/school');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/school');


        $data['schooles'] = array();

        $result['data'] = $this->model_account_school->getAllSchooles($filter_name);

        $result['status'] = 1;
        die(json_encode($result));
    }

	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/school', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/school');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/school');

		if (isset($this->request->get['school_id']) && $this->validateDelete()) {
			$this->model_account_school->deleteSchool($this->request->get['school_id']);



			$this->session->data['success'] = $this->language->get('text_delete');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('school_delete', $activity_data);

			$this->response->redirect($this->url->link('account/school', '', 'SSL'));
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
			'href' => $this->url->link('account/school', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_school_book'] = $this->language->get('text_school_book');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_new_school'] = $this->language->get('button_new_school');
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

		$data['schooles'] = array();

		$results = $this->model_account_school->getJoinSchooles();

		foreach ($results as $result) {

			$data['schooles'][] = array(
				'school_id' => $result['school_id'],
				'name'    => $result['name'],
                'logo'    => $result['logo'],
                'telephone'    => $result['telephone'],
				'update'     => $this->url->link('account/school/edit', 'school_id=' . $result['school_id'], 'SSL'),
				'delete'     => $this->url->link('account/school/delete', 'school_id=' . $result['school_id'], 'SSL')
			);
		}

		$data['add'] = $this->url->link('account/school/add', '', 'SSL');
		$data['back'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/school_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/school_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/school_list.tpl', $data));
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
			'href' => $this->url->link('account/school', '', 'SSL')
		);

		if (!isset($this->request->get['school_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_school'),
				'href' => $this->url->link('account/school/add', '', 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_school'),
				'href' => $this->url->link('account/school/edit', 'school_id=' . $this->request->get['school_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit_school'] = $this->language->get('text_edit_school');
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



		
		if (!isset($this->request->get['school_id'])) {
			$data['action'] = $this->url->link('account/school/add', '', 'SSL');
		} else {
			$data['action'] = $this->url->link('account/school/edit', 'school_id=' . $this->request->get['school_id'], 'SSL');
		}

		if (isset($this->request->get['school_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$school_info = $this->model_account_school->getSchool($this->request->get['school_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($school_info)) {
			$data['name'] = $school_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($school_info)) {
			$data['logo'] = $school_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($school_info)) {
			$data['telephone'] = $school_info['telephone'];
		} else {
			$data['telephone'] = '';
		}



		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($school_info)) {
			$data['logo'] = $school_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($school_info)) {
			$data['description'] = $school_info['description'];
		} else {
			$data['description'] = '';
		}


        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($school_info)) {
            $data['address'] = $school_info['address'];
        } else {
            $data['address'] = '';
        }






		$data['back'] = $this->url->link('account/school', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/school_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/school_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/school_form.tpl', $data));
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
		if ($this->model_account_school->getTotalSchooles() == 1) {
			$this->error['warning'] = $this->language->get('error_delete');
		}

		if ($this->customer->getSchoolId() == $this->request->get['school_id']) {
			$this->error['warning'] = $this->language->get('error_default');
		}

		return !$this->error;
	}
}
