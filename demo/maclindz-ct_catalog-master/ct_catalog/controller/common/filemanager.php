<?php
/*
require_once(DIR_APPLICATION.'controller/uploader/autoload.php');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;*/
require DIR_SYSTEM.'library/aws/aws-autoloader.php';
class ControllerCommonFileManager extends Controller {

    public $S3BUCKET = 'static.icootoo.com';
    public $AWSACCESSKEY = 'AKIAOZKKEKG7GK5MPI6A';
    public $AWSSECRETKEY ='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';
	public function index() {
		$this->load->language('common/filemanager');
        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
		if (isset($this->request->get['filter_name'])) {
			$filter_name = rtrim(str_replace(array('../', '..\\', '..', '*'), '', $this->request->get['filter_name']), '/');
		} else {
			$filter_name = null;
		}

		
		// Make sure we have the correct directory
		

		$directory = 0;
		$parent_path = 0;
		// Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$this->session->data['filemanager_directory'] =$this->request->get['directory'];
			$directory = $this->getCurrentDirectory($this->request->get['directory']);
			$parent_path =$this->request->get['directory'];

		} else {
			$directory = 0;
			$parent_path = 0;
			if(isset($this->session->data['filemanager_directory'])&&!empty($this->session->data['filemanager_directory'])){
				$directory = $this->getCurrentDirectory($this->session->data['filemanager_directory']);
				$parent_path =$this->session->data['filemanager_directory'];
			}
		}


        if (isset($this->request->get['share'])&&$this->request->get['share']) {
            $share = $this->request->get['share'];
        } else {
            $share = 0;
        }

        if (isset($this->request->get['init'])&&$this->request->get['init']) {
            $init = $this->request->get['init'];
        } else {
            $init = 0;
        }

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['images'] = array();

		$this->load->model('tool/image');
		$this->load->model('catalog/directory');

        $filter_data = array(
            'filter_name'	  => $filter_name,
            'filter_directory_id'	  => $directory,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

		// Get directories
        if($init)
        {
            $data['images'] = $this->getDefaultDir();
            $image_total = 2;

        }
        else
        {
            if($share&&!$directory)
            {
                $images = $this->model_catalog_directory->getShareDirectories($filter_data);
                $image_total = $this->model_catalog_directory->getTotalShareDirectories($filter_data);
            }
            else
            {
                $images = $this->model_catalog_directory->getDirectories($filter_data);
                $image_total = $this->model_catalog_directory->getTotalDirectories($filter_data);
            }
            if (!$images) {
                $images = array();
            }
            foreach ($images as $image) {
                $name = $image['name'];
                $filename = $image['filename'];
                $directory = $this->model_catalog_directory->getDirectory($image['directory_id']);

                if ($image['type']) {  //如果是目录
                    $url = '';

                    if (isset($this->request->get['target'])) {
                        $url .= '&target=' . $this->request->get['target'];
                    }

                    if (isset($this->request->get['thumb'])) {
                        $url .= '&thumb=' . $this->request->get['thumb'];
                    }

                    if($share)
                    {
                        $url .= '&share=1';
                    }
                    $data['images'][] = array(
                        'thumb' => '',
                        'name'  => $filename,
                        'modify_action' => "<a class=\"modify_action\" title=\"".$this->language->get('text_modify_name')."\" onClick=\"modifyName('".$image['directory_id']."');return false;\" href=".$this->url->link('common/filemanager/modify_name', 'path_id='.$image['directory_id'])."><i class=\"fa design-item-icon fa-pencil\"></i></a>" ,

                        'type'  => 'directory',
                        'path'  => $image['directory_id'],
                        'dir_name'=>$directory['path'],
                        'date_added' =>date('Y-m-d',strtotime($image['date_added'])),
                        'href'  => $this->url->link('common/filemanager', 'directory=' . $parent_path.'_'.$image['directory_id'] . $url, '')
                    );
                } else {

                    $data['images'][] = array(
                        'thumb' => HTTPS_SERVER.'pub_image/'.$image['thumb'],
                        'name'  => $filename,
                        'modify_action' => "<a class=\"modify_action\" title=\"".$this->language->get('text_modify_name')."\" onClick=\"modifyName('".$image['directory_id']."');return false;\" href=".$this->url->link('common/filemanager/modify_name', 'path_id='.$image['directory_id'])."><i class=\"fa design-item-icon fa-pencil\"></i></a>" ,
                        'type'  => 'image',
                        'size' => formatBytes($image['size']),
                        'width'  => $image['width'],
                        'height'  =>  $image['height'],
                        'path'  => $image['directory_id'],
                        'dir_name'=>$directory['path'],
                        'date_added' =>date('Y-m-d',strtotime($image['date_added'])),
                        'href'  => HTTPS_SERVER.'pub_image/'.$name
                    );
                }
            }
        }

		// Split the array based on current page number and max number of items per page of 10
	


		$data['heading_title'] = $this->language->get('heading_title');
        $data['share'] = $share;
        $data['init'] = $init;

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_modify_success'] = $this->language->get('text_modify_success');
        $data['error_modify_failed'] = $this->language->get('error_modify_failed');

		$data['entry_search'] = $this->language->get('entry_search');
		$data['entry_folder'] = $this->language->get('entry_folder');

		$data['button_parent'] = $this->language->get('button_parent');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_folder'] = $this->language->get('button_folder');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_search'] = $this->language->get('button_search');


