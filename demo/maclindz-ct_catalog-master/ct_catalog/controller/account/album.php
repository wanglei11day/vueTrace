<?php
class ControllerAccountAlbum extends Controller {
    private $error = array();

    public function index() {
		ini_set('allow_url_fopen', 1);
		$this->language->load('account/album');
		$this->data['breadcrumbs'] = array();

   		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$queryFields="[]";
		$page = 0;
	
		if (isset($this->request->get['page'])) {
			$page= $this->request->get['page'];
			$this->data['page'] = $page;
		}
		$request_offset =($page - 1) * $this->config->get('config_admin_limit');
		$request_length  = $this->config->get('config_admin_limit');
		$query = "{queryFields:".$queryFields.",request_offset:".$request_offset.",request_length:".$request_length."}";
	
		$userid = $this->customer->getEmail();
		
		$current_user_id = $this->session->data["user_id"];
        $this->load->model('setting/store');
        $store_id= $this->customer->getStoreId();
		$this->load->model('setting/setting');
		$store_info = $this->model_setting_setting->getSetting('config', $store_id);
		$token="";
		$platformuri="";
		if(isset($store_info['config_opensite_id'])&&$store_info['config_opensite_key']){
			$appid = $store_info['config_opensite_id'];
			$appkey = $store_info['config_opensite_key'];
			$platformuri = $store_info['config_opensite_platform_uri'];
			if(substr( $platformuri, 0, 7 ) === "http://")
				$platformuri = $platformuri;
			else
				$platformuri = "http://".$platformuri;
			$platformuri= $platformuri{strlen($platformuri)-1} == '/' ? substr($platformuri, 0, -1) : $platformuri;
			$md5 = md5($appid.$appkey.$userid);
			$jsonarr = array ('appid' => $appid, 'userid' => $userid, 'role' => 'user' , 'md5' => $md5);
			$token = base64_encode(json_encode($jsonarr));	
		}
		$this->data['querytoken']=$token;
		$this->data['token'] = $this->session->data['token'];
		$this->data['platformuri'] = $platformuri;
		$url="";
		$this->data['queryuri'] = $platformuri."/pubaccess/user/nav/album/get";
		$this->data['albumcoveruri'] = $platformuri."/pubaccess/user/nav/album/cover";
		$url="";
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$post_data = array(
			'token'	     => $token
		);
		
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		
		$this->data['text_missing'] = $this->language->get('text_missing');
		$this->data['text_add_album'] = $this->language->get('text_add_album');
		$this->data['text_album'] = $this->language->get('text_album');
		$this->data['text_album_add_success'] = $this->language->get('text_album_add_success');
		$this->data['text_album_name'] = $this->language->get('text_album_name');
		$this->data['text_album_description'] = $this->language->get('text_album_description');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_delete'] = $this->language->get('text_delete');
		$this->data['add_album'] = $this->url->link('account/album/add', '', 'SSL');
		$album_total = $this->send_post2($platformuri."/pubaccess/user/nav/album/count/get?token=".$token);

		$pagination = new Pagination();
		$pagination->total = $album_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/album',  $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('account/home', '', 'SSL'),
      		'separator' => false
   		);
		
