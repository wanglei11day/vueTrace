
<?php
/*
require_once(DIR_APPLICATION.'controller/uploader/autoload.php');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;*/
require DIR_SYSTEM.'library/aws/aws-autoloader.php';
class ControllerCommonDepartment extends Controller {

    public $S3BUCKET = 'static.icootoo.com';
    public $AWSACCESSKEY = 'AKIAOZKKEKG7GK5MPI6A';
    public $AWSSECRETKEY ='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';
	public function index() {
		$this->load->language('account/department');
        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
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
			$this->session->data['account_department'] =$this->request->get['department'];
			$department = $this->getCurrentDepartment($this->request->get['department']);
			$parent_path =$this->request->get['department'];

		} else {
			$department = 0;
			$parent_path = 0;
			if(isset($this->session->data['account_department'])&&!empty($this->session->data['account_department'])){
				$department = $this->getCurrentDepartment($this->session->data['account_department']);
				$parent_path =$this->session->data['account_department'];
			}
		}





		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['images'] = array();

		$this->load->model('tool/image');
		$this->load->model('account/department');

		if(empty($filter_name)){
			$filter_data = array(
				'filter_department_id'	  => $department,
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit' => $this->config->get('config_limit_admin')
			);
		}else{
			$filter_data = array(
				'filter_name'	  => $filter_name,
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit' => $this->config->get('config_limit_admin')
			);
		}

        
		// Get directories
        $images = $this->model_account_department->getMemberDepartments($filter_data);
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
		if(empty($filter_name)){
			$filter_data = array(
				'filter_department_id'	  => $department,
				'sort'  => $sort,
				'order' => $order,
			);
		}else{
			$filter_data = array(
				'filter_name'	  => $filter_name,
				'sort'  => $sort,
				'order' => $order,
			);
		}
		$image_total = $this->model_account_department->getTotalDepartments($filter_data);
		// Split the array based on current page number and max number of items per page of 10

		foreach ($images as $image) {
			$name = $image['name'];
			if ($image['type']) {  //如果是目录
				$url = '';

				if (isset($this->request->get['target'])) {
					$url .= '&target=' . $this->request->get['target'];
				}

				if (isset($this->request->get['thumb'])) {
					$url .= '&thumb=' . $this->request->get['thumb'];
				}


				$data['departments'][] = array(
					'thumb' => '',
					'name'  => $name,
					'type'  => 'department',
					'path'  => $image['department_id'],
					'href'  => $this->url->link('common/department', '&department=' . $parent_path.'_'.$image['department_id'] . $url, 'SSL')
				);
			} else {
				// Find which protocol to use to pass the full image link back
				if ($this->request->server['HTTPS']) {
					$server = HTTPS_CATALOG;
				} else {
					$server = HTTP_CATALOG;
				}

				$data['members'][] = array(
				    'thumb' => HTTPS_SERVER.'pub_image/'.$image['thumb'],
					'name'  => $name,
					'type'  => 'image',
					'path'  => $image['department_id'],
				    'href'  => HTTPS_SERVER.'pub_image/'.$name
				);
			}
		}
		//print_r($images);

		$data['heading_title'] = $this->language->get('heading_title');


		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
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

		$data['parent'] = $this->url->link('common/department', $url, 'SSL');

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

		$data['refresh'] = $this->url->link('common/department', $url, 'SSL');

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
		$pagination->url = $this->url->link('common/department', $url . '&page={page}', 'SSL');

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
					'href'  => $this->url->link('common/department', '&department=0'  . $url, 'SSL')
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
					'href'  => $this->url->link('common/department', '&department=' . urlencode($cur_path) . $url, 'SSL')
				);


			}
		}else{
			$data['breadcrumbs'][] = array(
					'text' => $name,
					'href'  => $this->url->link('common/department', '&department=' . urlencode($cur_path) . $url, 'SSL')
				);

		}
        $data['invite'] = $this->url->link('common/department/invite_user', 'department='.$dir_id, 'SSL');
        $data['department_id'] = $dir_id;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/department.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/department.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/department.tpl', $data));
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
        $data['join'] = $this->url->link('common/department/join', 'id='.$department_id, 'SSL');
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



    public function join() {
        $department_id = $this->request->get['id'];
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('common/department/join', 'id='.$department_id, 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->load->model('account/department');
        $this->load->language('account/department');
        $customer_id  = $this->customer->getId();
        $email = $this->customer->getEmail();
        if ($this->model_account_department->checkExist($department_id,$customer_id)) {
            $data['info'] = $this->language->get('error_exists');
        }
        if (!$data['info']) {
            $data = array(
                'name' => $email,
                'customer_id' =>$customer_id,
                'parent_id' =>  $department_id
            );
            $this->model_account_department->addDepartment($data);
            $data['info'] = $this->language->get('text_department');
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/join.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/join.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/join.tpl', $data));
        }
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

    private function mv_file_to_s3($name,$file,$ext){
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
            'Body'   => fopen($name, 'r'),
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
			$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((utf8_strlen($folder) < 3) || (utf8_strlen($folder) > 128)) {
				$json['error'] = $this->language->get('error_folder');
			}

			// Check if department already exists or not


			if ($this->model_account_department->checkExist($department,$folder)) {
				$json['error'] = $this->language->get('error_exists');
			}
		}

		if (!$json) {
			$data = array(
				'name' => $folder,
                'filename' => $folder,
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