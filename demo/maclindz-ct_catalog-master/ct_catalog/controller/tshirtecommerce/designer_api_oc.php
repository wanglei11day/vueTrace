<?php 
/**
 * @author 		tshirtecommerce - https://tshirtecommerce.com
 * @date: 		July, 05, 2016
 * 
 * API 			4.1.3
 * 
 * @copyright  	Copyright (C) 2015 https://tshirtecommerce.com. All rights reserved.
 * @license    	GNU General Public License version 2 or later; see LICENSE
 *
 */

class ControllerTshirtecommerceDesignerApiOc extends Controller
{
	private $error 	= array();

	private $plus 	= '+';
	private $minus 	= '-';

	public function index() {}

	public function get_mim_max()
	{
		$min_order = 1;
		$max_order = 99999;

		if(isset($this->request->post['id']))
		{
			$product_id = $this->request->post['id'];

			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$json = array('min_order' => isset($product_info['minimum']) ? $product_info['minimum'] : $min_order, 'max_order' => isset($product_info['quantity']) ? $product_info['quantity'] : $max_order);
		}
		else
		{
			$json = array('min_order' => 1, 'max_order' => $max_order);
		}

		echo json_encode($json);
		return;
	}
	public function get_options_oc($product_id)
	{
		// Load model needed
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$product_prices = $this->get_product_price($product_id);
		$eco = isset($product_prices['eco']) ? $product_prices['eco'] : 0;
		$product_info = $this->model_catalog_product->getProduct($product_id);

		// Get options of product
		$options = array();
		foreach ($this->model_catalog_product->getProductOptions($product_id) as $option)
		{
			$product_option_value_data = array();

			$option_values = isset($option['product_option_value']) ? $option['product_option_value'] : (isset($option['option_value']) ? $option['option_value'] : array());

			if(is_array($option_values) && count($option_values) > 0)
			{
				foreach ($option_values as $option_value)
				{
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0))
					{
						if (($this->config->get('config_customer_price') || !$this->config->get('config_customer_price')) && (float)$option_value['price'])
						{
							//$_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false) - $eco;
							$_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false);
							$price 	= $this->currency->format($_price, $this->session->data['currency']);
						}
						else
						{
							$price 	= false;
							$_price = false; // Fixed Notice: Undefined variable _price - July, 2016, 19
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'_price'				  => $_price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}
			}

			$options[] = array(
				'product_option_id'    => $option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $option['option_id'],
				'name'                 => $option['name'],
				'type'                 => $option['type'],
				'value'                => $option['value'],
				'required'             => $option['required']
			);
		}

