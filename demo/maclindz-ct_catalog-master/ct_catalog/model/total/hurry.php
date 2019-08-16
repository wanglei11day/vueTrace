<?php
class ModelTotalHurry extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {

		if (isset($this->session->data['hurry'])) {
			$this->load->language('total/hurry');
			$hurry = $this->session->data['hurry'];
			
			$arr = explode(':',$hurry);
			$day_hour = array(
				'd'=>$this->language->get('text_day'),
				'h'=>$this->language->get('text_hour'),
			);
			$charge  = 0;
			if(count($arr)==3){
				$hurry_time = $arr[0];
				$hurry_time_value = substr($hurry_time,0,-1);
				$hurry_time_tag = substr($hurry_time,-1);
				if(isset($day_hour[$hurry_time_tag])){
					$hurry_percent = $arr[1];
					$hurry_min = $arr[2];
					$percent_charge = $total * $hurry_percent / 100;
					if($percent_charge <= $hurry_min){
						$charge = $hurry_min;
					}else{
						$charge = $percent_charge;
					}
				}
			}
			$total_data[] = array(
					'code'       => 'hurry',
					'title'      => $hurry_time_value .$day_hour[$hurry_time_tag].' ' .$this->language->get('text_hurry'),
					'value'      =>$charge,
					'sort_order' => $this->config->get('hurry_sort_order')
			);
            $this->session->data['hurryvalue'] = $hurry_time.' '.$charge;
			$total += $charge;
		}
	}

	
}