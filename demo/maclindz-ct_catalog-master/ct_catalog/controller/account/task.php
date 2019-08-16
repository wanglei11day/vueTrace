<?php
class ControllerAccountTask extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/task', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		$this->load->language('account/task');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/task');


		$this->getList();
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/task', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/task');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/task');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		    $task = $this->copyDesign($this->request->post);
			$this->model_account_task->addTask($task);
			
			$this->session->data['success'] = $this->language->get('text_add');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'email' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('task_add', $activity_data);
			$back_id = $this->request->post['back_id'];
            if($back_id)
            {
                $this->response->redirect($this->url->link('account/department', 'department='.$back_id, 'SSL'));
            }
            else
            {
                $this->response->redirect($this->url->link('account/task', '', 'SSL'));
            }
		}
		$data['display'] = '';

		$this->getForm($data);
	}

    public function copyDesign($task) {
        $this->load->model('account/diy_design');
        $data = $this->model_account_diy_design->getDiyDesignByDiyId($task['customer_creation_id']);
        $query = $this->db->query("SELECT count(*) AS total FROM " . DB_PREFIX . "customer_design WHERE customer_id = '".$task['customer_id']."'");
        $total = $query->row['total'];
        $data['design_name'] = $task['name'].$total;
        $data['customer_id'] = $task['customer_id'];
        $data['visiable'] = 0;
        $task['customer_creation_id'] = $this->model_account_diy_design->insert($data);
        return $task;


    }

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/task', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/task');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/task');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_task->editTask($this->request->get['task_id'], $this->request->post);

			// Default Shipping Task
			
			$this->session->data['success'] = $this->language->get('text_edit');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'email' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('task_edit', $activity_data);

			$this->response->redirect($this->url->link('account/task', '', 'SSL'));
		}
		$data['display'] = 'none';
		$this->getForm($data);
	}



    public function modify_status() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/task', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/task');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/task');

        if (isset($this->request->get['task_id'])&&isset($this->request->get['status'])) {
            $this->model_account_task->updateStatus($this->request->get['task_id'],$this->request->get['status']);

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'email' => $this->customer->getId(),
                'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('task_update', $activity_data);

            $this->response->redirect($this->url->link('account/task', 'post=1', 'SSL'));
        }

        $this->getList();
    }


	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/task', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/task');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/task');

		if (isset($this->request->get['task_id'])) {
			$this->model_account_task->deleteTask($this->request->get['task_id']);

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'email' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('task_delete', $activity_data);

			$this->response->redirect($this->url->link('account/task', 'post=1', 'SSL'));
		}

		$this->getList();
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
        return true;


    }
	protected function getList() {

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $url = S3_SERVER.'upload.creator5.permanent/cootoo/creations/';
            $items = $this->request->post['I'];
            $selected = array();

            foreach ($items as $item){
                if($item['sort']>0){
                    $selected[$item['sort']][]=$item['task_id'];
                }
            }
            ksort($selected);

            $sortselected = array();
            foreach ($selected as $item){
                $sortselected = array_merge($sortselected,$item);
            }
            $this->load->model('account/task');
            $this->load->model('account/diy_design');
            if($this->request->post['cover']){
                $cover = $this->request->post['cover'];
            }else{
                $cover = 0;
            }
            $pages = array();
            $newdesign = array();
            $firstpages = array();
            foreach($sortselected as $k=>$task_id){
                if(!$cover){
                    $cover = $task_id;
                }
                $task_design = $this->model_account_task->getTaskById($task_id);
                $content = file_get_contents($url.$task_design['save_key'].'.json');
                $json_content = json_decode($content,true);
                $objcts = $json_content['pages'];

                if($k==0)
                {
                    $type = $json_content['product']['type'];
                }
                if($type=='gift')
                {
                    $pages = array_merge($pages, $objcts);
                    if ($task_id == $cover) {
                        $newdesign = $json_content;
                    }
                }
                else
                {
                    if ($task_id == $cover) {
                        $newdesign = $json_content;
                        $firstpages = $objcts;
                    } else {
                        unset($objcts[0]);
                        $pages = array_merge($pages, $objcts);
                    }
                }

            }
            if($type=='gift')
            {
                $newdesign['pages'] = $pages;
                $numpages = count($newdesign['pages']);
            }
            else
            {
                $newdesign['pages'] = array_merge($firstpages,$pages);
                $numpages = (count($newdesign['pages'])-1)*2;
            }


            $newdesign['pages'] = array_merge($firstpages,$pages);
            $design = $this->model_account_diy_design->getDiyDesignByDiyId($task_design['customer_creation_id']);
            $design['save_key'] = md5($design['save_key'].time()).date(Ymd);

            $file_key = 'cootoo/creations/'.$design['save_key'].'.json';


            $index = 1;
            if(!$newdesign['product']['units'][$index])
            {
                $index = 0;
            }
            $newdesign['product']['units'][$index]['num_pages'] = $numpages;
            $maxpage = $newdesign['product']['units'][$index]['max_pages'];
            if($maxpage<$numpages)
                $maxpage = $numpages;
            $newdesign['product']['units'][$index]['max_pages'] = $maxpage;


            $design['customer_id'] = $this->customer->getId();
            $design['pages'] = $numpages;
            $id = $this->model_account_diy_design->copydesignByCombine($design);

            $this->mv_file_to_s3(json_encode($newdesign),$file_key,'json');

            if ($id) {
                $data['success'] = sprintf($this->language->get('combinesuccess'), $this->url->link('account/diydesign'));
            } else {
                $data['success'] = '';
            }

        }

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
			'href' => $this->url->link('account/task', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_task_book'] = $this->language->get('text_task_book');
		$data['text_empty'] = $this->language->get('text_empty');
        $data['text_more'] = $this->language->get('text_more');

		$data['button_new_task'] = $this->language->get('button_new_task');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_back'] = $this->language->get('button_back');
        $data['text_combine_design'] = $this->language->get('text_combine_design');
        $data['button_combine_design'] = $this->language->get('button_combine_design');
        $data['column_cover'] = $this->language->get('column_cover');

        $data['button_back'] = $this->language->get('button_back');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['tag'])) {
			$tag = $this->request->get['tag'];
		} else {
			$tag = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}
		$limit = 200;


		$data['text_operate'] = $this->language->get('column_action');
        $data['text_copyurl'] = $this->language->get('text_copyurl');
        $data['text_delete'] = $this->language->get('text_delete');
        $data['text_my_task'] = $this->language->get('text_my_task');
		$data['text_my_history_task'] = $this->language->get('text_my_history_task');
		$data['text_my_post_task'] = $this->language->get('text_my_post_task');
		$data['text_history'] = $this->language->get('text_history');
		$data['column_edit_task'] = $this->language->get('column_edit_task');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_tag'] = $this->language->get('column_tag');
		$data['column_priority'] = $this->language->get('column_priority');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_excutor'] = $this->language->get('column_excutor');
        $data['column_supervise'] = $this->language->get('column_supervise');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_sort'] = $this->language->get('column_sort');
		$data['column_customer_creation_id'] = $this->language->get('column_customer_creation_id');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_info'] = $this->language->get('column_info');

		$data['column_excutor'] = $this->language->get('column_excutor');
		$data['column_creator'] = $this->language->get('column_creator');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_project'] = $this->language->get('column_project');
        $data['button_edit'] = $this->language->get('button_edit');

		$data['tip_save_project'] = $this->language->get('tip_save_project');
        $data['text_cover'] = $this->language->get('text_cover');




		//exit;
		$filter_data = array(
			'filter_tag'      => $tag,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);
		$this->load->model('account/diy_design');
		$data['tasks'] = array();
		$data['tags'] = array();
        $item['c_p'] = array();
        $today = date('Y-m-d');
        $no_more_data = $this->language->get('no_more_data');
        $get_more_data = $this->language->get('get_more_data');
		if(isset($this->request->get['post'])&&$this->request->get['post']==1){

			$data['mytask_class'] =  '';
			$data['myposttask_class'] =  'active';
			$data['myhistorytask_class'] =  '';
			$data['post'] = 1;
			$tags = $this->model_account_task->getPostTaskTags($filter_data);
			foreach ($tags as $t) {
				if($t['tag'] == $tag){
					$tagclass="active";
				}else{
					$tagclass="";
				}
				$data['tags'][] = array(
					'name' => $t['tag'],
					'tagclass' =>$tagclass,
					'href' => $this->url->link('account/task','post=1&tag='.$t['tag'])
				);
			}
		
			$data['tasks'] = $this->model_account_task->getPostTasks($filter_data);
			foreach ($data['tasks'] as &$item) {
                if(strtotime($item['date_end'])<strtotime($today))
                {
                    $item['over'] = 1;
                }
                else
                {
                    $item['over'] = 0;
                }

				$project_history = $this->model_account_diy_design->getHistory($item['customer_creation_id']);
                $item['first_edituri'] = $edituri = $this->url->link('product/design', 'product_id='.$item['product_id'] .'&customer_creation_id='.$item['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$item['creation_id']).'#/'.$item['save_key'].'/';

                foreach ($project_history as $history) {
					$edituri = $this->url->link('product/task', 'product_id='.$history['product_id'] .'&task_id='.$item['task_id'].'&history_id='.$history['customer_design_log_id'].'&minimum=1&action=edit&creation_id='.$history['creation_id']).'#/'.$history['save_key'].'/'; 
					$item['projects'][] = array(
						'name'=>'#'.$history['customer_design_log_id'].' '.$history['date_added'],
						'href'=>$edituri
					);
				}
                if(count($project_history)==10)
                {
                    $more_link = $this->url->link('account/diydesign/getMoreHistory', 'customer_creation_id='.$item['customer_creation_id'] .'&page=1');
                    $item['projects'][] = array(
                        'name' => $get_more_data,
                        'href' =>$more_link,
                        'class'=>'getmore'
                    );
                }
				if($item['projects'][0]){
				    $cp = $item['projects'][0];
                }else{
                    $cp = $this->model_account_diy_design->getDiyDesignByDiyId($item['customer_creation_id']);
                    $cp['href'] = $this->url->link('product/task', 'product_id='.$cp['product_id'] .'&task_id='.$item['task_id'].'&minimum=1&action=edit&creation_id='.$cp['creation_id']).'#/'.$cp['save_key'].'/';
                }
                $item['c_p'] = $cp;
                $item['c_p']['name'] = $item['name'];

				$item['actions'][] = array(
					'name' => $this->language->get('button_edit'),
					'class' => '',
					'href' => $this->url->link('account/task/edit','task_id='.$item['task_id'])
				);


				/*
				$item['actions'][] = array(
					'name' => $this->language->get('button_save'),
					'class' => 'save_project',
					'href' => $this->url->link('account/task/save_project','task_id='.$item['task_id'])
				);
                */
				$item['status_actions'][] = array(

                );

                $item['actions'][] = array(
                    'name' => $this->language->get('button_delete'),
                    'class' => '',
                    'href' => $this->url->link('account/task/delete','task_id='.$item['task_id'])
                );
				if($item['status']){
					$item['status_info'] = $this->language->get('data_status_'.$item['status']);
				}
				if($item['priority']){
					$item['priority'] = $this->language->get('data_priority_'.$item['priority']);
				}
			}

		}else if(isset($this->request->get['post'])&&$this->request->get['post']==2){
            $item['over'] = 0;
			$data['myhistorytask_class'] =  'active';
			$data['mytask_class'] =  '';
			$data['myposttask_class'] =  '';
            $data['post'] = 2;
			$data['tasks'] = $this->model_account_task->getHistoryTasks($filter_data);

			foreach ($data['tasks'] as &$item) {
				$project_history = $this->model_account_diy_design->getHistory($item['customer_creation_id']);

                $item['first_edituri'] = $edituri = $this->url->link('product/design', 'product_id='.$item['product_id'] .'&customer_creation_id='.$item['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$item['creation_id']).'#/'.$item['save_key'].'/';
				
				foreach ($project_history as $history) {
					$edituri = $this->url->link('product/task', 'product_id='.$history['product_id'] .'&task_id='.$item['task_id'].'&history_id='.$history['customer_design_log_id'].'&minimum=1&action=edit&creation_id='.$history['creation_id']).'#/'.$history['save_key'].'/'; 
					$item['projects'][] = array(
						'name'=>'#'.$history['customer_design_log_id'].' '.$history['date_added'],
						'href'=>$edituri
					);
				}
                if(count($project_history)==10)
                {
                    $more_link = $this->url->link('account/diydesign/getMoreHistory', 'customer_creation_id='.$item['customer_creation_id'] .'&page=1');
                    $item['projects'][] = array(
                        'name' => $get_more_data,
                        'href' =>$more_link,
                        'class'=>'getmore'
                    );
                }
                $item['c_p'] = $item['projects'][0];
                $item['c_p']['name'] = $item['name'];

                /*
				$item['actions'][] = array(
					'name' => $this->language->get('button_save'),
					'class' => 'save_project',
					'href' => $this->url->link('account/task/save_project','task_id='.$item['task_id'])
				);

                */
				if($item['status']){
					$item['status_info'] = $this->language->get('data_status_'.$item['status']);
				}
				if($item['priority']){
					$item['priority'] = $this->language->get('data_priority_'.$item['priority']);
				}

			}


		}else{
            $data['post'] = 0;
			$data['myhistorytask_class'] =  '';
			$data['mytask_class'] =  'active';
			$data['myposttask_class'] =  '';
			$tags = $this->model_account_task->getTaskTags();
			foreach ($tags as $t) {
				if($t['tag'] == $tag){
					$tagclass="active";
				}else{
					$tagclass="";
				}
				$data['tags'][] = array(
					'name' => $t['tag'],
					'tagclass' =>$tagclass,
					'href' => $this->url->link('account/task','tag='.$t['tag'])
				);
			}
			$data['tasks'] = $this->model_account_task->getTasks($filter_data);
			foreach ($data['tasks'] as &$item) {
                if(strtotime($item['date_end'])<strtotime($today))
                {
                    $item['over'] = 1;
                }
                else
                {
                    $item['over'] = 0;
                }
				$project_history = $this->model_account_diy_design->getHistory($item['customer_creation_id']);
                $item['first_edituri'] = $edituri = $this->url->link('product/design', 'product_id='.$item['product_id'] .'&customer_creation_id='.$item['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$item['creation_id']).'#/'.$item['save_key'].'/';
                $first_viewuri = $edituri = $this->url->link('product/task', 'product_id='.$item['product_id'] .'&task_id='.$item['task_id'].'&history_id='.$item['customer_design_log_id'].'&minimum=1&action=edit&creation_id='.$item['creation_id']).'#/'.$item['save_key'].'/';
                foreach ($project_history as $history) {
					$item['projects'][] = array(
						'name'=>'#'.$history['customer_design_log_id'].' '.$history['date_added'],
						'href'=>$this->url->link('product/task', 'product_id='.$history['product_id'] .'&task_id='.$item['task_id'].'&history_id='.$history['customer_design_log_id'].'&minimum=1&action=edit&creation_id='.$history['creation_id']).'#/'.$history['save_key'].'/'
					);
				}
                if(count($project_history)==10)
                {
                    $more_link = $this->url->link('account/diydesign/getMoreHistory', 'customer_creation_id='.$item['customer_creation_id'] .'&page=1');
                    $item['projects'][] = array(
                        'name' => $get_more_data,
                        'href' =>$more_link,
                        'class'=>'getmore'
                    );
                }
                $item['c_p']['href'] = $item['projects'][0]['href']?$item['projects'][0]['href']:$first_viewuri;
				$item['c_p']['name'] = $item['name'];

				/*
				$item['actions'][] = array(
					'name' => $this->language->get('button_save'),
					'class' => 'save_project',
					'href' => $this->url->link('account/task/save_project','task_id='.$item['task_id'])
				);
*/
				if($item['status']){
					$item['status_info'] = $this->language->get('data_status_'.$item['status']);
				}
				if($item['priority']){
					$item['priority'] = $this->language->get('data_priority_'.$item['priority']);
				}
			}
		}
		$statuss = explode(',',$this->language->get('data_status'));
		foreach ($statuss as $s)
        {
            $data['statuss'][$s] = $this->language->get('data_status_'.$s);
        }

		$data['mytask'] = $this->url->link('account/task', '', 'SSL');
		$data['myposttask'] = $this->url->link('account/task', 'post=1', 'SSL');
		$data['myhistorytask'] = $this->url->link('account/task', 'post=2', 'SSL');
		$data['modify_url'] = $this->url->link('account/task/modify_status','','SSL');
		$data['add'] = $this->url->link('account/task/add', '', 'SSL');
		$data['back'] = $this->url->link('account/account', '', 'SSL');
        $data['copyurl'] = $this->url->link('account/task/copytomy', '', 'SSL');
        $data['delete'] = $this->url->link('account/task/delete', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/task_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/task_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/task_list.tpl', $data));
		}
	}

	public function save_project(){
		$this->load->language('account/task');
		$json = array('status'=>false);
		if(isset($this->request->get['task_id']) && !empty($this->request->get['task_id'])){
			$this->load->model('account/task');
			$task_id = $this->request->get['task_id'];
			$task_info = $this->model_account_task->getTaskById($task_id);
			if(empty($task_info)){
                $data['success'] = 'Error Task Id';
			}else{
				$customer_creation_id = $task_info['customer_creation_id'];
				$this->load->model('account/diy_design');
				$project = $this->model_account_diy_design->getDiyDesignByDiyId($customer_creation_id);
				if(empty($project)){
                    $data['success'] = 'No Project Found~';
				}else{
					$this->model_account_diy_design->copy($customer_creation_id);
					$this->model_account_diy_design->updateDesignVisiable($customer_creation_id,1);
					$json['status'] = true;
                    $data['success'] = $this->language->get('text_save_project_success');
				}
			}

		}else{
            $data['success'] = 'No task id';
		}

        $this->response->redirect($this->url->link('account/task', '', 'SSL'));
	}

	protected function getForm($data) {
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
			'href' => $this->url->link('account/task', '', 'SSL')
		);

		if (!isset($this->request->get['task_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_task'),
				'href' => $this->url->link('account/task/add', '', 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_task'),
				'href' => $this->url->link('account/task/edit', 'task_id=' . $this->request->get['task_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit_task'] = $this->language->get('text_edit_task');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');



        $data['entry_supervise'] = $this->language->get('entry_supervise');
		$data['entry_edit_task'] = $this->language->get('entry_edit_task');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_excutor'] = $this->language->get('entry_excutor');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_sort'] = $this->language->get('entry_sort');
		$data['entry_customer_creation_id'] = $this->language->get('entry_customer_creation_id');


		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_customer_creation_id'] = $this->language->get('entry_customer_creation_id');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_sort'] = $this->language->get('entry_sort');
		$data['entry_project'] = $this->language->get('entry_project');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_default'] = $this->language->get('entry_default');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_upload'] = $this->language->get('button_upload');


        if (isset($this->request->get['back'])) {
            $data['back_id'] = $this->request->get['back'];
        }  else {
            $data['back_id'] = '';
        }

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['tag'])) {
			$data['error_tag'] = $this->error['tag'];
		} else {
			$data['error_tag'] = '';
		}

		if (isset($this->error['customer_creation_id'])) {
			$data['error_customer_creation_id'] = $this->error['customer_creation_id'];
		} else {
			$data['error_customer_creation_id'] = '';
		}

		if (isset($this->error['sort'])) {
			$data['error_sort'] = $this->error['sort'];
		} else {
			$data['error_sort'] = '';
		}


		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['project'])) {
			$data['error_project'] = $this->error['project'];
		} else {
			$data['error_project'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}
		
		if (!isset($this->request->get['task_id'])) {
			$data['action'] = $this->url->link('account/task/add', '', 'SSL');
		} else {
			$data['action'] = $this->url->link('account/task/edit', 'task_id=' . $this->request->get['task_id'], 'SSL');
		}

        if (isset($this->request->get['name'])) {
            $data['tag'] = $this->request->get['name'];
        } else {
            if (isset($this->request->post['tag'])) {
                $data['tag'] = $this->request->post['tag'];
            } elseif (!empty($task_info)) {
                $data['tag'] = $task_info['tag'];
            } else {
                $data['tag'] = '';
            }
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($task_info)) {
            $data['name'] = $task_info['name'];
        } else {
            $data['name'] = '';
        }

		if (isset($this->request->get['task_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$task_info = $this->model_account_task->getPostTask($this->request->get['task_id']);
		}





		if (isset($this->request->post['tag'])) {
			$data['priority'] = $this->request->post['priority'];
		} elseif (!empty($task_info)) {
			$data['priority'] = $task_info['priority'];
		} else {
			$data['priority'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($task_info)) {
			$data['status'] = $task_info['status'];
		} else {
			$data['status'] = '';
		}


		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($task_info)) {
			$data['description'] = $task_info['description'];
		} else {
			$data['description'] = '';
		}



		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($task_info)) {
			$this->load->model('account/customer');
			$customer_id = $task_info['customer_id'];
			$customer = $this->model_account_customer->getCustomer($customer_id);

			$data['email'] = $customer['email'];
		} else {
			$data['email'] = '';
		}
		if (isset($this->request->post['customer_creation_id'])) {
			$data['customer_creation_id'] = $this->request->post['customer_creation_id'];
		} elseif (!empty($task_info)) {
			$data['customer_creation_id'] = $task_info['customer_creation_id'];
		} else {
			$data['customer_creation_id'] = '';
		}
		if (isset($this->request->post['date_end'])) {
			$data['date_end'] = $this->request->post['date_end'];
		} elseif (!empty($task_info)) {
			$data['date_end'] = $task_info['date_end'];
		} else {
			$data['date_end'] = '';
		}

		

		if (isset($this->request->post['sort'])) {
			$data['sort'] = $this->request->post['sort'];
		} elseif (!empty($task_info)) {
			$data['sort'] = $task_info['sort'];
		} else {
			$data['sort'] = '';
		}

		

		
		$data['statuss'] = array();
		$status = $this->language->get('data_status');
		$status_arr = explode(',',$status);
		foreach ($status_arr as $st) {
			$data['statuss'][] = array(
				'id'=>$st,
				'name'=>$this->language->get('data_status_'.$st)
			);
		}




		$data['priorities'] = array();
		$status = $this->language->get('data_priority');
		$status_arr = explode(',',$status);
		foreach ($status_arr as $st) {
			$data['priorities'][] = array(
				'id'=>$st,
				'name'=>$this->language->get('data_priority_'.$st)
			);
		}

        $data['back'] = $this->url->link('account/task', '', 'SSL');
        if (isset($this->request->get['department_id'])) {
            $department_id = $this->request->get['department_id'];
            $this->load->model('account/department');
            $department = $this->model_account_department->getCustomerByDepartmentId($department_id);
            $data['email'] = $department['email'];
            $data['department_id'] = $department_id;
            $data['readonly'] = true;
            $data['back'] = $this->url->link('account/department', 'department='.$this->request->get['back'], 'SSL');

        }
		// Custom fields
		$this->load->model('account/diy_design');

		$projects = $this->model_account_diy_design->getAllDiyDesigns();
		$data['projects'] = $projects;
		



		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/task_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/task_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/task_form.tpl', $data));
		}
	}

	protected function validateForm() {
		if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen(trim($this->request->post['tag'])) < 1) || (utf8_strlen(trim($this->request->post['tag'])) > 32)) {
			$this->error['tag'] = $this->language->get('error_tag');
		}

		if (empty($this->request->post['customer_creation_id'])) {
			$this->error['customer_creation_id'] = $this->language->get('error_customer_creation_id');
		}






		if (!isset($this->request->post['email']) || $this->request->post['email']=='') {
			$this->error['email'] = $this->language->get('error_empty_email');
		}else{
			$this->load->model('account/customer');
            $customer = $this->model_account_customer->getCustomersByInfo($this->request->post['email']);

			if(empty($customer)){
				$this->error['email'] = $this->language->get('error_email');
			}else{
				if($customer['customer_id']==$this->customer->getId()){
					$this->error['email'] = $this->language->get('error_task_self');
				}else{
					$this->request->post['customer_id'] = $customer['customer_id'];
				}
			}
		}


		// Custom field validation

		
		return !$this->error;
	}


}
