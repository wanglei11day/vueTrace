<?php
class ControllerAccountProject extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/project', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/project');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/project');
        $this->load->model('account/section');
        $this->load->model('account/role');
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
            'href' => $this->url->link('account/project', '', 'SSL')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_project_book'] = $this->language->get('text_project_book');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_new_project'] = $this->language->get('button_new_project');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_back'] = $this->language->get('button_back');


        if ($this->request->get['project_id'])
        {
            $project_id = $this->request->get['project_id'];
        }
        else
        {
            $project_id = 0;
        }
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

        $data['projectes'] = array();

        $filter = array();
        if ($this->request->get['filter_name']) {
            $filter['success'] = $this->get->get['filter_name'];
        }
        if ($this->request->get['filter_school_id']) {
            $filter['filter_school_id'] = $this->request->get['filter_school_id'];
        }

        $data['project'] = $this->model_account_project->getProject($project_id);
        $sections = $this->model_account_section->getSections(array('filter_project_id'=>$project_id));
        foreach ($sections as &$section)
        {
            $section['thumbnails'] = json_decode($section['thumbnails'],true);
            $creation_id = $section['creation_id'];
            $save_key = $section['save_key'];
            if($save_key&&$creation_id)
            {
                $section['edituri'] = $this->url->link('account/section/design', 'product_id='.$data['project']['product_id'] .'&section_id='.$section['section_id'].'&minimum=1&action=edit&creation_id='.$creation_id.'#/'.$save_key.'/');
                $section['content_url'] = S3_SERVER.'upload.creator5.permanent/cootoo/creations/'.$save_key.'.json';
            }
            else
            {
                $section['edituri'] = $this->url->link('account/section/design', 'product_id='.$data['project']['product_id'].'&section_id='.$section['section_id']);
                $section['content_url'] = S3_SERVER.'upload.creator5.permanent/cootoo/creations/'.$save_key.'.json';;
            }
            $section_images = $this->model_account_section->getSectionImages(array('filter_section_id'=>$section['section_id']));
            $section['image_count'] = count($section_images);
        }
        $data['image_uri'] = S3_SERVER.'static.icootoo.com/';
        $data['sections'] = $sections;
        $data['photos'] = $this->model_account_project->getProjectRecentPhotos($project_id);
        $data['customers'] = $this->model_account_project->getProjectCustomers(array('filter_project_id'=>$project_id));
        $data['roles'] = $this->model_account_role->getAllRoles();
        $data['project_id'] = $project_id;
        $data['add'] = $this->url->link('account/project/add', '', 'SSL');
        $data['back'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/project.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/project.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/project.tpl', $data));
        }
	}

	public function add() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/project');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_project->addProject($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
	}

    public function addProjectCustomer() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
           // die(json_encode($result));
        }

        $this->load->language('account/project');
        $this->load->model('account/quicksignup');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/project');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $email = $this->request->post['email'];

            $email = trim($email);
            $exist = $this->model_account_quicksignup->getCustomerByEmail($email);
            if(!$exist)
            {
                $cus['email'] = $email;
                $email_arr = explode('@',$email);
                if(count($email_arr)!=2){
                    $result['msg'] = 'email error';
                    die(json_encode($result));
                }
                $cus['firstname'] = $this->request->post['first_name'];
                $cus['lastname'] = $this->request->post['last_name'];
                $cus['password'] = '123456';
                $customer_id = $this->model_account_quicksignup->addCustomer($cus);
            }
            else
            {
                $customer_id = $exist['customer_id'];
            }
            $this->request->post['customer_id'] = $customer_id;
            $res = $this->model_account_project->addProjectCustomer($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['data'] = $this->request->post;
            $result['data']['id'] = $res;
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/project', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/project');
        $this->load->language('account/section');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/project');
        $this->load->model('account/section');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_project->editProject($this->request->get['project_id'], $this->request->post);

			// Default Shipping Project
			if (isset($this->session->data['shipping_project']['project_id']) && ($this->request->get['project_id'] == $this->session->data['shipping_project']['project_id'])) {
				$this->session->data['shipping_project'] = $this->model_account_project->getProject($this->request->get['project_id']);

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Project
			if (isset($this->session->data['payment_project']['project_id']) && ($this->request->get['project_id'] == $this->session->data['payment_project']['project_id'])) {
				$this->session->data['payment_project'] = $this->model_account_project->getProject($this->request->get['project_id']);

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

			$this->model_account_activity->addActivity('project_edit', $activity_data);

			$this->response->redirect($this->url->link('account/project', '', 'SSL'));
		}

		$this->getForm();
	}


    public function join() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/project');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_project->joinProject($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }

    /**
     * 复制项目
     */
    public function copy() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/project');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_project->copyProject($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }

    /*
     * 改变项目组织
     */
    public function changeOrg() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        $this->load->language('account/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/project');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_project->changeOrg($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }


    public function projectList() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        if (isset($this->request->get['page'])) {
            $filter['page'] = $this->request->get['page'];
        } else {
            $filter['page'] = 1;
        }
        $filter['school_id'] = $this->request->get['school_id'];
        $this->load->language('account/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/project');

        $data['projectes'] = array();

        $result['data'] = $this->model_account_project->getSchoolProjectes($filter);

        $result['status'] = 1;
        die(json_encode($result));
    }



	public function delete() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }
		$this->load->language('account/project');

		$this->load->model('account/project');

		if (isset($this->request->get['project_id'])) {
			$this->model_account_project->deleteProject($this->request->get['project_id']);

            $result['msg'] = $this->language->get('text_delete');
            $result['status'] = 1;
		}

        die(json_encode($result));
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
			'href' => $this->url->link('account/project', '', 'SSL')
		);

		if (!isset($this->request->get['project_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_project'),
				'href' => $this->url->link('account/project/add', '', 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_project'),
				'href' => $this->url->link('account/project/edit', 'project_id=' . $this->request->get['project_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit_project'] = $this->language->get('text_edit_project');
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



		
		if (!isset($this->request->get['project_id'])) {
			$data['action'] = $this->url->link('account/project/add', '', 'SSL');
		} else {
			$data['action'] = $this->url->link('account/project/edit', 'project_id=' . $this->request->get['project_id'], 'SSL');
		}

		if (isset($this->request->get['project_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$project_info = $this->model_account_project->getProject($this->request->get['project_id']);
			if($project_info)
            {
                $data['sections'] = $this->model_account_section->getSections(array('filter_project_id'=>$project_info['project_id']));
            }
		}
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($project_info)) {
			$data['name'] = $project_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($project_info)) {
			$data['logo'] = $project_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($project_info)) {
			$data['telephone'] = $project_info['telephone'];
		} else {
			$data['telephone'] = '';
		}



		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($project_info)) {
			$data['logo'] = $project_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($project_info)) {
			$data['description'] = $project_info['description'];
		} else {
			$data['description'] = '';
		}


        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($project_info)) {
            $data['address'] = $project_info['address'];
        } else {
            $data['address'] = '';
        }






		$data['back'] = $this->url->link('account/project', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/project_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/project_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/project_form.tpl', $data));
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

		return !$this->error;
	}
}
