<?php
class ControllerAccountSection extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/section', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/section');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/section');

		$this->getList();
	}

	public function add() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        $this->load->language('account/section');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_section->addSection($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['data'] = $this->request->post;
            $result['data']['id'] = $res;
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
	}


    public function lock() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        $this->load->language('account/section');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_section->lock($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }


    public function markReview() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        $this->load->language('account/section');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_section->markReview($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }

    public function rename() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        $this->load->language('account/section');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_section->rename($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/section', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/section');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/section');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_section->editSection($this->request->get['section_id'], $this->request->post);

			// Default Shipping Section
			if (isset($this->session->data['shipping_section']['section_id']) && ($this->request->get['section_id'] == $this->session->data['shipping_section']['section_id'])) {
				$this->session->data['shipping_section'] = $this->model_account_section->getSection($this->request->get['section_id']);

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Section
			if (isset($this->session->data['payment_section']['section_id']) && ($this->request->get['section_id'] == $this->session->data['payment_section']['section_id'])) {
				$this->session->data['payment_section'] = $this->model_account_section->getSection($this->request->get['section_id']);

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

			$this->model_account_activity->addActivity('section_edit', $activity_data);

			$this->response->redirect($this->url->link('account/section', '', 'SSL'));
		}

		$this->getForm();
	}


    public function join() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/section');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $res = $this->model_account_section->joinSection($this->request->post);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
    }


    public function images() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        if (!$this->request->get['section_id']) {
            $result['msg'] = 'arg error';
            die(json_encode($result));
        }
        $filter['filter_section_id'] =$this->request->get['section_id'];
        $page =$this->request->get['page']?$this->request->get['page']:1;
        $filter['limit'] =$this->request->get['limit']?$this->request->get['limit']:10;