        $data['text_image_detail'] = $this->language->get('text_image_detail');
        $data['text_image_name'] = $this->language->get('text_image_name');
        $data['text_image_dir'] = $this->language->get('text_image_dir');
        $data['text_image_date'] = $this->language->get('text_image_date');
        $data['text_with_height'] = $this->language->get('text_with_height');
        $data['text_size'] = $this->language->get('text_size');
		$data['token'] = $this->session->data['token'];

		$data['directory'] = $parent_path;


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

		if (isset($this->request->get['directory'])) {
			$pos = strrpos($this->request->get['directory'], '/');

			if ($pos) {
				$url .= '&directory=' . urlencode(substr($this->request->get['directory'], 0, $pos));
			}
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

		$data['parent'] = $this->url->link('common/filemanager', 'token=' . $this->session->data['token'] . $url, '');

		// Refresh
		$url = '';

		if (isset($this->request->get['directory'])) {
			$url .= '&directory=' . urlencode($this->request->get['directory']);
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

		$data['refresh'] = $this->url->link('common/filemanager', 'token=' . $this->session->data['token'] . $url, '');

		$url = '';

		if (isset($this->request->get['directory'])) {
			$url .= '&directory=' . urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8'));
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
		$pagination->url = $this->url->link('common/filemanager', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

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
		$data['file_breadcrumbs'][] = array(
					'text' => $this->language->get('Home'),
					'href'  => $this->url->link('common/filemanager' . '&init=1'  . $url, '')
		);

		if($share)
        {
            $data['file_breadcrumbs'][] = array(
                'text' => $this->language->get('text_share_dir'),
                'href'  => $this->url->link('common/filemanager' . '&directory=0&share=1'  . $url, '')
            );
        }
        else
        {
            if(!$init)
            {
                $data['file_breadcrumbs'][] = array(
                    'text' => $this->language->get('text_my_dir'),
                    'href'  => $this->url->link('common/filemanager' . '&directory=0'  . $url, '')
                );
            }

        }

		$file_path_arr = explode('_',$parent_path);
		$cur_path = '';
		if(!empty($file_path_arr)){
			
			foreach ($file_path_arr as $dir_id) {
				if(empty($dir_id)){
					continue;
				}
				$dir = $this->model_catalog_directory->getDirectory($dir_id);
				
				$name = $dir['name'];
                $filename = $dir['filename'];
				if($cur_path==''){
					$cur_path=$dir_id;
				}else{
					$cur_path=$cur_path.'_'.$dir_id;
				}

				if($share)
                {
                    $data['file_breadcrumbs'][] = array(
                        'text' => $filename,
                        'href'  => $this->url->link('common/filemanager', '&share=1&directory=' . urlencode($cur_path) . $url, 'SSL')
                    );
                }
                else
                {
                    $data['file_breadcrumbs'][] = array(
                        'text' => $filename,
                        'href'  => $this->url->link('common/filemanager', 'directory=' . urlencode($cur_path) . $url, 'SSL')
                    );
                }

				
				
			}
		}else{
			$data['file_breadcrumbs'][] = array(
					'text' => $filename,
					'href'  => $this->url->link('common/filemanager', 'token=' . $this->session->data['token'] . '&directory=' . urlencode($cur_path) . $url, 'SSL')
				);
		}

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/filemanager.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/filemanager.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/filemanager.tpl', $data));
        }
	}

	private function getDefaultDir()
    {
        $dirs[] = array(
            'thumb' => '',
            'name'  => $this->language->get('text_share_dir'),
            'share' =>true,
            'type'  => 'directory',
            'path'  => 0,
            'modify_action' => "" ,
            'dir_name'=>$this->language->get('text_share_dir'),
            'date_added' =>'',
            'href'  => $this->url->link('common/filemanager', 'directory=0&share=1', 'SSL')
        );

        $dirs[] = array(
            'thumb' => '',
            'name'  => $this->language->get('text_my_dir'),
            'type'  => 'directory',
            'share' =>false,
            'path'  => 0,
            'modify_action' => "" ,
            'dir_name'=>$this->language->get('text_my_dir'),
            'date_added' =>'',
            'href'  => $this->url->link('common/filemanager', 'directory=0', 'SSL')
        );
        return $dirs;
    }

    public function modify_name()
    {
        $issuccess = false;
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('catalog/directory');
            $this->model_catalog_directory->updateName($this->request->post);
            $issuccess = true;
        }
        $arr = array ('success' => $issuccess);

        $out = json_encode($arr);
        $this->response->setOutput($out);
    }
	public function upload() {
		$this->load->model('catalog/directory');
		$this->load->language('common/filemanager');

		$json = array();

		// Check user has permission


		// Make sure we have the correct directory
		$directory = 0;
		$cur_path  = 0;
		// Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$directory = $this->getCurrentDirectory($this->request->get['directory']);
			$cur_path = $this->request->get['directory'];

		} else {
			$directory = 0;
			$cur_path = 0;
			if(isset($this->session->data['filemanager_directory'])&&!empty($this->session->data['filemanager_directory'])){
				$directory = $this->getCurrentDirectory($this->session->data['filemanager_directory']);
				$cur_path = $this->session->data['filemanager_directory'];
			}
		}





        $ext='';

		if (!$json) {

			$file_ary = array();

            if (!empty($this->request->files['file']) ) {

                $file_count = count($this->request->files['file']['name']);
                $file_keys = array_keys($this->request->files['file']);
        
                for ($i=0; $i<$file_count; $i++) {
                    foreach ($file_keys as $key) {
                        $file_ary[$i][$key] = $this->request->files['file'][$key][$i];
                    }
                }
            }


            if (!empty($file_ary) ) {
                $storage_limit  = $this->session->data['CUSTOMER_STORAGE_LIMIT'];
                $storage_size  = $this->model_catalog_directory->getCurrentSize();
                foreach($file_ary as $cur_file) {

                    if (!empty($cur_file['name']) && is_file($cur_file['tmp_name'])) {
                        // Sanitize the filename
                        $filename = basename(html_entity_decode($cur_file['name'], ENT_QUOTES, 'UTF-8'));
                
                        // Validate the filename length
                        if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                            $json['error'] = $this->language->get('error_filename');
                        }
                
                        // Allowed file extension types
                        $allowed = array(
                            'jpg',

                            'jpeg',
                            'gif',
                            'png'
                        );
                        $ext = utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1));
                        if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                            $json['error'] = $this->language->get('error_filetype');
                        }


                        // Allowed file mime types
                        $allowed = array(
                            'image/jpeg',
                            'image/pjpeg',
                            'image/png',
                            'image/x-png',
                            'image/gif'
                        );
                
                        if (!in_array($cur_file['type'], $allowed)) {
                            $json['error'] = $this->language->get('error_filetype');
                        }
                
                        // Check to see if any PHP files are trying to be uploaded
                        $content = file_get_contents($cur_file['tmp_name']);
                
                        if (preg_match('/\<\?php/i', $content)) {
                            $json['error'] = $this->language->get('error_filetype');
                        }
                
                        // Return any upload error
                        if ($cur_file['error'] != UPLOAD_ERR_OK) {
                            $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                        }
                        $storage_size+=$cur_file['size'];
                    } else {
                        $json['error'] = $this->language->get('error_upload');

                    }
                }
            }
            if(!$json)
            {
                if($storage_limit<$storage_size)
                {
                    $json['error'] = $this->language->get('error_storage');
                }
            }

	        if (!$json) {

	            if ( !empty($file_ary) ) {
	                $upload_dir = DIR_IMAGE . 'upload/0';
	                foreach($file_ary as $cur_file) {
	                    $filename = basename(html_entity_decode($cur_file['name'], ENT_QUOTES, 'UTF-8'));
	                    $oriname = str_replace(strrchr($filename, "."),"",$filename);
	                   	$suffix = utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1));
	                    $filename = $cur_path.'_'.md5_file($cur_file['tmp_name']).'.'.$suffix;
	                    if ($this->model_catalog_directory->checkExist($directory,$filename)) {
							$json['error'] = $this->language->get('error_exists');
						}else{
						   
							$upload_status = $this->mv_file_to_s3($cur_file['tmp_name'] , $filename,$cur_file['type']);  //上传到亚马逊
						    
                            $im='';
                            $width=0;
                            $height = 0;
		                    if($upload_status){
                                if ($ext == 'gif') {
                                    $im = imagecreatefromgif($cur_file['tmp_name']);
                                } elseif ($ext == 'png') {
                                    $im = imagecreatefrompng($cur_file['tmp_name']);
                                    imagesavealpha($im, true);
                                } elseif ($ext == 'jpeg'||$ext == 'jpg') {
                                    $im = imagecreatefromjpeg($cur_file['tmp_name']);
                                }
                                if($im){
                                    $width=imagesx($im);
                                    $height = imagesy($im);
                                }
		                    $new_filename = $this->image_center_crop($im,200,200,$ext);
		                    imagedestroy($im);
		                    $thumb_name = 'thumb_'.$filename;
		                    $upload_status = $this->mv_file_to_s3($new_filename , $thumb_name,$cur_file['type']);  //上传缩略图到亚马逊

		                    $moved_file = $upload_dir . '/' . $thumb_name;
		                    //print_r($moved_file);
		                    copy($new_filename, $moved_file);
		                    unlink($new_filename);
		                    	$data = array(
									'name' => $filename,
									'thumb' =>$thumb_name,
									'filename'=>$oriname,
									'size'=>$cur_file['size'],
									'width'=>$width,
									'height'=>$height,
									'parent_id' =>  $directory,
									'sort_order' =>  0,
									'type' =>  0
								);
		                    	$upload_dir = DIR_IMAGE . 'upload/0';
		                    	$moved_file = $upload_dir . '/' . $filename;
		                    	move_uploaded_file($cur_file['tmp_name'], $moved_file);
								$this->model_catalog_directory->addDirectory($data);
								$json['success'] = $this->language->get('text_uploaded');
		                    }else{
		                    	 $json['error'] = $this->language->get('error_upload');
		                    }
						}         
			        }
			    }
	        }
    	}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


    public function sectionImageUpload() {
        $this->load->model('catalog/directory');
        $this->load->model('account/section');
        $this->load->language('common/filemanager');
        $json = array();

        // Check user has permission


        // Make sure we have the correct directory
        $directory = 0;
        $cur_path  = 0;
        // Make sure we have the correct directory
        if (!$this->request->get['section_id']) {
            $json['status'] = 0;
            $json['msg'] = 'no section id';
            die(json_encode($json));
        }
        $section_id = $this->request->get['section_id'];
        $ext='';

        if (!$json) {

            $file_ary = $this->request->files;


            if (!empty($file_ary) ) {
                $storage_limit  = $this->session->data['CUSTOMER_STORAGE_LIMIT'];
                $storage_size  = $this->model_catalog_directory->getCurrentSize();
                foreach($file_ary as $cur_file) {

                    if (!empty($cur_file['name']) && is_file($cur_file['tmp_name'])) {
                        // Sanitize the filename
                        $filename = basename(html_entity_decode($cur_file['name'], ENT_QUOTES, 'UTF-8'));

                        // Validate the filename length
                        if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                            $json['error'] = $this->language->get('error_filename');
                        }

                        // Allowed file extension types
                        $allowed = array(
                            'jpg',

                            'jpeg',
                            'gif',
                            'png'
                        );
                        $ext = utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1));
                        if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                            $json['error'] = $this->language->get('error_filetype');
                        }


                        // Allowed file mime types
                        $allowed = array(
                            'image/jpeg',
                            'image/pjpeg',
                            'image/png',
                            'image/x-png',
                            'image/gif'
                        );

                        if (!in_array($cur_file['type'], $allowed)) {
                            $json['error'] = $this->language->get('error_filetype');
                        }

                        // Check to see if any PHP files are trying to be uploaded
                        $content = file_get_contents($cur_file['tmp_name']);

                        if (preg_match('/\<\?php/i', $content)) {
                            $json['error'] = $this->language->get('error_filetype');
                        }

                        // Return any upload error
                        if ($cur_file['error'] != UPLOAD_ERR_OK) {
                            $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                        }
                        $storage_size+=$cur_file['size'];
                    } else {
                        $json['error'] = $this->language->get('error_upload');

                    }
                }
            }
            if(!$json)
            {
                if($storage_limit<$storage_size)
                {
                    //$json['error'] = $this->language->get('error_storage');
                }
            }

            if (!$json) {

                if ( !empty($file_ary) ) {
                    $upload_dir = DIR_IMAGE . 'upload/0';
                    foreach($file_ary as $cur_file) {
                        $filename = basename(html_entity_decode($cur_file['name'], ENT_QUOTES, 'UTF-8'));
                        $oriname = str_replace(strrchr($filename, "."),"",$filename);
                        $suffix = utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1));
                        $filename = $cur_path.'_'.md5_file($cur_file['tmp_name']).'.'.$suffix;
                        if ($this->model_catalog_directory->checkExist($directory,$filename)) {
                            $json['error'] = $this->language->get('error_exists');
                        }else{

                            $upload_status = $this->mv_file_to_s3($cur_file['tmp_name'] , $filename,$cur_file['type']);  //上传到亚马逊

                            $im='';
                            $width=0;
                            $height = 0;
                            if($upload_status){
                                if ($ext == 'gif') {
                                    $im = imagecreatefromgif($cur_file['tmp_name']);
                                } elseif ($ext == 'png') {
                                    $im = imagecreatefrompng($cur_file['tmp_name']);
                                    imagesavealpha($im, true);
                                } elseif ($ext == 'jpeg'||$ext == 'jpg') {
                                    $im = imagecreatefromjpeg($cur_file['tmp_name']);
                                }
                                if($im){
                                    $width=imagesx($im);
                                    $height = imagesy($im);
                                }
                                $new_filename = $this->image_center_crop($im,200,200,$ext);
                                imagedestroy($im);
                                $thumb_name = 'thumb_'.$filename;
                                $upload_status = $this->mv_file_to_s3($new_filename , $thumb_name,$cur_file['type']);  //上传缩略图到亚马逊

                                $moved_file = $upload_dir . '/' . $thumb_name;
                                //print_r($moved_file);
                                copy($new_filename, $moved_file);
                                unlink($new_filename);
                                $data = array(
                                    'name' => $filename,
                                    'thumb' =>$thumb_name,
                                    'filename'=>$oriname,
                                    'size'=>$cur_file['size'],
                                    'width'=>$width,
                                    'height'=>$height,
                                    'section_id' =>  $section_id,
                                    'sort_order' =>  0,
                                    'type' =>  0
                                );
                                $upload_dir = DIR_IMAGE . 'upload/0';
                                $moved_file = $upload_dir . '/' . $filename;
                                move_uploaded_file($cur_file['tmp_name'], $moved_file);
                                $this->model_account_section->addSectionImage($data);
                                $json['success'] = $this->language->get('text_uploaded');
                                $json['data'] = $data;
                                $json['status'] = 1;
                            }else{
                                $json['error'] = $this->language->get('error_upload');
                            }
                        }
                    }
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
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

    private function mv_file_to_s3($filename,$file,$ext){
        error_reporting(0);
        $content_type = '';


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


        $s3->putObject([
            'Bucket' => $this->S3BUCKET,
            'Key'    => $file,
            'Body'   => fopen($filename, 'r'),
            'ContentType'  => $ext,
        ]);

        return true;
        /*
        $contents = $s3->createBucket(['Bucket' => Uploader::S3BUCKET]);


        //$s3 = new S3(Uploader::AWSACCESSKEY, Uploader::AWSSECRETKEY);
        $contents = $s3->getBucket(Uploader::S3BUCKET);
        if(!$contents){
            if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ_WRITE)) {

            }else{
                return false;
            }
        }*/


    }
	public function folder() {
		$this->load->model('catalog/directory');
		$this->load->language('common/filemanager');

		$json = array();


		// Make sure we have the correct directory
		// 
		$directory = 0;
		// Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$this->session->data['filemanager_directory'] =$this->request->get['directory'];
			$directory = $this->getCurrentDirectory($this->request->get['directory']);

		} else {
			$directory = 0;
			if(isset($this->session->data['filemanager_directory'])&&!empty($this->session->data['filemanager_directory'])){
				$directory = $this->getCurrentDirectory($this->session->data['filemanager_directory']);
			}
		}
		// Check its a directory
		
		if (!$this->model_catalog_directory->isDirectory($directory)&&$directory!=0) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Sanitize the folder name
			$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((utf8_strlen($folder) < 3) || (utf8_strlen($folder) > 128)) {
				$json['error'] = $this->language->get('error_folder');
			}

			// Check if directory already exists or not
			

			if ($this->model_catalog_directory->checkExist($directory,$folder)) {
				$json['error'] = $this->language->get('error_exists');
			}
		}

		if (!$json) {
			$data = array(
				'name' => $folder,
                'filename' => $folder,
				'parent_id' =>  $directory,
				'sort_order' =>  0,
				'type' =>  1
			);
			$directory_id = $this->model_catalog_directory->addDirectory($data);
			//$json = $this->createdir($directory_id);
			//mkdir($directory . '/' . $folder, 0777);
           // print_r("echo");

			$json['success'] = $this->language->get('text_directory');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	public function createdir($directory_id){
	    $this->load->language('common/filemanager');
	    $json = array();
	    $directory = rtrim(DIR_IMAGE . 'upload/0');
	    if (!$json) {
	        // Sanitize the folder name
	        
	        $folder = $this->request->get['directory']."_".$directory_id;
	        // Check if directory already exists or not
	        if (is_dir($directory . '/' . $folder)) {
	            $json['error'] = $this->language->get('error_exists');
	        }
	    }
	    if (!$json) {
	        mkdir($directory . '/' . $folder, 0777);
	        chmod($directory . '/' . $folder, 0777);
	    }
	    return $json;
	}

	/*
		获取当前目录
	 */
   public function getCurrentDirectory($directory_path){
   		if(empty($directory_path)){
   			return 0;
   		}
   		$directories = explode('_',$directory_path);
   		return array_pop($directories);
   }






  	/*
		获取父路径
	 */
   public function getParentPath($directory_path){
   		if(empty($directory_path)){
   			return 0;
   		}
   		$directories = explode('_',$directory_path);
   		array_pop($directories);

   		if(empty($directories)){
   			return 0;
   		}
   		return implode('_', $directories);
   }

   /*
		获取父目录
	 */
   public function getParentDirectory($directory_path){
   		if(empty($directory_path)){
   			return 0;
   		}
   		$directories = explode('_',$directory_path);
   		array_pop($directories);
   		if(empty($directories)){
   			return 0;
   		}
   		return array_pop($directories);
   }

	

	public function delete() {
		$this->load->model('catalog/directory');
		$this->load->language('common/filemanager');

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
				$this->model_catalog_directory->deleteDirectory($path);
				
			}

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}