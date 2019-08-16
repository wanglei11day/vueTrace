
<?php
/*
require_once(DIR_APPLICATION.'controller/uploader/autoload.php');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;*/
require DIR_SYSTEM.'library/aws/aws-autoloader.php';
class ControllerAccountDepartment extends Controller {

    public $S3BUCKET = 'static.icootoo.com';
    public $AWSACCESSKEY = 'AKIAOZKKEKG7GK5MPI6A';
    public $AWSSECRETKEY ='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';
	public function index() {
		$this->load->language('account/department');
        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
        $this->document->addScript('catalog/view/javascript/clipboard/dist/clipboard.js');
		if (isset($this->request->get['filter_name'])) {
			$filter_name = rtrim(str_replace(array('../', '..\\', '..', '*'), '', $this->request->get['filter_name']), '/');
		} else {
			$filter_name = null;
		}

		
		// Make sure we have the correct department


		$department = 0;
		$parent_path = 0;
		// Make sure we have the correct department
		if (isset($this->request->get['department'])) {
            $parent_path =$this->request->get['department'];
            $directories = explode('_',$parent_path);
            $root_id = $directories[0]?$directories[0]:$directories[1];
			$department = $this->getCurrentDepartment($this->request->get['department']);
            array_pop($directories);
            $route_path = join('_',$directories);

		}else
        {
            $root_id = 0;
            $route_path = 0;
        }

		if (isset($this->request->post['filter_task'])) {
            $filter_task = $this->request->post['filter_task'];
		} else {
            $filter_task = '';
		}
		if($filter_task)
        {
            $this->view_tasks();
            return;
        }
        $data['search'] = false;
        $data['isCreator'] = false;
        if (isset($this->request->post['filter_member'])) {
            $filter_member = $this->request->post['filter_member'];
        } else {
            $filter_member = '';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if($this->session->data['delete_department_error'])
        {
            $data['error'] = $this->session->data['delete_department_error'];
            unset($this->session->data['delete_department_error']);
        }
		$data['images'] = array();

		$this->load->model('tool/image');
		$this->load->model('account/department');

		if(empty($filter_name)){
			$filter_data = array(
				'filter_department_id'	  => $department,
				'filter_name'=>$filter_member,
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit' => $this->config->get('config_limit_admin')
			);
		}else{
			$filter_data = array(
                'filter_department_id'	  => $department,
				'filter_name'	  => $filter_name,
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit' => $this->config->get('config_limit_admin')
			);
		}
		$level = false;

        $dp = $this->model_account_department->getDepartmentById($root_id);
		if($dp)
        {
            if($dp['customer_id'] == $this->customer->getId())
            {
                $data['isCreator'] = true;
                $level = 1;
            }
            else
            {
                $l = $this->model_account_department->getMyDepartmentLevel($root_id);
                $level = $l['level'];
            }

            $c = $this->model_account_department->getMyDepartmentLevel2($department);
            $c_level = $c['level'];
        }
		// Get directories
        if($department)
        {
            if($filter_member)
            {
                $data['search'] = true;
                $images = $this->model_account_department->searchDepartment($filter_data);
            }
            else
            {

                $images = $this->model_account_department->getDepartments($filter_data);
            }

        }
        else
        {
            $images = $this->model_account_department->getMyRootDepartments();
        }


		if (!$images) {
			$images = array();
		}

		// Get files
		//$files = glob($department . '/' . '*'.$filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
		//

		// Merge directories and files
		// Get total number of files and directories
		//
		//

		$image_total = $this->model_account_department->getTotalDepartments($filter_data);
		// Split the array based on current page number and max number of items per page of 10

		foreach ($images as $image) {
			$name = $image['name'];

			$show_create_button = false;
            $member_id = $image['member_id'];
            if($image['type']==0)
            {
                if(!$level||(($c_level+1)>$level&&$member_id!=$this->customer->getId()))
                {
                    $show_create_button = true;
                }
            }

			if ($image['type']) {  //如果是目录
				$url = '';
				if (isset($this->request->get['target'])) {
					$url .= '&target=' . $this->request->get['target'];
				}

				if (isset($this->request->get['thumb'])) {
					$url .= '&thumb=' . $this->request->get['thumb'];
				}

                $task_projects = $this->model_account_department->getDepartmentTaskProjects($image['department_id']);
                $tids = '';
                foreach ($task_projects as $task)
                {
                    if(!$tids)
                    {
                        $tids=$task['task_id'];
                    }else
                    {
                        $tids.=(','.$task['task_id']);
                    }
                }

				$data['departments'][] = array(
					'thumb' => '',
					'name'  => $name,
                    'level' =>$image['level'],
                    'member_id' => $image['member_id'],
                    'parent_id'=>$image['parent_id'],
                    'tids'=>$tids,
                    'task_projects' => $show_create_button?$task_projects:[],
                    'department_id'=>$image['department_id'],
					'type'  => 'department',
					'show'  => $show_create_button,
					'is_self' => false,
					'path'  => $image['department_id'],
					'href'  => $this->url->link('account/department', '&department=' . $parent_path.'_'.$image['department_id'] . $url, 'SSL')
				);
			} else {
                if($level&&$level>($c_level+1))
                {
                    continue;
                }
				// Find which protocol to use to pass the full image link back
				if ($this->request->server['HTTPS']) {
					$server = HTTPS_CATALOG;
				} else {
					$server = HTTP_CATALOG;
				}

                $member_id = $image['member_id'];
				if($filter_member)
                {
                    $level_1= $this->model_account_department->getDepartMentWithLevel($image['department_id'],0);
                    $task_projects = $this->model_account_department->searchTaskProjects($department/*$level_1['path_id']*/);
                    $show_create_button = true;
                }
                else
                {
                    $task_projects = $this->model_account_department->getTaskProjects($member_id,$image['department_id']);
                }


                $tids = '';
                foreach ($task_projects as $task)
                {
                    if(!$tids)
                    {
                        $tids=$task['task_id'];
                    }else
                    {
                        $tids.=(','.$task['task_id']);
                    }
                }
                $is_self = ($image['member_id'] == $this->customer->getId())?true:false;
				$data['members'][] = array(
				    'thumb' => HTTPS_SERVER.'pub_image/'.$image['thumb'],
					'name'  => $name,
                    'level' =>$image['level'],
                    'parent_id'=>$image['parent_id'],
                    'department_id'=>$image['department_id'],
                    'tids'=>$tids,
                    'task_projects' => ($show_create_button||$is_self)?$task_projects:[],
                    'member_id' => $image['member_id'],
                    'show'  => $show_create_button,
					'type'  => 'image',
					'is_self'=>$is_self,
					'path'  => $image['department_id'],
				    'href'  => HTTPS_SERVER.'pub_image/'.$name
				);
			}
		}

		//print_r($data['members']);
        $data['cur_department'] = $this->model_account_department->getDepartment($department);
        $data['text_create'] = $this->language->get('text_create');
        $data['entry_department'] = $this->language->get('entry_department');
        $data['text_invite'] = $this->language->get('text_invite');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['button_delete_department'] = $this->language->get('button_delete_department');
        $data['text_update'] = $this->language->get('text_update');
        $data['button_delete'] = $this->language->get('button_delete');

		$data['heading_title'] = $this->language->get('heading_title');
        $data['text_search'] = $this->language->get('text_search');

        $data['text_search_member'] = $this->language->get('text_search_member');
        $data['text_search_task'] = $this->language->get('text_search_task');
        $data['text_search_member_holder'] = $this->language->get('text_search_member_holder');
        $data['text_search_task_holder'] = $this->language->get('text_search_task_holder');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_empty'] = $this->language->get('text_empty');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_invite'] = $this->language->get('entry_invite');
		$data['entry_search'] = $this->language->get('entry_search');
		$data['entry_folder'] = $this->language->get('entry_folder');
        $data['text_invite'] = $this->language->get('text_invite');
		$data['button_parent'] = $this->language->get('button_parent');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_folder'] = $this->language->get('button_folder');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_search'] = $this->language->get('button_search');


