<?php
/**
 * @author 		tshirtecommerce - https://tshirtecommerce.com
 * @date 		June, 16, 2016
 * 
 * API 			4.1.3
 * 
 * @copyright  	Copyright (C) 2015 https://tshirtecommerce.com. All rights reserved.
 * @license    	GNU General Public License version 2 or later; see LICENSE
 *
 */

class ControllerTshirtecommerceDesigner extends Controller {

	private $error = array();

	public function index()
	{
		$data = array();

		$check = false;
		if(isset($this->request->get['color']))
		{
			$color = $this->request->get['color'];
		}
        if(isset($this->request->get['order_product_id']))
        {
            $data['order_product_id'] = $this->request->get['order_product_id'];
        }else{
            $data['order_product_id'] = '';
        }
		if(isset($this->request->get['cart_id']))
		{
			$cart_id = $this->request->get['cart_id'];
		}
		if(isset($this->request->get['edit']))
		{
			$edit = $this->request->get['edit'];
		}
        $this->load->model('tshirtecommerce/order');
        if (isset($this->request->get['debug'])) {
            $this->session->data['debug'] = 1;
        }
        else{
            $this->session->data['debug'] = 0;
        }
		if (isset($this->request->get['product_id']) && isset($this->request->get['parent_id']))
		{
			$check 		= true;
			$product_id = $this->request->get['product_id'];
			$parent_id 	= $this->request->get['parent_id'];
		}


		elseif (isset($this->request->get['design']) && isset($this->request->get['parent_id'])) {
			$check = true;
			$product_id = $this->request->get['design'];
			$parent_id = $this->request->get['parent_id'];
		}
		else
		{
			$product_id = $this->config->get('tshirtecommerce_product');
			
			if ($product_id)
			{
				$this->load->model('tshirtecommerce/order');

				$product = $this->model_tshirtecommerce_order->getProduct((int)$product_id);

				if ($product !== false)
				{
					$check 		= true;
					$product_id = $product['design_product_id'];
					$parent_id 	= $product['product_id'];
				}
			}			
		}

		if( $check == true)
		{
			$url = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? HTTPS_SERVER : HTTP_SERVER;
			$url = $this->config->get('config_secure') ? $this->config->get('config_ssl') : $this->config->get('config_url');
			
			$data['url'] = $url;
			
			$params = explode(':', $product_id);
			$view 	= '<div class="row-designer"></div>';
			if (count($params) > 1)
			{
				$data['urlDesignload'] = $url.'/tshirtecommerce/index.php?product='.$params[2].'&color='.$params[3].'&user='.$params[0].'&id='.$params[1].'&parent='.$parent_id;
				$data['product_id']		= $product_id;
				$data['parent_id']		= $parent_id;

				if(isset($cart_id))
				{
					$data['cart_id'] 		= $cart_id;
					$data['urlDesignload'] .= '&cart_id='.$cart_id;
				}
			}
			else
			{
				if(isset($color))
				{
					$data['urlDesignload'] 	= $url.'/tshirtecommerce/index.php?product='.$product_id.'&parent='.$parent_id.'&color='.$color;
					$data['color'] 			= $color;
				}
				elseif(isset($cart_id))
				{
					$data['urlDesignload'] 	= $url.'/tshirtecommerce/index.php?product='.$product_id.'&parent='.$parent_id.'&cart_id='.$cart_id;
					$data['cart_id'] 		= $cart_id;
				}
				else
				{
					$data['urlDesignload'] 	= $url.'/tshirtecommerce/index.php?product='.$product_id.'&parent='.$parent_id;
				}
				$data['product_id']	= $product_id;				
				$data['parent_id']	= $parent_id;
			}
			$data['edit'] = isset($edit) ? $edit : 0;
		}
		else
		{
			$view = 'Product Not Found.';
		}
        $product = $this->model_tshirtecommerce_order->getProduct((int)$parent_id);
        $t_max_size = $product['t_max_size'];
        $max_size = explode('x',$t_max_size);

        if(count($max_size)==2){
            $min_size = $max_size[0];
            $max_size = $max_size[1];
            $max_size = explode(':',$max_size);
            $this->session->data['t_max_width'] = $max_size[0];
            $this->session->data['t_max_height'] = $max_size[1];
            $min_size = explode(':',$min_size);
            $this->session->data['t_min_width'] = $min_size[0];
            $this->session->data['t_min_height'] = $min_size[1];
        }else{
            $this->session->data['t_min_width'] = 1;
            $this->session->data['t_min_height'] = 1;
            $this->session->data['t_max_width'] = 1920;
            $this->session->data['t_max_height'] = 1080;
        }
        $data['t_max_size'] = $t_max_size;


        $t_max_file_size = $product['t_max_file_size'];
        if($t_max_file_size){
            $this->session->data['t_max_file_size'] = $t_max_file_size;
            $data['t_max_file_size'] = $t_max_file_size;
        }else{
            unset($this->session->data['t_min_file_size']);
            unset($this->session->data['t_max_file_size']);
        }

        $this->session->data['tshirt_product_parent_id'] = $product['tshirt_category'];
        $this->session->data['tshirt_art_cate_id'] = $product['tshirt_art_cate_id'];
		$file = dirname(DIR_SYSTEM).'/tshirtecommerce/data/settings.json';

		if ( file_exists($file) )
		{
			$setting = @file_get_contents($file);
			$setting = @json_decode($setting);

			if (isset($setting->site_name))
			{
				$this->document->setTitle($setting->site_name);
			}
			
			if (isset($setting->site_name))
			{
				$this->document->setDescription($setting->meta_description);
			}
			
			if (isset($setting->site_name))
			{
				$this->document->setKeywords($setting->meta_keywords);
			}
		}
		
		$data['content'] 		= $view;

		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		
		if(mobile_check()){
			$data['footer'] 		= $this->load->controller('common/tshirt_footer');
			$data['header'] 		= $this->load->controller('common/tshirt_header');

		}else{
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/t_header');
		}

        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $this->customer->getId(),
            'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
        );

