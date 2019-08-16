<?php
class ModelAccountDiyDesign extends Model {
    
	
	 public function insert($data) {
        $creation_id = $this->db->escape($data['creation_id']);
       
        $product_id = $data['product_id'];
        $store_id = 0;
        $save_key = $this->db->escape($data['save_key']);
        if($data['customer_id'])
        {
             $customer_id = $data['customer_id'];
        }
        else
        {
             $customer_id = $this->customer->getId();
        }
		$pages = isset($data['pages'])?$data['pages']:0;
		$image = isset($data['image'])?$data['image']:'';
		$empty_page_count = isset($data['empty_page_count'])?$data['empty_page_count']:0;
		$picqty = isset($data['picqty'])?$data['picqty']:0;
		$low_resolution_count = isset($data['low_resolution_count'])?$data['low_resolution_count']:0;
		$total_page_count = isset($data['total_page_count'])?$data['total_page_count']:0;
		if(isset($data['thumbnail'])){
			$image = $data['thumbnail'];
		}
		$add_price = $data['add_price'];
        if(isset($data['design_name'])){
			$diy_design_name =$this->db->escape($data['design_name']);
		}else{
			$diy_design_name = $this->language->get('text_default_design_name');
		}

		if(isset($data['history'])){
			$history =  $data['history'];
		}else{
			$history =  0;
		}


		if(isset($this->session->data['editorarg'])){
			$editorarg =$this->db->escape($this->session->data['editorarg']);
		}else{
			$editorarg = json_encode(array());
		}


        if (isset($data['order_id'])) {
            $order_id =$data['order_id'];
        }
        else
        {
            $order_id = 0;
        } 

		 $this->db->query("INSERT INTO " . DB_PREFIX . "customer_design SET `customer_id` = '" . 
	                    (int)$customer_id. "', `creation_id` = '".$creation_id. "', `pages` = '".$pages."', `product_id` = '".(int)$product_id."'".
	                    ", `store_id` = '".(int)$store_id."', `option` = '".$this->db->escape($data['option'])."', `order_id` = '".(int)$order_id."'".", `save_key` = '".$save_key."', `add_price` = '".$add_price."', `status` = 'designing', `name` = '".$diy_design_name. "', `editorarg` = '".$editorarg."', `image` = '".$image."', `picqty` = '".(int)$picqty."', `empty_page_count` = '".(int)$empty_page_count."', `low_resolution_count` = '".(int)$low_resolution_count."', `total_page_count` = '".(int)$total_page_count. "', date_added = NOW(), date_modified = NOW()");
         return $this->db->getLastId();
		
    }
		
		
	
	
	 public function copy($customer_creation_id) {
			$data = $this->getDiyDesignByDiyId($customer_creation_id);
			$query = $this->db->query("SELECT count(*) AS total FROM " . DB_PREFIX . "customer_design WHERE customer_id = '".$this->customer->getId()."'");   
			$total = $query->row['total'];
			$data['design_name'] = $this->language->get('text_default_design_name').$total;
			$this->insert($data);
			return $this->db->getLastId();
		
    }

    public function copydesignByCombine($data) {

        $query = $this->db->query("SELECT count(*) AS total FROM " . DB_PREFIX . "customer_design WHERE customer_id = '".$this->customer->getId()."'");
        $total = $query->row['total'];
        $data['design_name'] = $this->language->get('text_default_design_name').$total;
        $this->insert($data);
        return $this->db->getLastId();

    }





	 public function docopy($customer_creation_id) {
			$data = $this->getDiyDesignByDiyId($customer_creation_id);
			$this->insert($data);
			return $this->db->getLastId();
    }
	

