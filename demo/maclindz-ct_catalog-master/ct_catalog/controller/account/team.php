<?php
class ControllerAccountTeam extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/team', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/team');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/team');

		$this->getList();
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/team', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/team');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/team');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_team->addTeam($this->request->post);

			$this->session->data['success'] = $this->language->get('text_add');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('team_add', $activity_data);

			$this->response->redirect($this->url->link('account/team', '', 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/team', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/team');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/team');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_team->editTeam($this->request->get['team_id'], $this->request->post);

			// Default Shipping Team
			if (isset($this->session->data['shipping_team']['team_id']) && ($this->request->get['team_id'] == $this->session->data['shipping_team']['team_id'])) {
				$this->session->data['shipping_team'] = $this->model_account_team->getTeam($this->request->get['team_id']);

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Team
			if (isset($this->session->data['payment_team']['team_id']) && ($this->request->get['team_id'] == $this->session->data['payment_team']['team_id'])) {
				$this->session->data['payment_team'] = $this->model_account_team->getTeam($this->request->get['team_id']);

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

			$this->model_account_activity->addActivity('team_edit', $activity_data);

			$this->response->redirect($this->url->link('account/team', '', 'SSL'));
		}

		$this->getForm();
	}


    public function join() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/team');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/team');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_team->joinTeam($this->request->post);
            $result['msg'] = $res?$this->language->get('text_joined'):$this->language->get('text_join_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }


    // 批量添加成员
    public function addTeamMember()
    {
        $result['status'] =0;
        $team_id = $this->request->post['team_id'];
        $role_id = $this->request->post['role_id'];
        $emails = $this->request->post['emails'];
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
           // die(json_encode($result));
        }
        if (!$team_id||!$role_id||!$emails) {
            $result['msg'] = 'data err';
            die(json_encode($result));
        }
        $this->load->model('account/team');
        $this->load->model('account/quicksignup');

        $emails = explode(',',$this->request->post['emails']);
        if(count($emails)==1)
        {
            $emails = explode(';',$this->request->post['emails']);
        }
        foreach ($emails as $email)
        {
            if(!$email) continue;
            $email = trim($email);
            $exist = $this->model_account_quicksignup->getCustomerByEmail($email);
            if(!$exist)
            {
                $cus['email'] = $email;
                $email_arr = explode('@',$email);
                if(count($email_arr)!=2)    continue;
                $cus['firstname'] = $name = $email_arr[0];
                $cus['password'] = '123456';
                $customer_id = $this->model_account_quicksignup->addCustomer($cus);
            }
            else
            {
                $customer_id = $exist['customer_id'];
            }
            if (!$this->model_account_team->checkExist($team_id,$customer_id)) {
                $data['team_id'] = $team_id;
                $data['role_id'] = $role_id;
                $data['customer_id'] = $customer_id;

                $res = $this->model_account_team->addTeamCustomer($data);
                if($res)  $result['status'] =1;
            }
        }
        die(json_encode($result));

    }



    public function teamList() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }


        $filter_name = $this->request->post['filter_name'];
        $this->load->language('account/team');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/team');


        $data['teames'] = array();

        $result['data'] = $this->model_account_team->getAllTeames($filter_name);

        $result['status'] = 1;
        die(json_encode($result));
    }

    public function getTeamCustomers() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }


        $team_id = $this->request->get['team_id'];
        if (!$team_id) {
            $result['msg'] = 'data error';
            die(json_encode($result));
        }
        $this->load->language('account/team');

        $this->load->model('account/team');

        $result['data'] = $this->model_account_team->getTeamCustomers($team_id);
        $result['status'] = 1;
        die(json_encode($result));
    }


	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/team', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/team');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/team');

		if (isset($this->request->get['team_id']) && $this->validateDelete()) {
			$this->model_account_team->deleteTeam($this->request->get['team_id']);



			$this->session->data['success'] = $this->language->get('text_delete');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('team_delete', $activity_data);

			$this->response->redirect($this->url->link('account/team', '', 'SSL'));
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
			'href' => $this->url->link('account/team', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_team_book'] = $this->language->get('text_team_book');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_new_team'] = $this->language->get('button_new_team');
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

		$data['teames'] = array();

		$results = $this->model_account_team->getJoinTeames();

		foreach ($results as $result) {

			$data['teames'][] = array(
				'team_id' => $result['team_id'],
				'name'    => $result['name'],
                'logo'    => $result['logo'],
                'telephone'    => $result['telephone'],
				'update'     => $this->url->link('account/team/edit', 'team_id=' . $result['team_id'], 'SSL'),
				'delete'     => $this->url->link('account/team/delete', 'team_id=' . $result['team_id'], 'SSL')
			);
		}

		$data['add'] = $this->url->link('account/team/add', '', 'SSL');
		$data['back'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/team_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/team_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/team_list.tpl', $data));
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
			'href' => $this->url->link('account/team', '', 'SSL')
		);

		if (!isset($this->request->get['team_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_team'),
				'href' => $this->url->link('account/team/add', '', 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_team'),
				'href' => $this->url->link('account/team/edit', 'team_id=' . $this->request->get['team_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit_team'] = $this->language->get('text_edit_team');
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



		
		if (!isset($this->request->get['team_id'])) {
			$data['action'] = $this->url->link('account/team/add', '', 'SSL');
		} else {
			$data['action'] = $this->url->link('account/team/edit', 'team_id=' . $this->request->get['team_id'], 'SSL');
		}

		if (isset($this->request->get['team_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$team_info = $this->model_account_team->getTeam($this->request->get['team_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($team_info)) {
			$data['name'] = $team_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($team_info)) {
			$data['logo'] = $team_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($team_info)) {
			$data['telephone'] = $team_info['telephone'];
		} else {
			$data['telephone'] = '';
		}



		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($team_info)) {
			$data['logo'] = $team_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($team_info)) {
			$data['description'] = $team_info['description'];
		} else {
			$data['description'] = '';
		}


        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($team_info)) {
            $data['address'] = $team_info['address'];
        } else {
            $data['address'] = '';
        }






		$data['back'] = $this->url->link('account/team', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/team_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/team_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/team_form.tpl', $data));
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
		if ($this->model_account_team->getTotalTeames() == 1) {
			$this->error['warning'] = $this->language->get('error_delete');
		}

		if ($this->customer->getTeamId() == $this->request->get['team_id']) {
			$this->error['warning'] = $this->language->get('error_default');
		}

		return !$this->error;
	}
}