		// Return result
		return $options;
	}

	public function render_options($product_id = 0)
	{
		if(isset($this->request->post['id'])) $product_id = $this->request->post['id'];

		$this->language->load('product/product');

		$text_option 	= $this->language->get('text_option');
		$text_select 	= $this->language->get('text_select');
		$text_loading 	= $this->language->get('text_loading');
		$button_upload 	= $this->language->get('button_upload');

		// Get base url
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
         	$site_url = $this->config->get('config_ssl');
	    }
	    else
	    {
	        $site_url = $this->config->get('config_url');
	    }

		$html = '';

		// Add style and javascript for datetimepicker
		$html .= '<link href="'.$site_url.'/tshirtecommerce/assets/js/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />';
		$html .= '<link href="'.$site_url.'/tshirtecommerce/assets/css/opencart.css" type="text/css" rel="stylesheet" />';
		$html .= '<script src="'.$site_url.'/tshirtecommerce/assets/js/datetimepicker/moment.js" type="text/javascript"></script>';
		$html .= '<script src="'.$site_url.'/tshirtecommerce/assets/js/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>';
		$html .= '<script>var _site_url = "'.$site_url.'";</script>';
		$html .= '<script src="'.$site_url.'/tshirtecommerce/assets/js/opencart.js" type="text/javascript"></script>';

		// gen html code
		$html .= '<div class="content-y"><form method="post" id="oc_tool_cart" name="oc_tool_cart" action="">';
		$html .= '<input type="hidden" id="product_id_oc" name="product_id_oc" value="'.$product_id.'" />';

		$options = $this->get_options_oc($product_id);
		if(count($options) > 0)
		{
			foreach ($options as $option)
			{
				if ($option['type'] == 'select')
				{
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label" for="input-option '.$option['product_option_id'] .'">'.$option['name'].'</label>';
	              	$html .= '<select name="option_oc['.$option['product_option_id']. ']" id="input-option_oc'.$option['product_option_id'].'" class="form-control" onchange="jQuery(\'.price-restart\').trigger(\'click\')" >';
	                $html .= '<option value="">'.$text_select.'</option>';
            		foreach ($option['product_option_value'] as $option_value)
            		{
                		$html .= '<option value="'.$option_value['product_option_value_id'].'">'.$option_value['name'];
                		if ($option_value['price'])
                		{
                			$html .= '('.$option_value['price_prefix'].$option_value['price'].')';
                		}
                		$html .= '</option>';
            		}
	              	$html .= '</select>';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'radio')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label">'.$option['name'].'</label>';
		            $html .= '<div id="input-option_oc'. $option['product_option_id'].'">';
	                foreach ($option['product_option_value'] as $option_value)
	                {
		                $html .= '<div class="radio">';
		                $html .= '<label>';
		                $html .= '<input type="radio" name="option_oc['.$option['product_option_id'].']" value="'.$option_value['product_option_value_id'] .'" onchange="jQuery(\'.price-restart\').trigger(\'click\')" />';
		                $html .= $option_value['name'];
		                if ($option_value['price'])
		                {
		                    $html .= '('.$option_value['price_prefix'].$option_value['price'].')';
		                }
		                $html .= '</label>';
		                $html .= '</div>';
	                }
		            $html .= '</div>';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'checkbox')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label">'.$option['name'].'</label>';
		            $html .= '<div id="input-option_oc'.$option['product_option_id'].'">';
		            foreach ($option['product_option_value'] as $option_value)
		            {
		                $html .= '<div class="checkbox">';
		                $html .= '<label>';
		                $html .= '<input type="checkbox" name="option_oc['.$option['product_option_id'].'][]" value="'.$option_value['product_option_value_id'].'" onchange="jQuery(\'.price-restart\').trigger(\'click\')" />';
		                if ($option_value['image'])
		                {
		                    $html .= '<img src="'.$option_value['image'].'" alt="'.$option_value['name'].($option_value['price'] ? ' '.$option_value['price_prefix'].$option_value['price'] : '').'" class="img-thumbnail" />';
		                }
	                    $html .= $option_value['name'];
	                    if ($option_value['price'])
	                    {
	                    	$html .= '('.$option_value['price_prefix']. $option_value['price'].')';
	                    }
		                $html .= '</label>';
		                $html .= '</div>';
		            }
		            $html .= '</div>';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'image')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label">'.$option['name'].'</label>';
		            $html .= '<div id="input-option_oc'.$option['product_option_id'].'">';
		            foreach ($option['product_option_value'] as $option_value)
		            {
		                $html .= '<div class="radio">';
		                $html .= '<label>';
		                $html .= '<input type="radio" name="option_oc['.$option['product_option_id'].']" value="'. $option_value['product_option_value_id'].'" />';
		                $html .= '<img src="'.$option_value['image'] .'" alt="'.$option_value['name'].($option_value['price'] ? ' '.$option_value['price_prefix'].$option_value['price'] : '').'" class="img-thumbnail" /> '. $option_value['name'];
	                    if ($option_value['price'])
	                    {
	                    	$html .= '('.$option_value['price_prefix'].$option_value['price'].')';
	                    }
		                $html .= '</label>';
		                $html .= '</div>';
		            }
		            $html .= '</div>';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'text')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label" for="input-option_oc'.$option['product_option_id'].'">'.$option['name'].'</label>';
		            $html .= '<input type="text" name="option_oc['.$option['product_option_id'].']" value="'.$option['value'].'" placeholder="'.$option['name'].'" id="input-option_oc'.$option['product_option_id'].'" class="form-control" />';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'textarea')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label" for="input-option_oc'.$option['product_option_id'].'">'.$option['name'].'</label>';
		            $html .= '<textarea style="resize:none" name="option_oc['.$option['product_option_id'].']" rows="3" placeholder="'.$option['name'].'" id="input-option_oc'.$option['product_option_id'].'" class="form-control">'.$option['value'].'</textarea>';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'file')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label">'.$option['name'].'</label>';
		            $html .= '<button type="button" id="button-upload'.$option['product_option_id'].'" data-loading-text="'.$text_loading.'" class="btn btn-default btn-block"><i class="fa fa-upload"></i> '. $button_upload.'</button>';
		            $html .= '<input type="hidden" name="option_oc['.$option['product_option_id'].']" value="" id="input-option_oc'.$option['product_option_id'].'" />';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'date')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label" for="input-option_oc'.$option['product_option_id'].'">'.$option['name'].'</label>';
		            $html .= '<div class="input-group date">';
		            $html .= '<input type="text" name="option_oc['.$option['product_option_id'].']" value="'.$option['value'].'" data-date-format="YYYY-MM-DD" id="input-option_oc'.$option['product_option_id'].'" class="form-control" />';
		            $html .= '<span class="input-group-btn">';
		            $html .= '<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>';
		            $html .= '</span></div>';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'datetime')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label" for="input-option_oc'.$option['product_option_id'].'">'.$option['name'].'</label>';
		            $html .= '<div class="input-group datetime">';
		            $html .= '<input type="text" name="option_oc['.$option['product_option_id'].']" value="'.$option['value'].'" data-date-format="YYYY-MM-DD HH:mm" id="input-option_oc'.$option['product_option_id'].'" class="form-control" />' ;
		            $html .= '<span class="input-group-btn">';
		            $html .= '<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>';
		            $html .= '</span></div>';
		            $html .= '</div>';
	            }
	            if ($option['type'] == 'time')
	            {
		            $html .= '<div class="form-group'.($option['required'] ? ' required' : '').'">';
		            $html .= '<label class="control-label" for="input-option_oc'.$option['product_option_id'].'">'.$option['name'].'</label>';
		            $html .= '<div class="input-group time">';
		            $html .= '<input type="text" name="option_oc['.$option['product_option_id'].']" value="'.$option['value'].'" data-date-format="HH:mm" id="input-option_oc'.$option['product_option_id'].'" class="form-control" /><span class="input-group-btn">';
		            $html .= '<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>';
		            $html .= '</span></div>';
		            $html .= '</div>';
	            }
			}
		}
		$html .= "</form></div>";

		// return ajax
		echo $html;
		return;
	}

	private function get_product_price($product_id, $quantity = 1)
	{
		$product_price_old 	= 0;
		$product_price_sale = 0;

		// Get taxes
		$taxes = 0;

		$this->language->load('product/product');

		$this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info)
		{
			// Get price
			if (!$this->config->get('config_customer_price'))
			{
				$price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
			}
			else
			{
				$price = 0;
			}

			// Product Discount
			$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

			if ($product_discount_query->num_rows)
			{
				$discount = $this->tax->calculate($product_discount_query->row['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
			}
			else
			{
				$discount = false;
			}

			// Product Specials
			$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

			if ($product_special_query->num_rows)
			{
				$special = $this->tax->calculate($product_special_query->row['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
			}
			else
			{
				$special = false;
			}

			$product_price_old 	= $price;
			$product_price_sale = ((float)$product_info['special'] && $special !== false) ? $special : ((isset($discount) && $discount !== false) ? $discount : $price);
		}

		// Get price (printing + cliparts) with taxes
		$rates_info = $this->tax->getRates(1, $product_info['tax_class_id']);
		$amount 	= 0;
		$eco 		= 0;
		foreach($rates_info as $rates)
		{
			if($rates['type'] == 'P') $amount 	+= $rates['amount'];
			if($rates['type'] == 'F') $eco 		+= $rates['amount'];
		}
		$taxes = $amount;

		return array(
			'price_old'	 	=> $product_price_old, 
			'price_sale' 	=> $product_price_sale, 
			'taxes' 		=> $taxes, 
			'eco' 			=> $eco
		);
	}

	// Get price for ajax
	public function get_prices()
	{
		$product_price_old 	= 0;
		$product_price_sale = 0;
		$price 				= 0;

		// get taxes
		$taxes = 0;

		// get post value from ajax
		$dataoc = isset($this->request->post['dataoc']) ? $this->request->post['dataoc'] : '';

		if(!empty($dataoc))
		{
			$dataoc = str_replace('&quot;', '"', $dataoc);
			$json 	= json_decode($dataoc, true);

			$product_quantity 	= isset($json['quantity']) ? $json['quantity'] : 0;

			// get product id
			$product_id_oc 		= isset($json['product_id_oc']) ? $json['product_id_oc'] : 0;

			if($product_id_oc == 0) 
			{
				$product_id_oc 	= isset($json['_product_id_oc']) ? $json['_product_id_oc'] : 0;
			}

			// get options
			$options = isset($json['option_oc']) ? $json['option_oc'] : array();
			foreach ($options as $key => $value)
			{
				if($value == null || empty($value))
				{
					unset($options[$key]);
				}
			}

			// get all options of product
			$options_temp = $this->get_options_oc($product_id_oc);

			// Calc price for ajax
			foreach ($options_temp as $value)
			{
				foreach ($options as $k => $option_val)
				{
					if($k == $value['product_option_id'])
					{
						if($value['type'] == 'checkbox' && is_array($option_val) && count($option_val) > 0)
						{
							foreach ($option_val as $val)
							{
								foreach ($value['product_option_value'] as $op)
								{
									// calc price
									if($val == $op['product_option_value_id'])
									{
										if($op['price_prefix'] == $this->minus)
											$price -= isset($op['_price']) ? $op['_price'] : 0; // minus price
										else
											$price += isset($op['_price']) ? $op['_price'] : 0; // plus price
									}
								}
							}
						}
						elseif($value['type'] == 'radio' || $value['type'] == 'select')
						{
							// because the $option_val is number, so do not need run foreach ($option_val as $val)
							foreach ($value['product_option_value'] as $op)
							{
								if($option_val == $op['product_option_value_id'])
								{
									if($op['price_prefix'] == $this->minus)
										$price -= isset($op['_price']) ? $op['_price'] : 0;	// minus price
									else
										$price += isset($op['_price']) ? $op['_price'] : 0;	// plus price
								}
							}
						}
						// the others have not price, so don't need calc
					}
					// if not same -> return.
				}
			}

			$product_prices 	= $this->get_product_price($product_id_oc, $product_quantity);
			$product_price_old 	= isset($product_prices['price_old']) 	? $product_prices['price_old'] 	: 0;
			$product_price_sale = isset($product_prices['price_sale']) 	? $product_prices['price_sale'] : 0;
			$taxes 				= isset($product_prices['taxes']) 		? $product_prices['taxes'] 		: 0;

		} // do nothing when empty/blank

		// return price for ajax
		echo json_encode(
			array(
				'price' 		=> $price, 
				'price_old' 	=> $product_price_old, 
				'price_sale' 	=> $product_price_sale, 
				'taxes' 		=> $taxes
			)
		);
		return;
	}

	public function get_product_id_last()
	{
		$product_id = 0;

		// Get product id last from tshirtecommerce json
		if(!defined('DS')) 		define('DS', DIRECTORY_SEPARATOR);
		if (!defined('ROOT')) define('ROOT', dirname(DIR_SYSTEM).DS.'tshirtecommerce');

		$file = ROOT . DS . 'data' . DS . 'products.json';

		if(file_exists($file))
		{
			$string = @file_get_contents($file);
			if ($string != FALSE)
			{
				$products = @json_decode($string, TRUE);
				if(isset($products['products']) && count($products['products']) > 0)
				{
					foreach ($products['products'] as $product)
					{
						if($product['id'] > $product_id) $product_id = $product['id'];
					}
				}
			}
		}

		if($product_id <= 0) echo '';
		else echo $product_id;
		
		return;
	}

	public function validate1564()
	{
		$this->language->load('checkout/cart');

		if(!defined('DS')) 		define('DS', DIRECTORY_SEPARATOR);
		if (!defined('ROOT')) define('ROOT', dirname(DIR_SYSTEM).DS.'tshirtecommerce');

		if(file_exists(ROOT.DS.'includes'.DS.'functions.php'))
		{
			include_once ROOT.DS.'includes'.DS.'functions.php';

			$dg 	= new dg();
			$lang 	= $dg->lang();

			$msg 	= $lang['tshirtecommerce_validate_sync_oc'];
		}
		else
		{
			$msg 	= 'Product Options: (*) is required.';
		}

		$json = array('error' => 0, 'msg' => $msg);

		if (isset($this->request->post['product_id_oc'])) $product_id = $this->request->post['product_id_oc'];
		else $product_id = 0;

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		// Check quantity
		if (isset($this->request->post['quantity'])) $quantity = $this->request->post['quantity'];
		else $quantity = 1;

		if($quantity < $product_info['minimum'])
		{
			$json['error'] 	= 1;
			$json['msg'] 	= $lang['tshirtecommerce_validate_out_stock_oc_min'].' '.$product_info['minimum'];

			echo json_encode($json);
			return;
		}

		if($quantity > $product_info['quantity'])
		{
			$json['error'] 	= 1;
			$json['msg'] 	= $lang['tshirtecommerce_validate_out_stock_oc'].' '.$product_info['quantity'];
			echo json_encode($json);
			return;
		}

		// Check options required
		if ($product_info)
		{
			if (isset($this->request->post['option_oc'])) 
				$option = array_filter($this->request->post['option_oc']);
			else 
				$option = array();

			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id_oc']);

			foreach ($product_options as $product_option)
			{
				if ($product_option['required'] == 1 && empty($option[$product_option['product_option_id']]))
				{
					$json['error'] 	= 1;
					$json['msg'] 	= $lang['tshirtecommerce_validate_sync_oc'];
				}	
			}
		}

		echo json_encode($json);
		return;
	}
}

?>