     public function getHistory($customer_creation_id,$page=0,$limit = 10) {
	     $start = $page*$limit;
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design_log WHERE customer_creation_id = '".$customer_creation_id."'  ORDER BY date_added DESC LIMIT ".$start.','.$limit);

			//exit;
			return $query->rows;
			
		
    }

	
    public function copyshare($customer_creation_id) {
			$data = $this->getDiyDesignByDiyId($customer_creation_id);
			$data['design_name'] = $data['name'];
			$data['customer_id'] = $this->customer->getId();
			$this->insert($data);
			return $this->db->getLastId();
    }
	
	
    public function addDiyDesign($data) {
   
        $creation_id = $this->db->escape($data['creation_id']);
       	$customer_creation_id = $this->db->escape($data['customer_creation_id']);
       
        $product_id = $data['product_id'];
        $store_id = 0;
		 $save_key = $this->db->escape($data['save_key']);
		 
		 $pages = isset($data['pages'])?$data['pages']:0;
		 $picqty = isset($data['picqty'])?$data['picqty']:0;
		 $empty_page_count = isset($data['empty_page_count'])?$data['empty_page_count']:0;
		$low_resolution_count = isset($data['low_resolution_count'])?$data['low_resolution_count']:0;
		$total_page_count = isset($data['total_page_count'])?$data['total_page_count']:0;
		if($data['creator']){
			$customer_id = $data['creator'];
		}else{
			$customer_id = $this->customer->getId();
		}

        $customer_id = $this->customer->getId();
        
		$image = "";
		if(isset($data['thumbnail'])){
			$image = $data['thumbnail'];
		}
		$add_price = $data['add_price'];
        if(isset($data['design_name'])){
			$diy_design_name =$this->db->escape($data['design_name']);
		}else{
			$diy_design_name = $this->language->get('text_default_design_name');
		}
		if(isset($this->session->data['editorarg'])){
			$editorarg =$this->db->escape($this->session->data['editorarg']);
		}else{
			$editorarg = json_encode(array());
		}

        if (isset($data['order_id'])) {
            $order_id =$data['order_id'];
        }
        else
        {
            $order_id = 0;
        } 
        
		if($customer_id){
		if($data['action']=='edit'){



				 $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET `name` = '" .$diy_design_name."'".", `image` = '".$image."', `empty_page_count` = '".(int)$empty_page_count."', `low_resolution_count` = '".(int)$low_resolution_count."', `picqty` = '".(int)$picqty."', `total_page_count` = '".(int)$total_page_count."', `save_key` = '".$save_key."', `add_price` = '".$add_price. "', `pages` = '".$pages."' , date_modified = NOW() WHERE customer_creation_id='".$customer_creation_id. "'");


			
		}else{
			  $this->db->query("INSERT INTO " . DB_PREFIX . "customer_design SET `customer_id` = '" . 
                    (int)$customer_id. "', `creation_id` = '".$creation_id. "', `pages` = '".$pages."', `product_id` = '".(int)$product_id."'".
	                    ", `store_id` = '".(int)$store_id."', `option` = '".$this->db->escape($data['option'])."', `order_id` = '".(int)$order_id."'".", `save_key` = '".$save_key."', `add_price` = '".$add_price."', `status` = 'designing', `name` = '".$diy_design_name. "', `editorarg` = '".$editorarg."', `image` = '".$image."', `picqty` = '".(int)$picqty."', `empty_page_count` = '".(int)$empty_page_count."', `low_resolution_count` = '".(int)$low_resolution_count."', `total_page_count` = '".(int)$total_page_count. "', date_added = NOW(), date_modified = NOW()"); 
				 
				 $customer_creation_id = $this->db->getLastId();
			}
		}	
		
		 //add log
		 //
		 //
	    if(isset($data['history'])){
			$history =  $data['history'];
		}else{
			$history =  0;
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_design_log SET `customer_id` = '" . 
                    (int)$customer_id. "', `customer_creation_id` = '".$customer_creation_id. "', `creation_id` = '".$creation_id. "', `pages` = '".$pages."', `product_id` = '".(int)$product_id."'".
                    ", `store_id` = '".(int)$store_id."', `option` = '".$this->db->escape($data['option'])."', `order_id` = '".(int)$order_id."'".", `save_key` = '".$save_key."', `add_price` = '".$add_price."', `status` = 'designing', `name` = '".$diy_design_name. "', `editorarg` = '".$editorarg."', `image` = '".$image."', `picqty` = '".(int)$picqty."', `history` = '".(int)$history."', `empty_page_count` = '".(int)$empty_page_count."', `low_resolution_count` = '".(int)$low_resolution_count."', `total_page_count` = '".(int)$total_page_count. "', date_added = NOW(), date_modified = NOW()"); 
		$log_id =  $this->db->getLastId();
		$this->session->data['log_id'] = $log_id;

		return $customer_creation_id;
		
		}