        $this->model_account_activity->addActivity('enter_teditor', $activity_data);
		$this->response->setOutput($this->load->view('default/template/tshirtecommerce/design.tpl', $data));		
	}
	
	public function ajax()
	{
		$this->load->model('tshirtecommerce/order');

		$products = $this->model_tshirtecommerce_order->getProducts();

		if ($products == 0)
		{
			return false;
		}

		$array = array('products' => array(), 'categories' => array());
		$category_id = (isset($this->request->post['id']) && $this->request->post['id'] > 0) ? $this->request->post['id'] : 0;
		if ($category_id > 0) {
			$product_to_category = array();
			$query_product_of_category = $this->db->query("
				SELECT ptc.`product_id`
				FROM `".DB_PREFIX."product_to_category` ptc
				WHERE ptc.`category_id` = '".$category_id."'
			");
			if ($query_product_of_category->num_rows) $product_to_category = $query_product_of_category->rows;
			$cproducts = array();
			if (count($product_to_category)) {
				foreach ($product_to_category as $ptc) {
					if (isset($ptc['product_id'])) $cproducts[] = $ptc['product_id'];
				}
			}
			foreach ($products as $key => $product) {
				if (!in_array($product['product_id'], $cproducts)) unset($products[$key]);
			}

			// Get sub-categories
			$language_id = $this->config->get('config_language_id');
			$query_categories = $this->db->query("
				SELECT c.`category_id` as id, c.`parent_id`, d.`name` as title
				FROM `".DB_PREFIX."category` c
				INNER JOIN `".DB_PREFIX."category_description` d 
					ON c.`category_id` = d.`category_id`
				WHERE d.`language_id` = '".$language_id."' 
					AND c.`parent_id` = '".$category_id."'
				ORDER BY c.`sort_order`
			");
			if ($query_categories->num_rows) $array['categories'] = $query_categories->rows;
		}
		
		$design_ids = array();
		$design = array();
		foreach ($products as $product) {
			$temp = explode(':', $product['design_product_id']);
			if (count($temp) == 1) {
				$design[$product['design_product_id']] = $product['product_id'];
				$design_ids[] = $product['design_product_id'];
			}			
		}
		
		$file = dirname(DIR_SYSTEM).'/tshirtecommerce/data/products.json';
		if (file_exists($file)) {			
			$string = @file_get_contents($file);
			if ($string != false) $tproducts = @json_decode($string, true);
		}
		
		if (count($tproducts['products'])) {

			foreach ($tproducts['products'] as $key => $tproduct) {
			
				if (!in_array($tproduct['id'], $design_ids)) {
					unset($tproducts[$key]);
				} else {
					$tproduct['parent_id'] = $design[$tproduct['id']];
					$tproduct['attribute'] = isset($tproduct['attributes']['name']) ? $this->getAttributes($tproduct['attributes']) : '';
					$tproduct['attribute'] .= $this->quantity($tproduct['min_order']);
					$array['products'][] = $tproduct;
				}
			}
		}

		echo @json_encode($array);
		exit();
	}
	
	// get attribute of product
	public function getAttributes($attribute)
	{
		if (isset($attribute->name) && $attribute->name != '')
		{
			$attrs = new stdClass();
			
			if (is_string($attribute->name))
				$attrs->name = json_decode($attribute->name);
			else
				$attrs->name = $attribute->name;
			
			if (is_string($attribute->titles))
				$attrs->titles = json_decode($attribute->titles);
			else
				$attrs->titles = $attribute->titles;
			
			if (is_string($attribute->prices))
				$attrs->prices = json_decode($attribute->prices);
			else
				$attrs->prices = $attribute->prices;
			
			if (is_string($attribute->type))
				$attrs->type = json_decode($attribute->type);
			else
				$attrs->type = $attribute->type;
			
			$html = '';
			for ($i=0; $i<count($attrs->name); $i++)
			{
				$html 	.= '<div class="form-group product-fields">';
				$html 	.= 		'<label for="fields">'.$attrs->name[$i].'</label>';
				
				$id 	 = 'attribute['.$i.']';
				$html 	.= 		$this->field($attrs->name[$i], $attrs->titles[$i], $attrs->prices[$i], $attrs->type[$i], $id);
				
				$html 	.= '</div>';
			}
			return $html;
		}
		else
		{
			return '';
		}
	
	}
	
	public function field($name, $title, $price, $type, $id)
	{
		$html = '<div class="dg-poduct-fields">';
		switch($type)
		{
			case 'checkbox':
				for ($i=0; $i<count($title); $i++)
				{
					$html .= '<label class="checkbox-inline">';
					$html .= 	'<input type="checkbox" name="'.$id.'['.$i.']" value="'.$i.'"> '.$title[$i];
					$html .= '</label><br />';
				}
			break;
			
			case 'selectbox':
				$html .= '<select class="form-control input-sm" name="'.$id.'">';
				
				for ($i=0; $i<count($title); $i++)
				{
					$html .= '<option value="'.$i.'">'.$title[$i].'</option>';
				}
				
				$html .= '</select><br />';
			break;
			
			case 'radio':
				for ($i=0; $i<count($title); $i++)
				{
					$html .= '<label class="radio-inline">';
					$html .= 	'<input type="radio" name="'.$id.'" value="'.$i.'"> '.$title[$i];
					$html .= '</label><br />';
				}
			break;
			
			case 'textlist':
				$html 		.= '<style>.product-quantity{display:none;}</style><ul class="p-color-sizes list-number col-md-12">';
				for ($i=0; $i<count($title); $i++)
				{
					$html .= '<li>';
					$html .= 	'<label>'.$title[$i].'</label>';
					$html .= 	'<input type="text" class="form-control input-sm size-number" name="'.$id.'['.$i.']">';					
					$html .= '</li>';
				}
				$html 		.= '</ul>';
			break;
		}
		$html	.= '</div>';
		
		return $html;
	}
	
	public function quantity($min = 1, $name = 'Quantity', $name2 = 'minimum quantity: '){
		if ($min < 1)
		{
			$min = 1;
		}
		
		$html = '<div class="form-group product-fields product-quantity">';
		$html .= 	'<label class="col-sm-4">'.$name.'</label>';
		$html .= 	'<div class="col-sm-6">';
		$html .= 		'<input type="text" class="form-control input-sm" value="0" data-count="'.$min.'" name="quantity" id="quantity">';
		$html .= 	'</div>';
		$html .= 	'<span class="help-block"><small>'.$name2.$min.'</small></span>';
		$html .= '</div>';
		
		return $html;
	}

	public function categories()
	{
		$data = array('error' => 0, 'categories' => '');
		$default_product_id = $this->session->data['tshirt_product_parent_id']?$this->session->data['tshirt_product_parent_id']:0;

		$parent_id = isset($this->request->post['parent_id']) ? $this->request->post['parent_id'] : $default_product_id;

		$language_id = $this->config->get('config_language_id');

		$categories = $this->db->query("
			SELECT c.`category_id` as id, c.`parent_id`, d.`name` as title
			FROM `".DB_PREFIX."category` c
			INNER JOIN `".DB_PREFIX."category_description` d 
				ON c.`category_id` = d.`category_id`
			WHERE d.`language_id` = '".$language_id."' 
				AND c.`parent_id` = '".$parent_id."'
			ORDER BY c.`sort_order`
		");
		if ($categories->num_rows) $data['categories'] = $categories->rows;
		echo @json_encode($data);
		return;
	}
}

?>