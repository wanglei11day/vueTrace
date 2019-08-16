<?php
class ControllerToolApi extends Controller {
	public function index() {
		
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}


    #发送验证码
    private function sendVercode($phone,$content,$number=false){
        $this->load->model('account/verify_code');
        //TODO: 这里需要做手机号的限制，每个手机号只能发3次/天

        $sns_times = $this->model_account_verify_code->countCodesByPhone($phone);
        if($sns_times>3){
            return -1;
        }
        if($number){
            $code = rand(1000,9999);
        }else{
            $code_num = rand(10,99);
            $code_str = $this->random_str(2);
            $code = $code_num.$code_str;
        }
        $this->session->data['verify_code'] = $code;
        $content = sprintf($content,$code);
        $result = $this->sendSNS($phone,$content);
        if($result['code']==0){
            $data['code'] = $code;
            $data['phone'] = $phone;
            $this->model_account_verify_code->addCode($data);
            # 这里是存放在本地服务器上，后面需要存放在 memcached 上面
            return 1;
        }else{
            return $result['msg'];
        }
    }

    private function random_str($length)
    {
        //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
        $arr = range('a', 'z');

        $str = '';
        $arr_len = count($arr);
        for ($i = 0; $i < $length; $i++)
        {
            $rand = mt_rand(0, $arr_len-1);
            $str.=$arr[$rand];
        }

        return $str;
    }

    public function sendSNS($phone,$content){
        $apikey = '4e6bfde6147560ac7aaa8e38c65787f3';
        $url = 'http://sms.yunpian.com/v2/sms/single_send.json';
        $postdata=array(
            'text'=>$content,
            'apikey'=>$apikey,
            'mobile'=>$phone
        );
        $response = post_by_curl($url,$postdata);

        $result = json_decode($response,true);
        return $result;
    }

	public function send_register_vercode(){
		$json = array();
        $this->load->language('account/register');
        $this->load->model('account/customer');
        $phone = $this->request->post['telephone'];

        $c = $this->model_account_customer->getCustomerByTelephone($phone);
        $text_phone_register =  $this->language->get('text_phone_register');
        if(!$c){
            $status = $this->sendVercode($phone,$text_phone_register); #发送 验证码
            if($status == 1){
                $json['status'] = 1;
            }elseif ($status == -1){
                $json['status'] = 0;
                $json['info'] = $this->language->get('error_sns_over3');
            }else{
                $json['status'] = 0;
                $json['info'] = $status;
            }
        }else{
            $json['status'] = 0;
            $json['info'] = $this->language->get('error_phone_exist');
        }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}



    public function send_passforget_code(){
        $json = array();
        $this->load->language('account/forgotten');
        $this->load->model('account/customer');
        $phone = $this->request->post['telephone'];

        $c = $this->model_account_customer->getCustomerByTelephone($phone);
        $text_phone_register =  $this->language->get('text_phone_register');

        if($c){
            $status = $this->sendVercode($phone,$text_phone_register,true); #发送 验证码
            if($status == 1){
                $json['status'] = 1;
            }elseif ($status == -1){
                $json['status'] = 0;
                $json['info'] = $this->language->get('error_sns_over3');
            }else{
                $json['status'] = 0;
                $json['info'] = $status;
            }
        }else{
            $json['status'] = 0;
            $json['info'] = $this->language->get('error_phone_not_exist');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }



	
	public function translate(){
		$json = array();

		$uri = API_SERVER.'/rest/v1/Translate/google';
		$postdata = array(
			'text'=>$this->request->post['text'],
			'to'=>$this->request->post['to']
		);
		$response = post_by_curl($uri,$postdata);
		/*
		$response = array(
			'status'=>1,
			'data'=>"你好"
		);*/
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
	
	public function register(){
		$json = array();
		$this->load->language('account/register');
		$uri = API_SERVER.'/rest2/v1/customer/register';
		$address_id = $this->session->data['shipping_address']['address_id'];
		$token = $this->session->data['api_token'];
		$telephone = $this->request->post['telephone_area_code'].'-'.$this->request->post['telephone'];
		$postdata = array(
			'phone'=>$telephone,
			'email'=>$this->request->post['email'],
			'pwdsha1'=>sha1($this->request->post['password']),
			'fname'=>$this->request->post['firstname'],
			'vercode'=>$this->request->post['verify_code']
		);
		
		$response = post_by_curl($uri,$postdata);

		$resarr = json_decode(trim($response,chr(239).chr(187).chr(191)),true);

		$json['status'] = $resarr['status'];
		if($resarr['status']==1){
			$this->load->model('account/customer');
			if (!$this->customer->login($telephone, $this->request->post['password'])) {
				$json['info'] = $this->language->get('error_login');
			}
			
			$vendors = $this->api->getVendors();	
			$product_vendors = $this->api->getProductVendors();	
			$this->cart->check_cart($vendors,$product_vendors);
			
			$customer_info = $this->model_account_customer->getCustomerByTelephone($telephone);
			if (empty($customer_info)) {
					$json['status'] = '100';
					$json['info'] = $this->language->get('error_register_failed');
			}
		}else{
			$json['info'] = $this->language->get('api_error_code_'.$resarr['status']);
		}
		
		if(isset($this->session->data['force_redirect'])&&!empty($this->session->data['force_redirect'])){
			$red = $this->session->data['force_redirect'];
			$this->session->data['force_redirect'] ='';
			$json['redirect'] = $red;
		}else{
			$json['redirect'] = '';
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getVendorCategory($data){
		$json = array();

		$uri = API_SERVER.'/rest/v1/Category/get_category_list_by_md5';
/*		
language_id       语言id        Y
category_md5      目录md5       N
zone_id           地区id        Y
product_filter      是否赛选产品的。1：筛选。为空不筛选。   Y
membership_level     店铺会员等级，1,2,3。为空则代表不赛选    N
*/
		$postdata = array(
			'zone_id'=>$this->session->data['zone_id'],
			'product_filter'=>$data['product_filter'],
			'language_id'=>$this->config->get('language_id'),
			'membership_level'=>$data['membership_level']
		);
		$response = post_by_curl($uri,$data);
		/*
		$response = array(
			'status'=>1,
			'data'=>"你好"
		);*/
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
}