//        $filter['start'] = $page*$filter['limit'];
        $filter['start'] = 0;
        $filter['order'] =$this->request->get['order'];
        $this->load->language('account/section');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');
        $result['data'] = $this->model_account_section->getSectionImages($filter);
        die(json_encode($result));
    }


    public function deleteImage() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        if (!$this->request->post['section_id']) {
            $result['msg'] = 'arg error';
            die(json_encode($result));
        }
        $this->load->language('account/section');

        $this->load->model('account/section');
        $this->model_account_section->delSectionImage($this->request->post);
        $result['status'] =1;
        die(json_encode($result));
    }


    public function updateImage() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        if (!$this->request->post['section_id']) {
            $result['msg'] = 'arg error';
            die(json_encode($result));
        }
        $this->load->language('account/section');

        $this->load->model('account/section');
        $this->model_account_section->updateSectionImage($this->request->post);
        $result['status'] = 1;
        die(json_encode($result));
    }


    public function sectionList() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }


        $filter_name = $this->request->post['filter_name'];
        $this->load->language('account/section');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');


        $data['sectiones'] = array();

        $result['data'] = $this->model_account_section->getAllSectiones($filter_name);

        $result['status'] = 1;
        die(json_encode($result));
    }


	public function delete() {


        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            //die(json_encode($result));
        }

        $this->load->language('account/section');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');


        if (isset($this->request->post['section_id'])) {
            $res = $this->model_account_section->deleteSection($this->request->post['section_id']);
            $result['msg'] = $res?$this->language->get('text_added'):$this->language->get('text_add_failed');
            $result['status'] = $res?1:0;
        }
        die(json_encode($result));
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
			'href' => $this->url->link('account/section', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_section_book'] = $this->language->get('text_section_book');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_new_section'] = $this->language->get('button_new_section');
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

		$data['sectiones'] = array();

        $filter = array();
        if ($this->request->get['filter_name']) {
            $filter['success'] = $this->get->get['filter_name'];
        }
        if ($this->request->get['filter_school_id']) {
            $filter['filter_school_id'] = $this->request->get['filter_school_id'];
        }

		$results = $this->model_account_section->getSections($filter);

		foreach ($results as $result) {

			$data['sections'][] = array(
				'section_id' => $result['section_id'],
				'name'    => $result['name'],
                'logo'    => $result['logo'],
                'telephone'    => $result['telephone'],
				'update'     => $this->url->link('account/section/edit', 'section_id=' . $result['section_id'], 'SSL'),
				'delete'     => $this->url->link('account/section/delete', 'section_id=' . $result['section_id'], 'SSL')
			);
		}

		$data['add'] = $this->url->link('account/section/add', '', 'SSL');
		$data['back'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/section_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/section_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/section_list.tpl', $data));
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
			'href' => $this->url->link('account/section', '', 'SSL')
		);

		if (!isset($this->request->get['section_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_section'),
				'href' => $this->url->link('account/section/add', '', 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_section'),
				'href' => $this->url->link('account/section/edit', 'section_id=' . $this->request->get['section_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit_section'] = $this->language->get('text_edit_section');
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



		
		if (!isset($this->request->get['section_id'])) {
			$data['action'] = $this->url->link('account/section/add', '', 'SSL');
		} else {
			$data['action'] = $this->url->link('account/section/edit', 'section_id=' . $this->request->get['section_id'], 'SSL');
		}

		if (isset($this->request->get['section_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$section_info = $this->model_account_section->getSection($this->request->get['section_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($section_info)) {
			$data['name'] = $section_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($section_info)) {
			$data['logo'] = $section_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($section_info)) {
			$data['telephone'] = $section_info['telephone'];
		} else {
			$data['telephone'] = '';
		}



		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($section_info)) {
			$data['logo'] = $section_info['logo'];
		} else {
			$data['logo'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($section_info)) {
			$data['description'] = $section_info['description'];
		} else {
			$data['description'] = '';
		}


        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($section_info)) {
            $data['address'] = $section_info['address'];
        } else {
            $data['address'] = '';
        }






		$data['back'] = $this->url->link('account/section', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/section_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/section_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/section_form.tpl', $data));
		}
	}

    protected function validateCallbackForm()
    {
        $status = true;
        $creation_id = $this->request->post['creation_id'];
        if (!isset($this->request->post['creation_id'])) {
            return false;
        }



        return $status;
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


    public function sort() {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $this->load->language('account/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/section');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $data = $this->request->post['data'];
            $data = json_decode($data,true);
            foreach ($data as $item)
            {
                $this->model_account_section->changeSort($item);
            }

            $result['msg'] = $this->language->get('text_added');
            $result['status'] = 1;
        }
        die(json_encode($result));
    }


    public function uploadDesign()
    {
        $result['status'] = 0;
        if (!$this->customer->isLogged()) {
            $result['msg'] = 'please login';
            die(json_encode($result));
        }

        $save_key = $this->request->get['save_key'];
        $content = $this->request->get['content'];
        if(!$save_key||!$content)
        {
            $result['msg'] = 'arg error';
            die(json_encode($result));
        }
        $file_key = 'cootoo/creations/'.$save_key.'.json';

        $res = $this->mv_file_to_s3($content,$file_key,'json');
        if(!$res)
        {
            $result['msg'] = 'upload err';
            die(json_encode($result));
        }
        $result['status'] = 1;
        die(json_encode($result));
    }


    private function mv_file_to_s3($content,$file,$ext){
        $bucket = 'upload.creator5.permanent';
        require_once DIR_SYSTEM.'library/aws/aws-autoloader.php';
        error_reporting(0);
        $content_type = '';
        ini_set('display_errors',1);
        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => 'cn-north-1',
            'ContentType'  => $content_type,
            'http'    => [
                'verify' => false
            ],
            'credentials' => [
                'key'    => 'AKIAOZKKEKG7GK5MPI6A',
                'secret' => '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'
            ]
        ]);

        $res = $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => $file,
            'Body'   => $content,
            'ContentType'  => $ext,
            'ACL'    => 'public-read',
        ]);
        return $res;


    }


    public function design() {
        if (!$this->customer->isLogged()&&$this->session->data['weixinbrower']) {
            $this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->session->data['editor_save_redirect'] = $this->url->link('account/diydesign');
        if (!$this->customer->isLogged()) {
            $data['isLogin'] = 0;
        }else{
            $data['isLogin'] = 1;
        }
        if(isset($this->request->get['product_id'])){
            $data['product_id'] = (int)$this->request->get['product_id'];
        }else{
            $data['product_id'] = 0;
        }
        if(isset($this->request->get['minimum'])){
            $data['minimum'] = (int)$this->request->get['minimum'];
        }else{
            $data['minimum'] = 0;
        }
        if(isset($this->request->get['section_id'])){
            $data['section_id'] = $this->request->get['section_id'];
        }else{
            $data['section_id'] = '';
        }

       

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['hide_edit'] = false;
        $data['creator'] = false;
        $data['base'] = $server;

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $data['design_url'] = HTTP_SERVER;
        $data['design_cart'] = $this->url->link('product/design');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['language_id'] = $this->config->get('config_language_id');
        $data['language'] = $this->load->controller('common/language');
        $data['currency'] = $this->load->controller('common/currency');
        $data['search'] = $this->load->controller('common/search');
        $data['cart'] = $this->load->controller('common/cart');

        $this->load->language('product/design');


        $data['txt_guide'] = $this->language->get('txt_guide');
        $data['text_min_pages'] = $this->language->get('text_min_pages');
        $data['text_max_pages'] = $this->language->get('text_max_pages');
        $data['text_warning_page'] = $this->language->get('text_warning_page');
        $data['txt_guide_title'] = $this->language->get('txt_guide_title');
        $data['txt_close_guide'] = $this->language->get('txt_close_guide');
        $data['button_cart'] = $this->language->get('button_cart');
        $data['txt_add'] = $this->language->get('txt_add');
        $data['txt_cancel'] = $this->language->get('button_cancel_return');
        $data['txt_reduce'] = $this->language->get('txt_reduce');
        $data['text_modify_name'] = $this->language->get('text_modify_name');
        $data['txt_page'] = $this->language->get('txt_page');
        $data['kwclocaleurl'] = $this->language->get('kwclocaleurl');
        $design_name=$this->language->get('text_default_design_name');
        $data['text_tip_info']=$this->language->get('text_tip_info');
        $data['text_tip_title']=$this->language->get('text_tip_title');
        $data['text_tip_continue']=$this->language->get('text_tip_continue');
        $data['text_tip_cancel']=$this->language->get('text_tip_cancel');
        $data['text_save_design_name']=$this->language->get('text_save_design_name');
        $data['text_design_name']=$this->language->get('text_design_name');
        $data['button_confirm']=$this->language->get('button_confirm');
        $data['button_cancel']=$this->language->get('button_cancel');
        $data['text_save_success']=$this->language->get('text_save_success');
        $data['text_upload']=$this->language->get('text_upload');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_comfirm_password'] = $this->language->get('entry_comfirm_password');
        $data['text_forgotten'] = $this->language->get('text_forgotten');
        $data['guides']=$this->language->get('text_guides');

        $data['current_pages'] = 20;
        if (isset($this->request->get['product_id'])) {
            $product_id = (int)$this->request->get['product_id'];
            $data['cancel'] =  $this->url->link('product/product','product_id='.$product_id);
            if ($data['section_id']) {

                $this->load->model('account/section');
                $sectiondesign = $this->model_account_section->getSection($data['section_id']);
                $data['option'] = $sectiondesign['option'];
                $data['current_pages'] = $sectiondesign['pages'];
                $design_name= $sectiondesign['name'];
            }

            //product_info
            $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($product_id);
            $data['product_info'] = $product_info;
            $product_editor_arg_str = $this->trimall($product_info['editorarg']);
            $product_editor_arg_str = html_entity_decode($product_editor_arg_str);
            $product_editor_arg_arr = explode(';',$product_editor_arg_str);
            $product_editor_arg = array();
            if(!empty($product_editor_arg_arr)){
                foreach($product_editor_arg_arr as $oea){
                    $product_editor_arg_arr = explode(':',$oea);
                    if(count($product_editor_arg_arr)==2){
                        $product_editor_arg[$product_editor_arg_arr[0]] = $product_editor_arg_arr[1];
                    }
                }
            }

            $editorarg = $product_editor_arg;

            $data['editorarg'] = $editorarg;

            // Attributes
            $data['product_attributes'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

            // product_options
            $product_options = $this->model_catalog_product->getProductOptions($product_id);

            $option_array = array();
            if(!empty($data['option'])){
                $option_array = json_decode($data['option'],true);
                //print_r('xxx'.arrayToString($option_array).'yyy');

                $option_data = array();
                foreach ($product_options as $option) {
                    //echo json_encode($option);
                    //echo '<br>';
                    if ($option['type'] != 'file'){
                        $value = $option['value'];
                    }
                    if (!empty($option_array)){
                        foreach($option_array as $k=>$v){
                            $product_option_id = $k;
                            $product_option_value_id = $v;
                            //echo 'aa'.$product_option_id.'  bb'.$product_option_value_id.'<br>';
                            if($option['product_option_id'] == $product_option_id){
                                //echo 'aa'.$product_option_id.'  bb'.$product_option_value_id.'<br>';
                                foreach($option['product_option_value'] as $k=>$v){
                                    //echo 'cc'.$k.'  dd'.$v.'<br>';
                                    if($v['product_option_value_id'] == $product_option_value_id){

                                        $option_editor_arg_str = $this->trimall($v['editorarg']);
                                        $option_editor_arg_str = html_entity_decode($option_editor_arg_str);
                                        $option_editor_arg_arr = explode(';',$option_editor_arg_str);

                                        $option_editor_arg = array();
                                        if(!empty($option_editor_arg_arr)){
                                            foreach($option_editor_arg_arr as $oea){
                                                $option_editor_arg_arr = explode(':',$oea);
                                                if(count($option_editor_arg_arr)==2){
                                                    $option_editor_arg[$option_editor_arg_arr[0]] = $option_editor_arg_arr[1];
                                                }
                                            }
                                        }

                                        if(!empty($option_editor_arg)){
                                            foreach($option_editor_arg as $key=>$item){
                                                if(strcasecmp($item,'True')==0)
                                                    $item='true';
                                                if($item==''){
                                                    continue;
                                                }
                                                if(isset($editorarg[$key])&&is_numeric($editorarg[$key])){
                                                    if(is_numeric($item)){
                                                        $editorarg[$key] += $item;
                                                    }
                                                }else{
                                                    $editorarg[$key] = $item;
                                                }

                                            }
                                        }


                                        $option_data[] = array(
                                            'product_option_id' => $option['product_option_id'],
                                            'product_option_value_id' => $v['product_option_value_id'],
                                            'name'  => $option['name'],
                                            'value' => $v['name']
                                        );
                                    }
                                }

                            }
                        }
                    }


                }
                $data['option_data'] = $option_data;

                //print_r("+++".arrayToString($option_data));
            }else{
                $products = $this->cart->getProducts();
                foreach ($products as $product) {
                    $option_data = array();
                    if($product_id == $product['product_id']){
                        foreach ($product['option'] as $option) {
                            if ($option['type'] != 'file') {
                                $value = $option['value'];
                            }
                            $option_data[] = array(
                                'product_option_value_id' => $option['product_option_value_id'],
                                'name'  => $option['name'],
                                'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                            );
                        }
                        $data['option_data'] = $option_data;
                    }else{
                        $data['option_data'] = $option_data;
                    }
                }
            }
        } else {
            $product_id = 0;

        }
        if(isset($this->request->get['action'])){
            $data['action'] = $this->request->get['action'];
        }else{
            $data['action'] = '';
        }
        $data['design_name'] = $design_name;

        $data['fontfaces'] = array();
        if(isset($editorarg[811])){
            $rdat = $this->getFontfaces($editorarg[811]);
            $data['fontfaces'] = $rdat['fontfaces'];
            $editorarg[811] = $rdat['namelist'];
        }
        $tmpfonts = array();
        foreach ($data['fontfaces'] as $item)
        {
            $key = ltrim($item['name'],'-');
            $tmpfonts[$key] = $item;
        }

        $data['jsonfontfaces'] = json_encode($tmpfonts);
        $data['editorarg'] = $editorarg;
        $data['config_template'] = $this->config->get('config_template');
        $this->session->data['editorarg'] = json_encode($editorarg);
        $data['editorquicksignup'] = $this->load->controller('common/editor_quicksignup');
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $this->customer->getId(),
            'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
        );
        $data['savecallback'] = $this->url->link('account/section/aftersavedesigncallback', '', 'SSL');

        $data['http_server'] = HTTP_SERVER;
        $this->model_account_activity->addActivity('enter_keditor', $activity_data);
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/section_design.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/section_design.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/section_design.tpl', $data));
        }
    }

    function trimall($str)//删除空格
    {
        $qian=array(" ","　","\t","\n","\r");
        $hou=array("","","","","");
        return str_replace($qian,$hou,$str);
    }

    function getFontfaces($data){
        $returndata = array();
        $namelist = '';
        $arr = explode(",",$data);
        foreach ($arr as $item){
            $font = explode("#",$item);
            if(count($font)==2){
                if(empty($namelist)){
                    $namelist = "'".$font[0]."'";
                }else{
                    $namelist = $namelist.",'".$font[0]."'";
                }
                $returndata['fontfaces'][] = array(
                    'name'=>$font[0],
                    'eot_file'=>CDN_SERVER.'fonts/'.$font[1].'.eot',
                    'woff_file'=>CDN_SERVER.'fonts/'.$font[1].'.woff',
                    'ttf_file'=>CDN_SERVER.'fonts/'.$font[1].'.ttf',
                    'svg_file'=>CDN_SERVER.'fonts/'.$font[1].'.svg'
                );
            }
        }
        $returndata['namelist'] = $namelist;
        return $returndata;
    }


    public function aftersavedesigncallback()
    {
        $issuccess = false;
        $section_id = '';
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCallbackForm()) {
            $this->load->model('account/section');

            $this->request->post['option'] =base64_decode($this->request->post['option']);
            $section_id = $this->model_account_section->updateDesign($this->request->post);
            $issuccess = true;
        }
        $arr = array ('success' => $issuccess);
        if(isset($this->session->data['editor_save_redirect'])&&!empty($this->session->data['editor_save_redirect'])){
            $arr['redirect'] = $this->session->data['editor_save_redirect'];
            unset($this->session->data['editor_save_redirect']);
        }else{
            $arr['redirect'] = $this->url->link('account/diydesign');
        }

        $arr['section_id'] = $section_id;
        if ($this->customer->isLogged()) {
            if(!$this->customer->getEmail()&&$this->customer->getOpenId()&&$this->session->data['weixinbrower']){
                $arr['code'] = 1;
            }else{
                $arr['code'] = 1;
            }

        }else{
            $arr['code'] = 10;
        }
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $this->customer->getId(),
            'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName().'/'.$section_id.'/'.$arr['code']
        );

        $this->model_account_activity->addActivity('save_design', $activity_data);
        $out = json_encode($arr);
        $this->response->setOutput($out);

    }

	protected function validateDelete() {

		return !$this->error;
	}
}