		$data['department'] = $parent_path;
		$data['filter_member'] = $filter_member;

        $data['filter_task'] = $filter_task;
		if (isset($this->request->get['filter_name'])) {
			$data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$data['filter_name'] = '';
		}

		// Return the target ID for the file manager to set the value
		if (isset($this->request->get['target'])) {
			$data['target'] = $this->request->get['target'];
		} else {
			$data['target'] = '';
		}

		// Return the thumbnail for the file manager to show a thumbnail
		if (isset($this->request->get['thumb'])) {
			$data['thumb'] = $this->request->get['thumb'];
		} else {
			$data['thumb'] = '';
		}

		// Parent
		$url = '';

		if (isset($this->request->get['department'])) {
			$pos = strrpos($this->request->get['department'], '/');

			if ($pos) {
				$url .= '&department=' . urlencode(substr($this->request->get['department'], 0, $pos));
			}
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

		$data['parent'] = $this->url->link('account/department', $url, 'SSL');

		// Refresh
		$url = '';

		if (isset($this->request->get['department'])) {
			$url .= '&department=' . urlencode($this->request->get['department']);
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

		$data['refresh'] = $this->url->link('account/department', $url, 'SSL');

		$url = '';

		if (isset($this->request->get['department'])) {
			$url .= '&department=' . urlencode(html_entity_decode($this->request->get['department'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}




		$pagination = new Pagination();
		$pagination->total = $image_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('account/department', $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();



		$url = '';


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}
		$data['breadcrumbs'][] = array(
					'text' => $this->language->get('Home'),
					'href'  => $this->url->link('account/department', '&department=0'  . $url, 'SSL')
		);
		$file_path_arr = explode('_',$parent_path);
		$cur_path = '';
		$dir_id = 0;
		if(!empty($file_path_arr)){

			foreach ($file_path_arr as $dir_id) {
				if(empty($dir_id)){
					continue;
				}
				$dir = $this->model_account_department->getDepartment($dir_id);

				$name = $dir['name'];
				if($cur_path==''){
					$cur_path=$dir_id;
				}else{
					$cur_path=$cur_path.'_'.$dir_id;
				}

				$data['breadcrumbs'][] = array(
					'text' => $name,
					'href'  => $this->url->link('account/department', '&department=' . urlencode($cur_path) . $url, 'SSL')
				);


			}
		}else{
			$data['breadcrumbs'][] = array(
					'text' => $name,
					'href'  => $this->url->link('account/department', '&department=' . urlencode($cur_path) . $url, 'SSL')
				);

		}
		$last_index = count($data['breadcrumbs'])-1;
		$data['group_name'] = $data['breadcrumbs'][$last_index]['text'];
        $data['text_invite_tip'] =$this->language->get('text_invite_tip');
        $data['button_copy'] =$this->language->get('button_copy');

		$data['text_invite_link'] = $this->url->link('account/department/join','id='.$department);
		if($root_id==$department)
            $route_path = 0;
        $data['delete_department'] = $this->url->link('account/department/del','department_id='.$department.'&department='.$route_path);
        $data['invite'] = $this->url->link('account/department/invite_user', 'department='.$dir_id, 'SSL');
        $data['department_id'] = $dir_id;
        $data['department_root_id'] = $this->request->get['department'];

        $data['back'] = $this->url->link('account/department', 'department='.$department, 'SSL');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/department.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/department.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/department.tpl', $data));
        }
	}


    public function  setting()
    {
        if (isset($this->request->get['department'])) {
            $department_id = $this->request->get['department'];
        } else {
            $department_id = 0;
        }

        if (isset($this->request->post['name'])) {
            $name = $this->request->post['name'];
        } else {
            $name = 0;
        }
        $this->load->model('account/department');
        $this->load->language('account/department');
        if(!$name||!$department_id)
        {
            $json['error'] =$this->language->get('text_update_error');
            echo json_encode($json);exit;
        }

        $department = $this->model_account_department->getDepartmentById($department_id);

        if($department['customer_id'] != $this->customer->getId())
        {
            $json['error'] =$this->language->get('text_privilege_error');
            echo json_encode($json);exit;
        }

       $this->model_account_department->updateDepartmentName($department_id,$name);


        $json['success'] =$this->language->get('text_update_success');
        echo json_encode($json);exit;
    }



    public function view_tasks() {
        $this->load->model('account/task');
        $this->load->language('account/department');
        $this->load->language('account/task');
        $this->document->setTitle($this->language->get('heading_title'));

        $json = array();
        $departments = $this->request->get['tids'];
        $departments = explode(',',$departments);
        $departments = join("','",$departments);
        $member_id = $this->request->get['mid'];




        if (isset($this->request->get['department'])) {
            $parent_path =$this->request->get['department'];
            $directories = explode('_',$parent_path);
            $root_id = $directories[0]?$directories[0]:$directories[1];
            $department = $this->getCurrentDepartment($this->request->get['department']);
            array_pop($directories);
            $route_path = join('_',$directories);

        }else
        {
            $root_id = 0;
            $route_path = 0;
        }

        if (isset($this->request->post['filter_task'])) {
            $filter_task = $this->request->post['filter_task'];
        } else {
            $filter_task = '';
        }

        if (isset($this->request->post['filter_name'])) {
            $filter_name = $this->request->post['filter_name'];
        } else {
            $filter_name = '';
        }


        if (isset($this->request->post['filter_member'])) {
            $filter_member = $this->request->post['filter_member'];
        } else {
            $filter_member = '';
        }





        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $filter_task&&$filter_name) {
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
            foreach($sortselected as $task_id){
                if(!$cover){
                    $cover = $task_id;
                }
                $task_design = $this->model_account_task->getTaskById($task_id);
                $content = file_get_contents($url.$task_design['save_key'].'.json');
                $json_content = json_decode($content,true);
                $objcts = $json_content['pages'];

                $type = $json_content['product']['type'];
                if ($task_id == $cover) {
                    $newdesign = $json_content;
                    $firstpages = $objcts;
                } else {
                    unset($objcts[0]);
                    $pages = array_merge($pages, $objcts);
                }

            }


            $newdesign['pages'] = array_merge($firstpages,$pages);
            $design = $this->model_account_diy_design->getDiyDesignByDiyId($task_design['customer_creation_id']);

            $design['save_key'] = md5($design['save_key'].time()).date(Ymd);

            $file_key = 'cootoo/creations/'.$design['save_key'].'.json';


            $numpages = (count($newdesign['pages'])-1)*2;

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

        $data['tip_save_project'] = $this->language->get('tip_save_project');



        $this->load->model('account/diy_design');
        $data['tasks'] = array();
        $data['tags'] = array();
        $item['c_p'] = array();
        $data['mytask_class'] =  '';
        $data['myposttask_class'] =  'active';
        $data['myhistorytask_class'] =  '';
        if($filter_task)
        {
            $filter['filter_task'] = $filter_task;
            $filter['filter_department_id'] = $department;
            $filter['filter_memeber'] = $filter_member;
            $data['tasks'] = $this->model_account_task->getDepartmentTasks($filter);
        }
        else
        {
            $data['tasks'] = $this->model_account_task->getTasksByIds($member_id,$departments);
        }
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
            );*/

            $item['actions'][] = array(
                'name' => $this->language->get('button_delete'),
                'class' => '',
                'href' => $this->url->link('account/task/delete','task_id='.$item['task_id'])
            );
            if($item['status']){
                $item['status'] = $this->language->get('data_status_'.$item['status']);
            }
            if($item['priority']){
                $item['priority'] = $this->language->get('data_priority_'.$item['priority']);
            }
        }


        $data['mytask'] = $this->url->link('account/task', '', 'SSL');
        $data['myposttask'] = $this->url->link('account/task', 'post=1', 'SSL');
        $data['myhistorytask'] = $this->url->link('account/task', 'post=2', 'SSL');
        $data['filter_task'] = $filter_task;
        $data['filter_name'] = $filter_name;
        $data['add'] = $this->url->link('account/task/add', '', 'SSL');
        $data['back'] = $this->url->link('account/department', 'department='.$department, 'SSL');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/view_tasks.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/view_tasks.tpl', $data));
        } else {

            $this->response->setOutput($this->load->view('default/template/account/view_tasks.tpl', $data));
        }
    }