    public function addBakDiyDesign($data)
    {

        $creation_id = $this->db->escape($data['creation_id']);
        $customer_creation_id = $this->db->escape($data['customer_creation_id']);

        $product_id = $data['product_id'];
        $store_id = 0;
        $save_key = $this->db->escape($data['save_key']);

        $pages = isset($data['pages']) ? $data['pages'] : 0;
        $picqty = isset($data['picqty']) ? $data['picqty'] : 0;
        $empty_page_count = isset($data['empty_page_count']) ? $data['empty_page_count'] : 0;
        $low_resolution_count = isset($data['low_resolution_count']) ? $data['low_resolution_count'] : 0;
        $total_page_count = isset($data['total_page_count']) ? $data['total_page_count'] : 0;
        if ($data['creator']) {
            $customer_id = $data['creator'];
        } else {
            $customer_id = $this->customer->getId();
        }

        $customer_id = $this->customer->getId();

        $image = "";
        if (isset($data['thumbnail'])) {
            $image = $data['thumbnail'];
        }
        $add_price = $data['add_price'];
        if (isset($data['design_name'])) {
            $diy_design_name = $this->db->escape($data['design_name']);
        } else {
            $diy_design_name = $this->language->get('text_default_design_name');
        }
        if (isset($this->session->data['editorarg'])) {
            $editorarg = $this->db->escape($this->session->data['editorarg']);
        } else {
            $editorarg = json_encode(array());
        }

        if (isset($data['order_id'])) {
            $order_id = $data['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($data['visiable'])) {
            $visiable = $data['visiable'];
        } else {
            $visiable = 1;
        }


            $res = $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET `name` = '" . $diy_design_name . "'" . ", `image` = '" . $image . "', `empty_page_count` = '" . (int)$empty_page_count . "', `low_resolution_count` = '" . (int)$low_resolution_count . "', `visiable` = '" . (int)$visiable. "', `picqty` = '" . (int)$picqty . "', `total_page_count` = '" . (int)$total_page_count . "', `save_key` = '" . $save_key . "', `add_price` = '" . $add_price . "', `pages` = '" . $pages . "' , date_modified = NOW() WHERE customer_creation_id='" . $customer_creation_id . "' AND creation_id = '" . $creation_id . "' AND save_key = '" . $save_key . "' AND bak = 1");

            if ($res !== false) {
                $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_design WHERE customer_id=" . $customer_id . " AND bak = 1  AND creation_id != '" . $creation_id . "' AND save_key = '" . $save_key . "'");
                $cc = $query->row['total'];
                if (!$cc) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "customer_design SET `customer_id` = '" .
                        (int)$customer_id . "', `creation_id` = '" . $creation_id . "', `pages` = '" . $pages . "', `product_id` = '" . (int)$product_id . "'" .
                        ", `store_id` = '" . (int)$store_id . "', `option` = '" . $this->db->escape($data['option']) . "', `order_id` = '" . (int)$order_id . "'" . ", `save_key` = '" . $save_key . "', `add_price` = '" . $add_price . "', `status` = 'designing', `name` = '" . $diy_design_name . "', `editorarg` = '" . $editorarg . "', `image` = '" . $image . "', `picqty` = '" . (int)$picqty . "', `empty_page_count` = '" . (int)$empty_page_count . "', `low_resolution_count` = '" . (int)$low_resolution_count . "', `total_page_count` = '" . (int)$total_page_count . "', bak = 1 AND date_added = NOW(), date_modified = NOW()");
                }

            }


