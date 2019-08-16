<?php
class ControllerCheckoutHurry extends Controller {
	public function index() {
		$this->load->language('checkout/checkout');


        $products = $this->cart->getProducts();

        $hurrys = '';
        foreach ($products as $index=>$product) {
            if($index==0){
                $hurrys = $product['rush_service'];
                continue;
            }else{
                $rush_service = $product['rush_service'];
                if($hurrys==$rush_service){
                    continue;
                }else{
                    $hurrys = '';
                    break;
                }
            }
        }

        if(!$hurrys){
            $hurrys  = $this->config->get('config_custom_rush_service');
        }

		$data['text_hurry_tip'] = $this->language->get('text_hurry_tip');
        $data['text_tip_rush_service'] = $this->language->get('text_tip_rush_service');
		$hurry_arr = explode(';',$hurrys);
		
		$data['hurrys'] = array();
		$day_hour = array(
			'd'=>$this->language->get('text_day'),
			'h'=>$this->language->get('text_hour'),
		);
		foreach($hurry_arr as $hurry){
			$arr = explode(':',$hurry);
			if(count($arr)==3){
				$hurry_time = $arr[0];
				$hurry_time_value = substr($hurry_time,0,-1);
				$hurry_time_tag = substr($hurry_time,-1);
				if(isset($day_hour[$hurry_time_tag])){
					$hurry_percent = $arr[1];
					$hurry_min = $arr[2];
					$data['hurrys'][$hurry] = sprintf($this->language->get('text_hurry'),$hurry_time_value,$day_hour[$hurry_time_tag],$hurry_percent,$this->currency->format($hurry_min));
					
				}
			}
		}
		
		$data['text_comments'] = $this->language->get('text_comments');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->session->data['hurry'])) {
			$data['hurry'] = $this->session->data['hurry'];
		} else {
			$data['hurry'] = false;
		}


		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/hurry.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/hurry.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/checkout/hurry.tpl', $data));
		}
	}

	public function save() {
		$this->load->language('checkout/checkout');

		$json = array();
		// Validate if hurry address has been set.
		if (!isset($this->request->post['hurry'])) {
			$this->session->data['hurry'] = false;
		}else{
			$this->session->data['hurry'] = $this->request->post['hurry'];
		}
		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