    public function invite_user() {
        $this->load->model('account/department');
        $this->load->language('account/department');

        $json = array();
        $department_id = $this->request->get['department'];
        if(!$department_id)
        {

        }
        $data['join'] = $this->url->link('account/department/join', 'id='.$department_id, 'SSL');
        // Make sure we have the correct department
        //

        // Check its a department
        $data['text_join_desc'] = $this->language->get('text_join_desc');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/invite_user.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/invite_user.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/invite_user.tpl', $data));
        }
    }


    public function bat_join() {
        $department_id = $this->request->post['department'];
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/department/join', 'id='.$department_id, 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->load->model('account/department');
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
                $name = $exist['firstname'].' '.$exist['lastname'];
            }
            //$name = $this->customer->getFirstName().' '.$this->customer->getLastName();
            if (!$this->model_account_department->checkExist($department_id,$customer_id)) {
                $department = $this->model_account_department->getDepartmentById($department_id);
                $data = array(
                    'name' => $name,
                    'customer_id'=>$department['customer_id'],
                    'member_id' =>$customer_id,
                    'email' => $email,
                    'type'=>0,
                    'parent_id' =>  $department_id
                );
                $this->model_account_department->addDepartment($data);
            }
        }
        die(json_encode(array('status'=>true)));

        //$this->response->redirect($this->url->link('account/department', 'department='.$path, 'SSL'));


        //print_r($data);

    }


    public function join() {
        $department_id = $this->request->get['id'];
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/department/join', 'id='.$department_id, 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->load->model('account/department');
        $this->load->language('account/department');
        $customer_id  = $this->customer->getId();
        $email  = $this->customer->getEmail();
$name = $this->customer->getFirstName().' '.$this->customer->getLastName();
        if ($this->model_account_department->checkExist($department_id)) {
            $data['info'] = $this->language->get('error_exists');
        }

        if (!$data['info']) {

            $department = $this->model_account_department->getDepartmentById($department_id);
            $data = array(
                'name' => $name,
                'customer_id'=>$department['customer_id'],
                'member_id' =>$customer_id,
                'email' => $email,
                'type'=>0,
                'parent_id' =>  $department_id
            );
            $this->model_account_department->addDepartment($data);
            $data['info'] = $this->language->get('text_department');
        }
        //print_r($data);

        $this->response->redirect($this->url->link('account/login', '', 'SSL'));
    }





    /**
     * 居中裁剪图片
     * @param string $source [原图路径]
     * @param int $width [设置宽度]
     * @param int $height [设置高度]
     * @param string $target [目标路径]
     * @return bool [裁剪结果]
     */
    function image_center_crop($image, $width, $height,$filetype)
    {
        $new_name = tempnam($this->config->get('DIR_UPLOAD'),'tmp_');
        $name = $new_name.'.'.$filetype;
        /* 获取图像尺寸信息 */
        $target_w = $width;
        $target_h = $height;
        $source_w = imagesx($image);
        $source_h = imagesy($image);
        /* 计算裁剪宽度和高度 */
        $judge = (($source_w / $source_h) > ($target_w / $target_h));
        $resize_w = $judge ? ($source_w * $target_h) / $source_h : $target_w;
        $resize_h = !$judge ? ($source_h * $target_w) / $source_w : $target_h;
        $start_x = $judge ? ($resize_w - $target_w) / 2 : 0;
        $start_y = !$judge ? ($resize_h - $target_h) / 2 : 0;
        /* 绘制居中缩放图像 */
        $resize_img = imagecreatetruecolor($resize_w, $resize_h);
        imagecopyresampled($resize_img, $image, 0, 0, 0, 0, $resize_w, $resize_h, $source_w, $source_h);
        $target_img = imagecreatetruecolor($target_w, $target_h);
        imagecopy($target_img, $resize_img, 0, 0, $start_x, $start_y, $resize_w, $resize_h);
        /* 将图片保存至文件 */
        switch ( $filetype ) {
            case 'gif':   imagegif($target_img, $name);    break;
            case 'jpg':  imagejpeg($target_img, $name, 100);   break;
            case 'jpeg':  imagejpeg($target_img, $name, 100);   break;
            case 'png':
                imagepng($target_img, $name, 9);
                break;
            default: return false;
        }
        imagedestroy($target_img);
        unlink($new_name);
        return $name;

    }

	public function resizeImage($im,$maxwidth,$maxheight,$filetype)
    {

        $new_name = tempnam($this->config->get('DIR_UPLOAD'),'tmp_');
        $name = $new_name.'.'.$filetype;
        $pic_width = imagesx($im);
        $pic_height = imagesy($im);
        if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
        {
            $resizewidth_tag = false;
            $resizeheight_tag = false;
            if($maxwidth && $pic_width>$maxwidth)
            {
                $widthratio = (float)$maxwidth/(float)$pic_width;
                $resizewidth_tag = true;
            }

            if($maxheight && $pic_height>$maxheight)
            {
                $heightratio = $maxheight/$pic_height;
                $resizeheight_tag = true;
            }

            if($resizewidth_tag && $resizeheight_tag)
            {
                if($widthratio<$heightratio)
                    $ratio = $widthratio;
                else
                    $ratio = $heightratio;
            }

            if($resizewidth_tag && !$resizeheight_tag)
                $ratio = $widthratio;
            if($resizeheight_tag && !$resizewidth_tag)
                $ratio = $heightratio;

            $newwidth = $pic_width * $ratio;
            $newheight = $pic_height * $ratio;

            if(function_exists("imagecopyresampled"))
            {
                $newim = imagecreatetruecolor($newwidth,$newheight);//PHPϵͳ����
                imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHPϵͳ����
            }
            else
            {
                $newim = imagecreate($newwidth,$newheight);
                imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
            }
        }else{
            $newim = $im;
        }

        switch ( $filetype ) {
            case 'gif':   imagegif($newim, $name);    break;
            case 'jpg':  imagejpeg($newim, $name, 100);   break;
            case 'jpeg':  imagejpeg($newim, $name, 100);   break;
            case 'png':
                imagepng($newim, $name, 9);
                break;
            default: return false;
        }
        imagedestroy($newim);
        unlink($new_name);
        return $name;
    }


    private function getExtension($string){
        $ext    =   "";
        try{
            $parts  =   explode(".",$string);
            $ext        =   strtolower($parts[count($parts)-1]);
        }catch(Exception $c){
            $ext    =   "";
        }
        return $ext;
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
	public function folder() {
		$this->load->model('account/department');
		$this->load->language('account/department');

		$json = array();


		// Make sure we have the correct department
		//
		$department = 0;
		// Make sure we have the correct department
		if (isset($this->request->get['department'])) {
			$this->session->data['account_department'] =$this->request->get['department'];
			$department = $this->getCurrentDepartment($this->request->get['department']);

		} else {
			$department = 0;
			if(isset($this->session->data['account_department'])&&!empty($this->session->data['account_department'])){
				$department = $this->getCurrentDepartment($this->session->data['account_department']);
			}
		}
		// Check its a department

		if (!$this->model_account_department->isDepartment($department)&&$department!=0) {
			$json['error'] = $this->language->get('error_department');
		}

		if (!$json) {
			// Sanitize the folder name
			$folder = html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8');

			// Validate the filename length

			if ((utf8_strlen($folder) < 1) || (utf8_strlen($folder) > 128)) {
				$json['error'] = $this->language->get('error_folder');
			}

			// Check if department already exists or not


			if ($this->model_account_department->checkDepartmentExist($department,$folder)) {
				$json['error'] = $this->language->get('error_exists');
			}
		}

		if (!$json) {
			$data = array(
				'name' => $folder,
                'filename' => $folder,
				'member_id' => $this->customer->getId(),
				'parent_id' =>  $department,
				'sort_order' =>  0,
				'type' =>  1
			);
			$department_id = $this->model_account_department->addDepartment($data);
			//$json = $this->createdir($department_id);
			//mkdir($department . '/' . $folder, 0777);
           // print_r("echo");

			$json['success'] = $this->language->get('text_department');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	public function sendEmail()
    {
        if (isset($this->request->get['department'])) {
           $department_id = $this->request->get['department'];
        } else {
            $department_id = 0;
        }

        if (isset($this->request->post['email'])) {
            $email = $this->request->post['email'];
        } else {
            $email = 0;
        }
        $this->load->language('account/department');
        if(!$email||!$department_id)
        {
            $json['error'] =$this->language->get('text_invite_error');
            echo json_encode($json);exit;
        }
        $invitor = $this->customer->getFirstName().' '.$this->customer->getLastName();
        $this->load->model('account/department');
        $department = $this->model_account_department->getDepartmentById($department_id);
        $department_name = $department['name'];
        $subject = sprintf($this->language->get('text_invite_subject'), $invitor,$department_name, ENT_QUOTES, 'UTF-8');
        $message = sprintf($this->language->get('text_invite_message'), $invitor,$department_name, ENT_QUOTES, 'UTF-8');
        $message .= PHP_EOL.$this->url->link('account/department/join','id='.$department_id);
        $message =  html_entity_decode($message, ENT_QUOTES, 'UTF-8');
        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($email);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setText($message);
        $mail->send();
        $json['success'] =$this->language->get('text_invite_success');
        echo json_encode($json);exit;
    }


	public function createdir($department_id){
	    $this->load->language('account/department');
	    $json = array();
	    $department = rtrim(DIR_IMAGE . 'upload/0');
	    if (!$json) {
	        // Sanitize the folder name

	        $folder = $this->request->get['department']."_".$department_id;
	        // Check if department already exists or not
	        if (is_dir($department . '/' . $folder)) {
	            $json['error'] = $this->language->get('error_exists');
	        }
	    }
	    if (!$json) {
	        mkdir($department . '/' . $folder, 0777);
	        chmod($department . '/' . $folder, 0777);
	    }
	    return $json;
	}

	/*
		获取当前目录
	 */
   public function getCurrentDepartment($department_path){
   		if(empty($department_path)){
   			return 0;
   		}
   		$directories = explode('_',$department_path);
   		return array_pop($directories);
   }






  	/*
		获取父路径
	 */
   public function getParentPath($department_path){
   		if(empty($department_path)){
   			return 0;
   		}
   		$directories = explode('_',$department_path);
   		array_pop($directories);

   		if(empty($directories)){
   			return 0;
   		}
   		return implode('_', $directories);
   }

   /*
		获取父目录
	 */
   public function getParentDepartment($department_path){
   		if(empty($department_path)){
   			return 0;
   		}
   		$directories = explode('_',$department_path);
   		array_pop($directories);
   		if(empty($directories)){
   			return 0;
   		}
   		return array_pop($directories);
   }


    public function del() {
        $this->load->model('account/department');
        $this->load->language('account/department');

        if (isset($this->request->get['department_id'])) {
            $department_id = $this->request->get['department_id'];
        } else {
            $this->response->redirect($this->url->link('account/department'));
        }

        if (isset($this->request->get['department'])) {
            $parent_id= $this->request->get['department'];
        } else {
            $parent_id = 0;
        }


        // Loop through each path to run validations

        $department = $this->model_account_department->getDepartmentById($department_id);

        if($department['customer_id'] != $this->customer->getId())
        {
            $this->session->data['delete_department_error'] =$this->language->get('text_privilege_error');
        }
        else
        {
            $this->model_account_department->deleteDepartment($department_id);
        }

        $this->response->redirect($this->url->link('account/department','department='.$parent_id));
    }

	public function delete() {
		$this->load->model('account/department');
		$this->load->language('account/department');

		$json = array();



		if (isset($this->request->post['path'])) {
			$paths = $this->request->post['path'];
		} else {
			$paths = array();
		}

		// Loop through each path to run validations


		if (!$json) {
			// Loop through each path
			foreach ($paths as $path) {
				$this->model_account_department->deleteDepartment($path);
				
			}

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}