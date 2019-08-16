<?php 
class ControllerAccountCombineDesign extends Controller {

    public function index() {  
     	
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/diydesign', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
		

       $page_count = 12;
        $this->language->load('account/diydesign');

        $this->document->setTitle($this->language->get('heading_title'));

          $data['breadcrumbs'] = array();

          $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),            
            'separator' => false
          ); 

          $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),            
            'separator' => $this->language->get('text_separator')
          );
        
        $url = '';
        
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        }else{
            $type='K';
        }
                
          $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/diydesign', $url, 'SSL'),            
            'separator' => $this->language->get('text_separator')
          );

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }


         
			
        $customerid = $this->customer->getId(); 
		$this->load->model('setting/setting');
		$store_info = $this->model_setting_setting->getSetting('config', $this->config->get('config_store_id'));
		$appid = 'lllllllkkkkk';
		$appkey = 'kkkkkkkkk';
		$platformuri = 'http://www.baidu.com';
		if(substr( $platformuri, 0, 7 ) === "http://")
			$platformuri = $platformuri;
		else
			$platformuri = "http://".$platformuri;
		$platformuri= $platformuri{strlen($platformuri)-1} == '/' ? substr($platformuri, 0, -1) : $platformuri;
		$md5 = md5($appid.$appkey.$customerid);
		$jsonarr = array ('appid' => $appid, 'userid' => $customerid, 'role' => 'user' , 'md5' => $md5);
		$platformtoken = base64_encode(json_encode($jsonarr));	

		$data['platformuri'] = $platformuri;
		$data['platformtoken'] = $platformtoken;
        $this->load->model('account/diy_design');
		if(isset($this->request->get['success'])){
			$data['text_add_cart_success'] = sprintf($this->language->get('text_add_cart_success'),$this->url->link('checkout/cart'));
		}
        $diydesign_total = $this->model_account_diy_design->getTaskTotalDiyDesignByCustomerId($customerid);
        $results = $this->model_account_diy_design->getTaskDiyDesignsByCustomerId($customerid, ($page - 1) * $page_count, $page_count);

		
		if($results){
			 foreach ($results as $result) {
				 if(empty($result['option'])){
					 $edituri = $this->url->link('product/design', 'product_id='.$result['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$result['creation_id']).'#/'.$result['save_key'].'/'; 
					 $copyuri = $this->url->link('product/design', 'product_id='.$result['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&minimum=1&action=copy&creation_id='.$result['creation_id']).'#/'.$result['save_key'].'/';
				 }else{
					 $edituri = $this->url->link('product/design', 'product_id='.$result['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$result['creation_id']).'#/'.$result['save_key'].'/'; 
					 $copyuri = $this->url->link('product/design', 'product_id='.$result['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&minimum=1&action=copy&creation_id='.$result['creation_id']).'#/'.$result['save_key'].'/';
				 }
				
				$edituri = str_replace('&amp;', '&', $edituri); 
				$copyuri = str_replace('&amp;', '&', $copyuri); 
				$actions = array();
				$moreactions = array();
				if($result['status']=='comfirmed'){
					$actions[] = array(
					'a' => "[".$this->language->get('text_already_confirm')."]"
					);
				}else{
					$actions[] = array(
					'a' => "<a  title=\"".$this->language->get('button_edit')."\"  href=".$edituri."><i class=\"fa design-item-icon fa-edit\"></i></a>"
					);
				}
				
				$text_add_to_cart = $this->language->get('button_cart');
				if($result['order_times']!=0){
					$text_add_to_cart = $this->language->get('button_re_cart');
				}
				
				
				$actions[] = array(
					'a' => "<a  title=\"".$this->language->get('button_copy')."\"  href=".$copyuri."><i class=\"fa design-item-icon fa-copy\"></i></a>"
					
				);
				$moreactions[] = array(
					'a' => "<a title=\"".$this->language->get('button_delete')."\" onclick=\"doDelDesign('".$this->language->get('text_comfirm_delete').$result['name']."?')\" href=".$this->url->link('account/diydesign/delete', 'customer_creation_id='.$result['customer_creation_id'])."><i class=\"fa design-item-icon fa-trash-o\"></i></a>"
					);
				
				                
								
				$moreactions[] = array(
					'a' => "<a  title=\"".$text_add_to_cart."\" href=\"javascript:;\" onclick=\"cart.addcreationtocart('".$result['product_id']."','1','".$result['customer_creation_id']."');\"><i class=\"fa design-item-icon fa-shopping-cart\"></i></a>"
				);
				
				
				$moreactions[] = array(
					'a' => "<a  title=\"".$this->language->get('text_share_project')."\" href=\"".$this->url->link('account/share','&customer_creation_id='.$result['customer_creation_id'])."\" ><i class=\"fa design-item-icon fa-share-alt\"></i></a>"
				);
				
				
						
				$this->load->model('catalog/product');
				$product_id = $result['product_id'];
				$product_info = $this->model_catalog_product->getProduct($product_id);
				$options =  $this->model_account_diy_design->getDesignOptions($result['customer_creation_id']);
				
				$diyoptions =  $this->model_account_diy_design->getOptionDatas($options,$product_id);
				
				$option_data = array();
				foreach ($diyoptions  as $option) {
					
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$this->load->model('tool/upload');
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}
					if ($option['type'] != 'text') {
						$value = html_entity_decode($value);
					}
					if($option['onetimefee']){
						$v = (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value);
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $v
						);
					}else{
						
						$v = (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value);
						
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $v
						);
					}
				}
				$info = sprintf($this->language->get('text_design_info'),$result['total_page_count'],$result['empty_page_count'],$result['low_resolution_count'],$result['picqty']);
				
				$add_price = $result['add_price'];
				/*
				if($add_price){
					$option_data[] = array(
						'name'  =>  $this->language->get('column_add_price'),
						'value' => $add_price
					);
				}*/
				$data['diydesigns'][] = array(
					'customer_creation_id'   => $result['customer_creation_id'],
					'product_url'=>$this->url->link('product/product', 'product_id='.$result['product_id']),
					'text_desin' =>sprintf($this->language->get('text_design'),$result['customer_creation_id']),
					'order_id'     => $result['order_id'],
					'image'        => $result['image'],
					'creation_id'     => $result['creation_id'],
					'option'	     =>$option_data,
					'product_id'     => $product_id,
					'name'    =>$result['name'],
					'order_times'    =>$result['order_times'],
					'info'    =>$info,
					'product_name'   => $product_info['name'],
					'date_added'   => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
					'date_modified'   => date($this->language->get('datetime_format'), strtotime($result['date_modified'])),
					'store_id'     => $result['store_id'],
					'actions'     => $actions,
					'moreactions' =>$moreactions,
					'modify_action' => "<a class=\"modify_action\" title=\"".$this->language->get('text_modify_name')."\" onClick=\"modifyName('".$result['customer_creation_id']."');return false;\" href=".$this->url->link('account/diydesign/modify_design_name', 'customer_creation_id='.$result['customer_creation_id'])."><i class=\"fa design-item-icon fa-pencil\"></i></a>" ,
					'customer_id'     => $result['customer_id']
				);
			}        
			
			$data['error_modify_failed'] = $this->language->get('error_modify_failed');
			$data['image_width'] = $this->config->get('config_image_design_width')?$this->config->get('config_image_design_width'):150;
			$data['image_height'] = $this->config->get('config_image_design_height')?$this->config->get('config_image_design_height'):150;
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_send_order'] = $this->language->get('text_send_order');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_date_modified'] = $this->language->get('text_date_modified');
			$data['text_bat_delete'] = $this->language->get('text_bat_delete');
			$data['text_operation'] = $this->language->get('text_operation');
			$data['text_more'] = $this->language->get('text_more');
            $data['text_k_design'] = $this->language->get('text_k_design');
            $data['text_t_design'] = $this->language->get('text_t_design');
			$data['error_bat_delete_failed'] = $this->language->get('error_bat_delete_failed');
			$data['add_to_cart_tip'] = $this->language->get('add_to_cart_tip');
			$data['send_order_tip'] = $this->language->get('send_order_tip');
			$data['add_to_cart_failed'] = $this->language->get('add_to_cart_failed');
			$data['text_diy_design_name'] = $this->language->get('text_diy_design_name');
			$data['text_order_times'] = $this->language->get('text_order_times');
			$data['text_modify_name'] = $this->language->get('text_modify_name');
			$data['default_name'] = $this->language->get('text_default_design_name');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_delete'] = $this->language->get('button_delete');
			$data['button_grid'] = $this->language->get('button_grid');
            $data['kdesign'] = $this->url->link('account/diydesign', '', 'SSL');
            $data['tdesign'] = $this->url->link('account/tdesign', '', 'SSL');
			$pagination = new Pagination();
			$pagination->total = $diydesign_total;
			$pagination->page = $page;
			$pagination->limit = $page_count;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/diydesign', 'page={page}', 'SSL');
			
			$data['pagination'] = $pagination->render();
			
		   

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
						
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/combinedesign_list.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/combinedesign_list.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/combinedesign_list.tpl', $data));
			}
		}else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');
            $data['text_k_design'] = $this->language->get('text_k_design');
            $data['text_t_design'] = $this->language->get('text_t_design');
            $data['kdesign'] = $this->url->link('account/diydesign', '', 'SSL');
            $data['tdesign'] = $this->url->link('account/tdesign', '', 'SSL');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_continue_shoping'] = $this->language->get('button_continue_shoping');
			$data['continue'] = $this->url->link('common/home');
			$data['continue_shoping'] = $this->url->link('checkout/cart');
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found_design.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found_design.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found_design.tpl', $data));
			}
		}
        
		
    }
	
	
	
	
	
	public function addOrder(){		
			$total = 0;
			$this->language->load('checkout/checkout');
			$data = array();
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}
			
			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();
			
				$this->load->model('account/address');
				
				$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$data['firstname'] = $this->session->data['guest']['firstname'];
				$data['lastname'] = $this->session->data['guest']['lastname'];
				$data['email'] = $this->session->data['guest']['email'];
				$data['telephone'] = $this->session->data['guest']['telephone'];
				$data['fax'] = $this->session->data['guest']['fax'];
				
				$payment_address = $this->session->data['guest']['payment'];
			}
			
			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];	
			$data['payment_company'] = $payment_address['company'];	
			$data['payment_company_id'] = $payment_address['company_id'];	
			$data['payment_tax_id'] = $payment_address['tax_id'];	
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
		
			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
			
			if (isset($this->session->data['payment_method']['code'])) {
				$data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$data['payment_code'] = '';
			}
						
			if ($this->cart->hasShipping()) {
				if ($this->customer->isLogged()) {
					$this->load->model('account/address');
					
					$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);	
				} elseif (isset($this->session->data['guest'])) {
					$shipping_address = $this->session->data['guest']['shipping'];
				}			
				$data['column_diy_design_name'] = $this->language->get('column_diy_design_name');
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];	
				$data['shipping_company'] = $shipping_address['company'];	
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}
				
				if (isset($this->session->data['shipping_method']['code'])) {
					$data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$data['shipping_code'] = '';
				}				
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';	
				$data['shipping_company'] = '';	
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}
			
		$product_data = array();
		$product_id = 0;
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		$creation_id='-1';
		if (isset($this->request->post['creation_id'])) {
			$creation_id = $this->request->post['creation_id'];
		}
		$this->load->model('catalog/product');				
		$product = $this->model_catalog_product->getProduct($product_id);	
		$option_data = array();
		foreach ($product['option'] as $option) {
			if ($option['type'] != 'file') {
				$value = $option['option_value'];	
			} else {
				$value = $this->encryption->decrypt($option['option_value']);
			}	
			
			$option_data[] = array(
				'product_option_id'       => $option['product_option_id'],
				'product_option_value_id' => $option['product_option_value_id'],
				'option_id'               => $option['option_id'],
				'option_value_id'         => $option['option_value_id'],								   
				'name'                    => $option['name'],
				'value'                   => $value,
				'type'                    => $option['type']
			);					
		}
				
		$product_data[] = array(
			'product_id' => $product['product_id'],
			'name'       => $product['name'],
			'model'      => $product['model'],
			'option'     => $option_data,
			'download'   => $product['download'],
			'quantity'   => $product['quantity'],
			'subtract'   => $product['subtract'],
			'price'      => $product['price'],
			'total'      => $product['total'],
			'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
			'reward'     => $product['reward'],
			'creation_id'     => $creation_id
		); 
			
			// Gift Voucher
		$voucher_data = array();
		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$voucher_data[] = array(
					'description'      => $voucher['description'],
					'code'             => substr(md5(mt_rand()), 0, 10),
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'],
					'message'          => $voucher['message'],						
					'amount'           => $voucher['amount']
				);
			}
		}  
					
		$data['products'] = $product_data;
		$data['vouchers'] = $voucher_data;
		$data['totals'] = $total_data;
		$data['comment'] = $this->session->data['comment'];
		$data['total'] = $total;
		
		if (isset($this->request->cookie['tracking'])) {
			$this->load->model('affiliate/affiliate');
			
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
			$subtotal = $this->cart->getSubTotal();
			
			if ($affiliate_info) {
				$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
				$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
		} else {
			$data['affiliate_id'] = 0;
			$data['commission'] = 0;
		}
		
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency_code'] = $this->currency->getCode();
		$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
		} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
		} else {
			$data['forwarded_ip'] = '';
		}
		
		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
		} else {
			$data['user_agent'] = '';
		}
		
		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
		} else {
			$data['accept_language'] = '';
		}
					
		$this->load->model('checkout/order');
		$order_id = $this->model_checkout_order->addOrder($data);
		$this->load->model('checkout/order');
		$this->model_checkout_order->confirm($order_id , $this->config->get('cod_order_status_id'));
		$issuccess = false;
		if($order_id)
			$issuccess = true;
		$arr = array ('success' => $issuccess);
		$out = json_encode($arr);
		$this->response->setOutput($out); 
	}
	
	
    public function flashAddOrder(){		
		 if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/diydesign', '', 'SSL');
				$this->response->redirect($this->url->link('account/login', '', 'SSL'));
			}
		   
			$this->language->load('account/diydesign');

			$this->document->setTitle($this->language->get('heading_title'));

			  $data['breadcrumbs'] = array();

			  $data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),            
				'separator' => false
			  ); 

			  $data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_account'),
				'href'      => $this->url->link('account/account', '', 'SSL'),            
				'separator' => $this->language->get('text_separator')
			  );
        
			$total = 0;
			$this->language->load('checkout/checkout');
			$data = array();
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}
			
			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();
			
				$this->load->model('account/address');
				
				$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$data['firstname'] = $this->session->data['guest']['firstname'];
				$data['lastname'] = $this->session->data['guest']['lastname'];
				$data['email'] = $this->session->data['guest']['email'];
				$data['telephone'] = $this->session->data['guest']['telephone'];
				$data['fax'] = $this->session->data['guest']['fax'];
				
				$payment_address = $this->session->data['guest']['payment'];
			}
			
			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];	
			$data['payment_company'] = $payment_address['company'];	
			$data['payment_company_id'] = $payment_address['company_id'];	
			$data['payment_tax_id'] = $payment_address['tax_id'];	
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
		
			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
			
			if (isset($this->session->data['payment_method']['code'])) {
				$data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$data['payment_code'] = '';
			}
						
			if ($this->cart->hasShipping()) {
				if ($this->customer->isLogged()) {
					$this->load->model('account/address');
					
					$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);	
				} elseif (isset($this->session->data['guest'])) {
					$shipping_address = $this->session->data['guest']['shipping'];
				}			
				$data['column_diy_design_name'] = $this->language->get('column_diy_design_name');
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];	
				$data['shipping_company'] = $shipping_address['company'];	
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}
				
				if (isset($this->session->data['shipping_method']['code'])) {
					$data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$data['shipping_code'] = '';
				}				
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';	
				$data['shipping_company'] = '';	
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}
			
		$product_data = array();
		$product_id = 0;
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		$creation_id='-1';
		if (isset($this->request->post['creation_id'])) {
			$creation_id = $this->request->post['creation_id'];
		}
		$this->load->model('catalog/product');				
		$product = $this->model_catalog_product->getProduct($product_id);	
		$option_data = array();
		foreach ($product['option'] as $option) {
			if ($option['type'] != 'file') {
				$value = $option['option_value'];	
			} else {
				$value = $this->encryption->decrypt($option['option_value']);
			}	
			
			$option_data[] = array(
				'product_option_id'       => $option['product_option_id'],
				'product_option_value_id' => $option['product_option_value_id'],
				'option_id'               => $option['option_id'],
				'option_value_id'         => $option['option_value_id'],								   
				'name'                    => $option['name'],
				'value'                   => $value,
				'type'                    => $option['type']
			);					
		}
				
		$product_data[] = array(
			'product_id' => $product['product_id'],
			'name'       => $product['name'],
			'model'      => $product['model'],
			'option'     => $option_data,
			'download'   => $product['download'],
			'quantity'   => $product['quantity'],
			'subtract'   => $product['subtract'],
			'price'      => $product['price'],
			'total'      => $product['total'],
			'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
			'reward'     => $product['reward'],
			'creation_id'     => $creation_id
		); 
			
			// Gift Voucher
		$voucher_data = array();
		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$voucher_data[] = array(
					'description'      => $voucher['description'],
					'code'             => substr(md5(mt_rand()), 0, 10),
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'],
					'message'          => $voucher['message'],						
					'amount'           => $voucher['amount']
				);
			}
		}  
					
		$data['products'] = $product_data;
		$data['vouchers'] = $voucher_data;
		$data['totals'] = $total_data;
		$data['comment'] = $this->session->data['comment'];
		$data['total'] = $total;
		
		if (isset($this->request->cookie['tracking'])) {
			$this->load->model('affiliate/affiliate');
			
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
			$subtotal = $this->cart->getSubTotal();
			
			if ($affiliate_info) {
				$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
				$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
		} else {
			$data['affiliate_id'] = 0;
			$data['commission'] = 0;
		}
		
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency_code'] = $this->currency->getCode();
		$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
		} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
		} else {
			$data['forwarded_ip'] = '';
		}
		
		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
		} else {
			$data['user_agent'] = '';
		}
		
		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
		} else {
			$data['accept_language'] = '';
		}
					
		$this->load->model('checkout/order');
		$order_id = $this->model_checkout_order->addOrder($data);
		$this->load->model('checkout/order');
		$this->model_checkout_order->confirm($order_id , $this->config->get('cod_order_status_id'));
		$issuccess = false;
		if($order_id)
			$issuccess = true;
		$arr = array ('success' => $issuccess);
		 if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/flashAddOrder.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/flashAddOrder.tpl';
        } else {
            $this->template = 'default/template/account/flashAddOrder.tpl';
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
	
	
    public function thumbnail(){
		if(isset($this->request->get['creation_id'])){
			$this->load->model('account/diy_design'); 
            $result = $this->model_account_diy_design->getDiyDesignByDiyId($this->request->get['creation_id']);
			if($result&&$result['image']&&$result['image']!=""){
				$image = base64_decode($result['image']);
				$this->response->addHeader('Content-Type: image/jpeg');
				$this->response->setOutput($image);
			}else{
				$this->forward($this->config->get('config_url') . 'image/data/default/nopreview.jpg');
			}
		}else{
			$this->forward($this->config->get('config_url') . 'image/data/default/nopreview.jpg');
		}
	}	
	
	public function test(){
		$data = array(
				"product_id"=>"111",
				"creation_id"=>"333333",
				"store_id"=>"0",
				"diy_design_name"=>"name",
				"order_id"=>"1",
				"customer_id"=>"115"
				);
			 $this->load->model('account/diy_design'); 
          $this->model_account_diy_design->updateDesignStatus('333333', 'designfinished');
	}
	

	


	public function editor_login_success()
    {

    	$arr['success'] = false;
    	$customerid = $this->customer->getId();
    	if($customerid){
    		$this->load->model('account/diy_design'); 
            $this->model_account_diy_design->copyDesignFromLog();  
    		$arr['success'] = true;
    	}
        $out = json_encode($arr);
        $this->response->setOutput($out);         
    }


    public function aftersavedesigncallback()
    {
    	$issuccess = false;
    	$customer_creation_id = '';
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->load->model('account/diy_design');

            $this->request->post['option'] =base64_decode($this->request->post['option']);
            $customer_creation_id = $this->model_account_diy_design->addDiyDesign($this->request->post);
            $issuccess = true;
        } 
        $arr = array ('success' => $issuccess);
        if(isset($this->session->data['editor_save_redirect'])&&!empty($this->session->data['editor_save_redirect'])){
        	$arr['redirect'] = $this->session->data['editor_save_redirect'];
        	unset($this->session->data['editor_save_redirect']);
        }else{
        	$arr['redirect'] = $this->url->link('account/diydesign');
        }
		
		$arr['customer_creation_id'] = $customer_creation_id;
    	if ($this->customer->isLogged()) {
    		if(!$this->customer->getEmail()&&$this->customer->getOpenId()&&$this->session->data['weixinbrower']){	
				$arr['code'] = 1;
    		}else{
				$arr['code'] = 1;
    		}

        }else{
 			$arr['code'] = 10;
        }
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $this->customer->getId(),
            'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName().'/'.$customer_creation_id.'/'.$arr['code']
        );

        $this->model_account_activity->addActivity('save_design', $activity_data);
        $out = json_encode($arr);
        $this->response->setOutput($out);              
    	        
    }


	public function checklogin()
    {
        if ($this->customer->isLogged()) {
        	$arr = array ('success' => true);
        }else{
			$arr = array ('success' => false);
        }
        $out = json_encode($arr);
        $this->response->setOutput($out);              
    }
	
	
	
	
	public function modify_design_name()
    {
        $issuccess = false;
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('account/diy_design'); 
            $this->model_account_diy_design->updateName($this->request->post);
            $issuccess = true;
        } 
        $arr = array ('success' => $issuccess);

        $out = json_encode($arr);
        $this->response->setOutput($out);              
    }
	
	




    
    public function afterconfirmordercallback()
    {
        $issuccess = false;
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->load->model('account/diy_design');  
            $creation_id = $this->request->post['creation_id'];
            $this->model_account_diy_design->updateDesignStatus($creation_id, 'designfinished');
            $issuccess = true;
        } 
        $arr = array ('isSuccess' => $issuccess);

        $out = json_encode($arr);
        $this->response->setOutput($out);              
    }    
    
	
	
	public function copy() {
	  if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/diydesign', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
      }
      if (isset($this->request->get['customer_creation_id']))
      {
        $this->load->model('account/diy_design');
        $customer_creation_id = $this->model_account_diy_design->copy($this->request->get['customer_creation_id']);   
		if ($customer_creation_id) {
			$this->load->model('account/diy_design');
			$diydesign = $this->model_account_diy_design->getDiyDesignByDiyId($customer_creation_id);
			
			$edituri = $this->url->link('product/design', 'product_id='.$diydesign['product_id'] .'&customer_creation_id='.$diydesign['customer_creation_id'].'&minimum=1&creation_id='.$diydesign['creation_id']).'#/'.$diydesign['save_key'].'/';
			 $this->response->redirect($edituri); 
						
		}else{
			 $this->response->redirect($this->url->link('account/diydesign', '', 'SSL')); 
		}
       
      }
    }
	
    public function delete() {
	  if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/diydesign', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
      }

        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $this->customer->getId(),
            'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(). ' /' . $this->request->get['customer_creation_id']
        );

        $this->model_account_activity->addActivity('delete_design', $activity_data);
      if (isset($this->request->get['customer_creation_id']))
      {
        $this->load->model('account/diy_design');
        $diydesign_total = $this->model_account_diy_design->deleteDiyDesign($this->request->get['customer_creation_id']);   
        $this->response->redirect($this->url->link('account/diydesign', '', 'SSL')); 
      }
    }
      
	  
	protected function validateGetForm()
    {
        $status = true;        
        $creation_id = $this->request->get['creation_id'];
        if (!isset($this->request->get['creation_id'])) {
            return false;
        }        
        
        if (!isset($this->request->get['opensite_reserved_param'])) {
            return false;            
        }
        
        return $status;          
    } 
    protected function validateForm()
    {
        $status = true;        
        $creation_id = $this->request->post['creation_id'];
        if (!isset($this->request->post['creation_id'])) {
            return false;
        }        
        
      
        
        return $status;          
    }     
}
?>