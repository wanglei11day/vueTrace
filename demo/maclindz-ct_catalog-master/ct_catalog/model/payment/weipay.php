<?php 
class ModelPaymentWeipay extends Model {
	
	
	function is_mobile() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
		$is_mobile = false;
		foreach ($mobile_agents as $device) {
			if (stristr($user_agent, $device)) {
				$is_mobile = true;
				break;
			}
		}
		return $is_mobile;
	}

  	public function getMethod($address) {
		//$this->load->language('payment/weipay');

		if ($this->config->get('weipay_status')) {
			$status = TRUE;
		} else {
			$status = FALSE;
		}
		
		
		//手机端也不显示微信支付
		if( $this->is_mobile() ){
			$status = FALSE;	
		}
		
		//如果是微信浏览器 或者 手机端
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			// 非微信浏览器禁止浏览
			$status = FALSE;
		}else{
			$status = TRUE;	
		}
		
		
		
		

		$method_data = array();

		if ($status) {  
			$method_data = array( 
				'code'         => 'weipay',
				'title'      => $this->language->get('wei_text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('weipay_sort_order')
			);
		}
		return $method_data;
	}
}

?>