            //add log
            //
            //
            if (isset($data['history'])) {
                $history = $data['history'];
            } else {
                $history = 0;
            }

            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_design_log SET `customer_id` = '" .
                (int)$customer_id . "', `customer_creation_id` = '" . $customer_creation_id . "', `creation_id` = '" . $creation_id . "', `pages` = '" . $pages . "', `product_id` = '" . (int)$product_id . "'" .
                ", `store_id` = '" . (int)$store_id . "', `option` = '" . $this->db->escape($data['option']) . "', `order_id` = '" . (int)$order_id . "'" . ", `save_key` = '" . $save_key . "', `add_price` = '" . $add_price . "', `status` = 'designing', `name` = '" . $diy_design_name . "', `editorarg` = '" . $editorarg . "', `image` = '" . $image . "', `picqty` = '" . (int)$picqty . "', `history` = '" . (int)$history . "', `empty_page_count` = '" . (int)$empty_page_count . "', `low_resolution_count` = '" . (int)$low_resolution_count . "', `total_page_count` = '" . (int)$total_page_count . "', date_added = NOW(), date_modified = NOW()");
            $log_id = $this->db->getLastId();
            $this->session->data['log_id'] = $log_id;

            return $customer_creation_id;


    }

    public function copyDesignFromLog(){
    	$log_id = $this->session->data['log_id'];
    	if($log_id){
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design_log WHERE customer_design_log_id = '".$log_id."'");    
        	$customer_log =  $query->row;
        	if($customer_log){
        		$this->insert($customer_log);
        	}else{
        		return false;
        	}
    	}else{
    		return false;
    	}
    }
	
	
	public function consumeTimesPlusOne($creation_id){
		 $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET order_times = order_times+1 WHERE creation_id  = '".$creation_id ."'");
	}
	
    public function getTotalDiyDesignByCustomerId($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_design WHERE customer_id=".$customer_id."   AND status != '-1'  AND visiable='1' ");
        return $query->row['total'];
    }

    public function getTaskTotalDiyDesignByCustomerId($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_design WHERE customer_id=".$customer_id."   AND status != '-1'  ");
        return $query->row['total'];
    }


    public function getDiyDesignsByCustomerId($customer_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }
        
        if ($limit < 1) {
            $limit = 1;
        }    
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design WHERE customer_id='".$this->customer->getId(). "'   AND status != '-1'  AND visiable=1  ORDER BY customer_creation_id DESC LIMIT " . (int)$start . "," . (int)$limit);
        return $query->rows;
    }


    public function getTaskDiyDesignsByCustomerId($customer_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design WHERE customer_id='".$this->customer->getId(). "'   AND status != '-1'  ORDER BY customer_creation_id DESC LIMIT " . (int)$start . "," . (int)$limit);
        return $query->rows;
    }
    

    public function getAllDiyDesigns() {
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design WHERE customer_id='".$this->customer->getId(). "'  AND status != '-1' ORDER BY customer_creation_id DESC ");
        return $query->rows;
    } 


    

	 public function getDiyDesignByCreationIdAndSaveKey($creation_id,$savekey) { 
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design WHERE creation_id= '".$creation_id."' AND save_key='".$savekey."'");    
        return $query->row;
    }  
	
	 public function getDiyDesignByDiyId($customer_creation_id,$locked = 0) {
         $locked = 0;
	    if($locked){
            $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET locked = 1 WHERE customer_creation_id= '".$customer_creation_id."'");
        }
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design WHERE customer_creation_id= '".$customer_creation_id."'");
        return $query->row;
    }

    public function unlock($customer_creation_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET locked = 0 WHERE customer_creation_id= '".$customer_creation_id."'");
    }

    public function getDiyDesignByHistoryId($history_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design_log WHERE customer_design_log_id= '".$history_id."'");    
        return $query->row;
    }
    
	
    public function updateDesignStatus($customer_creation_id, $status){
        $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET status = '" . $status . "' WHERE customer_creation_id = '" . $customer_creation_id . "'");
    }
	
	  public function updateDesignVisiable($customer_creation_id, $visiable){
        $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET visiable = '" . $visiable . "' WHERE customer_creation_id = '" . $customer_creation_id . "'");
    }
	
	public function updateName($data){
        $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET name = '" . $data['name'] . "' WHERE customer_creation_id = '" . $data['customer_creation_id'] . "' AND customer_id  = '".$this->customer->getId()."'");
    }
	
    
    public function deleteDiyDesign($customer_creation_id){
        $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET status = '-1' ,date_modified = NOW() WHERE customer_creation_id = '" . $customer_creation_id . "'");
    }
	
	
	public function updateDesignName($customer_creation_id,$name){
        $this->db->query("UPDATE " . DB_PREFIX . "customer_design SET name = '" . $name . "' WHERE customer_creation_id  = '".$customer_creation_id ."'");
    }
	
	
	public function getOptionDatas($options,$product_id){
		$option_price = 0;
		$option_one_time_price = 0;
		$option_points = 0;
		$option_weight = 0;
		$option_data = array();
		if(!$options)
			return $option_data;
		foreach ($options as $product_option_id => $value) {
			$option_query = $this->db->query("SELECT po.product_option_id, po.option_id,o.onetimefee, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY po.sort_order");
			
			if ($option_query->num_rows) {
				if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
					$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pov.sort_order");

					if ($option_value_query->num_rows) {
						if ($option_value_query->row['price_prefix'] == '+') {
							
							if ($option_query->row['onetimefee']) {
								$option_one_time_price += $option_value_query->row['price'];
							}else{
								$option_price += $option_value_query->row['price'];
							}
						} elseif ($option_value_query->row['price_prefix'] == '-') {
							if ($option_query->row['onetimefee']) {
								$option_one_time_price -= $option_value_query->row['price'];
							}else{
								$option_price -= $option_value_query->row['price'];
							}
						}
						
						

						if ($option_value_query->row['points_prefix'] == '+') {
							$option_points += $option_value_query->row['points'];
						} elseif ($option_value_query->row['points_prefix'] == '-') {
							$option_points -= $option_value_query->row['points'];
						}

						if ($option_value_query->row['weight_prefix'] == '+') {
							$option_weight += $option_value_query->row['weight'];
						} elseif ($option_value_query->row['weight_prefix'] == '-') {
							$option_weight -= $option_value_query->row['weight'];
						}

						if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < 1))) {
							$stock = false;
						}
						
						$option_data[] = array(
							'product_option_id'       => $product_option_id,
							'product_option_value_id' => $value,
							'option_id'               => $option_query->row['option_id'],
							'option_value_id'         => $option_value_query->row['option_value_id'],
							'name'                    => $option_query->row['name'],
							'value'                   => $option_value_query->row['name'],
							'type'                    => $option_query->row['type'],
							'quantity'                => $option_value_query->row['quantity'],
							'onetimefee'              => $option_query->row['onetimefee'],
							'subtract'                => $option_value_query->row['subtract'],
							'price'                   => $option_value_query->row['price'],
							'price_prefix'            => $option_value_query->row['price_prefix'],
							'points'                  => $option_value_query->row['points'],
							'points_prefix'           => $option_value_query->row['points_prefix'],
							'weight'                  => $option_value_query->row['weight'],
							'weight_prefix'           => $option_value_query->row['weight_prefix']
						);
					}
				} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
					foreach ($value as $product_option_value_id) {
						
						$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'  ORDER BY pov.sort_order ");

						if ($option_value_query->num_rows) {
							if ($option_value_query->row['price_prefix'] == '+') {
							
								if ($option_query->row['onetimefee']) {
									$option_one_time_price += $option_value_query->row['price'];
								}else{
									$option_price += $option_value_query->row['price'];
								}
							} elseif ($option_value_query->row['price_prefix'] == '-') {
								if ($option_query->row['onetimefee']) {
									$option_one_time_price -= $option_value_query->row['price'];
								}else{
									$option_price -= $option_value_query->row['price'];
								}
							}
							
							if ($option_value_query->row['points_prefix'] == '+') {
								$option_points += $option_value_query->row['points'];
							} elseif ($option_value_query->row['points_prefix'] == '-') {
								$option_points -= $option_value_query->row['points'];
							}

							if ($option_value_query->row['weight_prefix'] == '+') {
								$option_weight += $option_value_query->row['weight'];
							} elseif ($option_value_query->row['weight_prefix'] == '-') {
								$option_weight -= $option_value_query->row['weight'];
							}

							if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
								$stock = false;
							}

							$option_data[] = array(
								'product_option_id'       => $product_option_id,
								'product_option_value_id' => $product_option_value_id,
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => $option_value_query->row['option_value_id'],
								'name'                    => $option_query->row['name'],
								'value'                   => $option_value_query->row['name'],
								'type'                    => $option_query->row['type'],
								'onetimefee'              => $option_query->row['onetimefee'],
								'quantity'                => $option_value_query->row['quantity'],
								'subtract'                => $option_value_query->row['subtract'],
								'price'                   => $option_value_query->row['price'],
								'price_prefix'            => $option_value_query->row['price_prefix'],
								'points'                  => $option_value_query->row['points'],
								'points_prefix'           => $option_value_query->row['points_prefix'],
								'weight'                  => $option_value_query->row['weight'],
								'weight_prefix'           => $option_value_query->row['weight_prefix']
							);
						}
					}
				} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
					$option_data[] = array(
						'product_option_id'       => $product_option_id,
						'product_option_value_id' => '',
						'option_id'               => $option_query->row['option_id'],
						'option_value_id'         => '',
						'name'                    => $option_query->row['name'],
						'value'                   => $value,
						'type'                    => $option_query->row['type'],
						'onetimefee'              => $option_query->row['onetimefee'],
						'quantity'                => '',
						'subtract'                => '',
						'price'                   => '',
						'price_prefix'            => '',
						'points'                  => '',
						'points_prefix'           => '',
						'weight'                  => '',
						'weight_prefix'           => ''
					);
				}
			}
		}
		return $option_data;
	}
	
	
	
	public function getDesignOptions($customer_creation_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design WHERE customer_creation_id= '".$customer_creation_id."'");  
		$data_arr = array();
        if($query->row){
			$option = $query->row['option'];
			if($option){
				$data_arr = json_decode($option,true);
			}
		}
		return $data_arr;
    }


    public function getOldDesignOptions($customer_creation_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design WHERE customer_creation_id= '".$customer_creation_id."'");  
		$data_arr = array();
        if($query->row){
			$option = $query->row['option'];
			if($option){
				$st = $this->startWith($option,'ey');
				if($st){
					$optionstr = base64_decode($option);
					$n = strlen($optionstr);
					$option_str = substr($optionstr, 1, $n-2  );
					
					$arr = explode(",",$option_str);
					
					foreach($arr as $k=>$v){
						$a = explode(":",$v);
						if(isset($a[0])&&isset($a[1])){
							
							$product_option_id = html_entity_decode($a[0]);
							$product_option_id =substr($product_option_id,1,strlen($product_option_id)-2);
							$product_option_value_id = html_entity_decode($a[1]);
							$product_option_value_id =substr($product_option_value_id,1,strlen($product_option_value_id)-2);
							$data_arr[$product_option_id] =$product_option_value_id;
						}

					}
				}
			}
		}
		return $data_arr;
    }


    function startWith($str, $needle) {

	    return strpos($str, $needle) === 0;

	}



}
?>