		$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_my_album'),
			'href'      => $this->url->link('account/album', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		$this->data['album_photo'] = $this->url->link('account/album/photo', '', 'SSL');
		$this->data['pagelimit'] = $this->config->get('config_admin_limit');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/album_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/album_list.tpl';
        } else {
            $this->template = 'default/template/account/album_list.tpl';
        }
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);	
    	$this->response->setOutput($this->render());
  	}
	
	/**
	 * 发送post请求
	 * @param string $url 请求地址
	 * @param array $post_data post键值对数据
	 * @return string
	 */
	public function send_post($url2, $post_data) {

		$postdata = http_build_query($post_data);
		$options = array(
			'http' =>array(
				'method' => 'POST',
				'header' => 'Content-type:application/x-www-form-urlencoded',
				'content' => $postdata,
				'timeout' => 15 * 60 // 超时时间（单位:s）
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url2, false, $context);

		return $result;
	}
	/**
	 * 发送post请求
	 * @param string $url 请求地址
	 * @param array $post_data post键值对数据
	 * @return string
	 */
	public function send_post2($url3) {
		$ch = curl_init();
		$timeout = 5; 
		curl_setopt ($ch, CURLOPT_URL, $url3);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
		return $file_contents;
	}
	
	public function add(){
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/album', '', 'SSL');
			
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	}			
		
		$this->language->load('account/album');
		$this->data['text_add_album'] = $this->language->get('text_add_album');
		$this->data['text_album_add_success'] = $this->language->get('text_album_add_success');
		$this->data['error_album_emptyname'] = $this->language->get('error_album_emptyname');
		$this->data['text_album_add_failed'] = $this->language->get('text_album_add_failed');
		$this->data['text_album_name'] = $this->language->get('text_album_name');
		$this->data['text_album_description'] = $this->language->get('text_album_description');
		
		$this->document->setTitle($this->language->get('heading_title'));
      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_my_album'),
			'href'      => $this->url->link('account/album', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_add_album'),
			'href'      => $this->url->link('account/album/add', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$userid = $this->customer->getEmail();
		
		$current_user_id = $this->session->data["user_id"];
        $this->load->model('setting/store');
        $store_id= $this->customer->getStoreId();
		$this->load->model('setting/setting');
		$store_info = $this->model_setting_setting->getSetting('config', $store_id);
		$token="";
		$platformuri="";
		if(isset($store_info['config_opensite_id'])&&$store_info['config_opensite_key']){
			$appid = $store_info['config_opensite_id'];
			$appkey = $store_info['config_opensite_key'];
			$platformuri = $store_info['config_opensite_platform_uri'];
			if(substr( $platformuri, 0, 7 ) === "http://")
				$platformuri = $platformuri;
			else
				$platformuri = "http://".$platformuri;
			$platformuri= $platformuri{strlen($platformuri)-1} == '/' ? substr($platformuri, 0, -1) : $platformuri;
			$md5 = md5($appid.$appkey.$userid);
			$jsonarr = array ('appid' => $appid, 'userid' => $userid, 'role' => 'user' , 'md5' => $md5);
			$token = base64_encode(json_encode($jsonarr));	
		}
		$this->data['querytoken']=$token;
		$this->data['token'] = $this->session->data['token'];
		$this->data['platformuri'] = $platformuri;
		$url="";
		$this->data['queryuri'] = $platformuri."/pubaccess/user/nav/album/add/";
		$this->data['back'] = $this->url->link('account/album', '', 'SSL');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/album_form.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/album_form.tpl';
		} else {
			$this->template = 'default/template/account/album_form.tpl';
		}
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['action'] = $this->url->link('account/album/add', '', 'SSL');
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);				
		$this->response->setOutput($this->render());
	}

	public function photo(){
		
		ini_set('allow_url_fopen', 1);
		$this->language->load('account/album');
		$this->data['breadcrumbs'] = array();

   		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$page = 0;
		if (isset($this->request->get['page'])) {
			$page= $this->request->get['page'];
			$this->data['page'] = $page;
		}
		
		$albumId = 0;
		
		if (isset($this->request->get['albumId'])) {
			$albumId= $this->request->get['albumId'];
			$this->data['albumId'] = $albumId;
		}
		$albumname="";
		if (isset($this->request->get['name'])) {
			$albumname= $this->request->get['name'];
			$this->data['albumname'] = $albumname;
		}
		$request_offset =($page - 1) * $this->config->get('config_admin_limit');
		$request_length  = $this->config->get('config_admin_limit');
	
		$userid = $this->customer->getEmail();
		
		$current_user_id = $this->session->data["user_id"];
        $this->load->model('setting/store');
        $store_id= $this->customer->getStoreId();
		$this->load->model('setting/setting');
		$store_info = $this->model_setting_setting->getSetting('config', $store_id);
		$token="";
		$platformuri="";
		if(isset($store_info['config_opensite_id'])&&$store_info['config_opensite_key']){
			$appid = $store_info['config_opensite_id'];
			$appkey = $store_info['config_opensite_key'];
			$platformuri = $store_info['config_opensite_platform_uri'];
			if(substr( $platformuri, 0, 7 ) === "http://")
				$platformuri = $platformuri;
			else
				$platformuri = "http://".$platformuri;
			$platformuri= $platformuri{strlen($platformuri)-1} == '/' ? substr($platformuri, 0, -1) : $platformuri;
			$md5 = md5($appid.$appkey.$userid);
			$jsonarr = array ('appid' => $appid, 'userid' => $userid, 'role' => 'user' , 'md5' => $md5);
			$token = base64_encode(json_encode($jsonarr));	
		}
		$this->data['userPhotoBaseUri']=$platformuri."/s3userphoto/";
		$this->data['querytoken']=$token;
		$this->data['token'] = $this->session->data['token'];
		$this->data['platformuri'] = $platformuri;
		$url="";
		$this->data['queryuri'] = $platformuri."/pubaccess/user/nav/album/photo/get";
		$url="";
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$post_data = array(
			'token'	     => $token
		);
		
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		
		$this->data['text_missing'] = $this->language->get('text_missing');

		$this->data['text_delete'] = $this->language->get('text_delete');
		$this->data['text_album'] = $this->language->get('text_album');
		$this->data['text_album_photo'] = $this->language->get('text_album_photo');
		$this->data['text_album_photo_size'] = $this->language->get('text_album_photo_size');
		
		$this->data['text_album_photo_add'] = $this->language->get('text_album_photo_add');
		$this->data['text_album_photo_name'] = $this->language->get('text_album_photo_name');
		$this->data['text_date_added'] = $this->language->get('text_date_added');

		$this->data['add_album'] = $this->url->link('account/album/add', '', 'SSL');
		$album_total = $this->send_post2($platformuri."/pubaccess/user/nav/album/photo/count/get?token=".$token."&albumId=".$albumId);
		$pagination = new Pagination();
		$pagination->total = $album_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/album/photo', 'albumId=' . $albumId ."&name=".$albumname. $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('account/home', '', 'SSL'),
      		'separator' => false
   		);
		
		$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_my_album'),
			'href'      => $this->url->link('account/album', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['breadcrumbs'][] = array(       	
        	'text'      => $albumname,
			'href'      => $this->url->link('account/album/photo&albumId='.$albumId.'&name='.$albumname ,'', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		$this->data['add_album_photo'] = $this->url->link('account/album/addphoto&albumId='.$albumId.'&name='.$albumname, '', 'SSL');
		$this->data['pagelimit'] = $this->config->get('config_admin_limit');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/album_photo_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/album_photo_list.tpl';
        } else {
            $this->template = 'default/template/account/album_photo_list.tpl';
        }
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);	
    	$this->response->setOutput($this->render());
	}	
	
	
	public function addphoto(){
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/album', '', 'SSL');
			
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	}			
		
		$this->language->load('account/album');
		$this->data['text_add_album'] = $this->language->get('text_add_album');
		$this->data['text_album_add_success'] = $this->language->get('text_album_add_success');
		$this->data['error_album_emptyname'] = $this->language->get('error_album_emptyname');
		$this->data['text_album_add_failed'] = $this->language->get('text_album_add_failed');
		$this->data['text_album_name'] = $this->language->get('text_album_name');
		$this->data['text_album_description'] = $this->language->get('text_album_description');
		
		$this->document->setTitle($this->language->get('heading_title'));
      	$this->data['breadcrumbs'] = array();
		
		$albumId = 0;
		if (isset($this->request->get['albumId'])) {
			$albumId= $this->request->get['albumId'];
			$this->data['albumId'] = $albumId;
		}
		
		$albumname="";
		if (isset($this->request->get['name'])) {
			$albumname= $this->request->get['name'];
			$this->data['albumname'] = $albumname;
		}
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_my_album'),
			'href'      => $this->url->link('account/album', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['breadcrumbs'][] = array(       	
        	'text'      => $albumname,
			'href'      => $this->url->link('account/album/photo&albumId='.$albumId.'&name='.$albumname ,'', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$userid = $this->customer->getEmail();
		
		$current_user_id = $this->session->data["user_id"];
        $this->load->model('setting/store');
        $store_id= $this->customer->getStoreId();
		$this->load->model('setting/setting');
		$store_info = $this->model_setting_setting->getSetting('config', $store_id);
		$token="";
		$platformuri="";
		if(isset($store_info['config_opensite_id'])&&$store_info['config_opensite_key']){
			$appid = $store_info['config_opensite_id'];
			$appkey = $store_info['config_opensite_key'];
			$platformuri = $store_info['config_opensite_platform_uri'];
			if(substr( $platformuri, 0, 7 ) === "http://")
				$platformuri = $platformuri;
			else
				$platformuri = "http://".$platformuri;
			$md5 = md5($appid.$appkey.$userid);
			$platformuri= $platformuri{strlen($platformuri)-1} == '/' ? substr($platformuri, 0, -1) : $platformuri;
			$jsonarr = array ('appid' => $appid, 'userid' => $userid, 'role' => 'user' , 'md5' => $md5);
			$token = base64_encode(json_encode($jsonarr));	
		}
		
		$this->data['userName']=$this->customer->getEmail();
		$this->data['photoUploadTo']="http://www.yearbook.com.tw/userphoto";
		$this->data['AmfServerUri']="http://www.yearbook.com.tw/amf";
		$this->data['querytoken']=$token;
		$this->data['accoutStatusUri']=$this->url->link('account/accountstatus', '', 'SSL');
		$this->data['token'] = $this->session->data['token'];
		$this->data['platformuri'] = $platformuri;
		$url="";
		$this->data['queryuri'] = $platformuri."/pubaccess/user/nav/album/add/";
		
		$this->data['back'] = $this->url->link('account/album/photo', 'albumId='.$albumId.'&name='.$albumname, 'SSL');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/album_add_photo.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/album_add_photo.tpl';
		} else {
			$this->template = 'default/template/account/album_add_photo.tpl';
		}
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['action'] = $this->url->link('account/album/add', '', 'SSL');
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);				
		$this->response->setOutput($this->render());
	}
}
?>