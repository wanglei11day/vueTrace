<?php 
class ControllerAccountDiyDesign extends Controller { 

    public function index() {  
     	
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/diydesign', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
		
		
       $page_count = 12;
        $this->language->load('account/diydesign');
        $this->document->addScript('catalog/view/javascript/clipboard/dist/clipboard.js');
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
        $diydesign_total = $this->model_account_diy_design->getTotalDiyDesignByCustomerId($customerid);
        $results = $this->model_account_diy_design->getDiyDesignsByCustomerId($customerid, ($page - 1) * $page_count, $page_count);

        $no_more_data = $this->language->get('no_more_data');
        $get_more_data = $this->language->get('get_more_data');
		if($results){
			 foreach ($results as $result) {
				 if(empty($result['option'])){
					 $edituri = $this->url->link('product/design', 'product_id='.$result['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$result['creation_id']).'#/'.$result['save_key'].'/'; 
					 $copyuri = $this->url->link('product/design', 'product_id='.$result['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&minimum=1&action=copy&creation_id='.$result['creation_id']).'#/'.$result['save_key'].'/';
				 }else{
					 $edituri = $this->url->link('product/design', 'product_id='.$result['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$result['creation_id']).'#/'.$result['save_key'].'/'; 
					 $copyuri = $this->url->link('product/design', 'product_id='.$result['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&minimum=1&action=copy&creation_id='.$result['creation_id']).'#/'.$result['save_key'].'/';
				 }
				 $shareurl=$this->url->link('account/share/confirmed','customer_creation_id='.$result['customer_creation_id'].'&key='.md5($result['customer_creation_id'].$result['customer_id'].$result['product_id']));
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
					'a' => "<a  title=\"".$this->language->get('text_share_project')."\" class=\"share_btn\" share_link=\"".$shareurl."\" href=\"".$this->url->link('account/share','&customer_creation_id='.$result['customer_creation_id'])."\" ><i class=\"fa design-item-icon fa-share-alt\"></i></a>"
				);

                 $moreactions[] = array(
                     'a' => "<a  title=\"".$this->language->get('text_split')."\" class=\"split_btn\" customer_creation_id=\"".$result['customer_creation_id']."\" href=\"#\" ><i class=\"fa design-item-icon fa-cut\"></i></a>"
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

                 $project_history = $this->model_account_diy_design->getHistory($result['customer_creation_id']);
                 $historyprojects = array();
                 foreach ($project_history as $history) {
                     $historyprojects[] = array(
                         'name'=>'#'.$history['customer_design_log_id'].' '.$history['date_added'],
                         'class'=>'',
                         'href'=>$this->url->link('product/design', 'product_id='.$history['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&history_id='.$history['customer_design_log_id'].'&minimum=1&action=edit&creation_id='.$history['creation_id']).'#/'.$history['save_key'].'/'
                     );
                 }
                 if(count($historyprojects)==10)
                 {
                     $more_link = $this->url->link('account/diydesign/getMoreHistory', 'customer_creation_id='.$result['customer_creation_id'] .'&page=1');
                     $historyprojects[] = array(
                       'name' => $get_more_data,
                         'href' =>$more_link,
                         'class'=>'getmore'
                     );
                 }

				$data['diydesigns'][] = array(
					'customer_creation_id'   => $result['customer_creation_id'],
					'product_url'=>$this->url->link('product/product', 'product_id='.$result['product_id']),
					'text_desin' =>sprintf($this->language->get('text_design'),$result['customer_creation_id']),
					'order_id'     => $result['order_id'],
					'locked'        => $result['locked'],
					'shareurl'      =>$shareurl,
					'image'        => $result['image'],
					'creation_id'     => $result['creation_id'],
					'option'	     =>$option_data,
					'product_id'     => $product_id,
					'name'    =>$result['name'],
					'historyprojects' =>$historyprojects,
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
            $data['text_cover'] = $this->language->get('text_cover');
            $data['text_sort'] = $this->language->get('text_sort');

			$data['error_modify_failed'] = $this->language->get('error_modify_failed');
			$data['image_width'] = $this->config->get('config_image_design_width')?$this->config->get('config_image_design_width'):150;
			$data['image_height'] = $this->config->get('config_image_design_height')?$this->config->get('config_image_design_height'):150;
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_send_order'] = $this->language->get('text_send_order');
            $data['text_bat_combine'] = $this->language->get('text_bat_combine');
            $data['error_bat_combine_failed'] = $this->language->get('error_bat_combine_failed');
            $data['text_comfirm_bat_combine'] = $this->language->get('text_comfirm_bat_combine');
            $data['text_share_tip'] = $this->language->get('text_share_tip');
            $data['text_locked'] = $this->language->get('text_locked');
			$data['text_date_added'] = $this->language->get('text_date_added');
            $data['text_history'] = $this->language->get('text_history');
            $data['text_copy'] = $this->language->get('text_copy');
            $data['text_share'] = $this->language->get('text_share');
            $data['text_split'] = $this->language->get('text_split');
            $data['text_split_tip'] = $this->language->get('text_split_tip');
            $data['text_split_label'] = $this->language->get('text_split_label');
            $data['text_combine_design'] = $this->language->get('text_combine_design');
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
						
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/diydesign_list.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/diydesign_list.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/diydesign_list.tpl', $data));
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
	
	
	
	public function getMoreHistory()
    {
        $this->language->load('account/diydesign');
        $no_more_data = $this->language->get('no_more_data');
        $get_more_data = $this->language->get('get_more_data');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        }else{
            $page = 1;
        }

        if (isset($this->request->get['customer_creation_id'])) {
            $customer_creation_id = $this->request->get['customer_creation_id'];
        }else{
            $customer_creation_id = 0;
        }
        $this->load->model('account/diy_design');
        $project_history = $this->model_account_diy_design->getHistory($customer_creation_id,$page);
        $html = '';
        $count = count($project_history);
        if($count==0){
            $html .= '<li>'.$no_more_data.'</li>';
        }else{
            $more_link = $this->url->link('account/diydesign/getMoreHistory', 'customer_creation_id='.$customer_creation_id .'&page='.($page+1));
            foreach ($project_history as $history) {
                $href=$this->url->link('product/design', 'product_id='.$history['product_id'] .'&customer_creation_id='.$result['customer_creation_id'].'&history_id='.$history['customer_design_log_id'].'&minimum=1&action=edit&creation_id='.$history['creation_id']).'#/'.$history['save_key'].'/';
                $name = '#'.$history['customer_design_log_id'].' '.$history['date_added'];
                $html .= '<li><a href="'.$href.'">'.$name.'</a></li>';
            }
            if($count=10)
            {
                $html .= '<li><a class="getmore" href="'.$more_link.'">'.$get_more_data.'</a></li>';
            }else{
                $html .= '<li>'.$no_more_data.'</li>';
            }
        }
        echo $html;
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
            $arr['msg'] =  $this->language->get('text_login_success');
    	}
        $out = json_encode($arr);
        $this->response->setOutput($out);         
    }


    public function unlock(){
        $this->load->model('account/diy_design');
        $customer_creation_id = $this->request->get['customer_creation_id'];
        $this->model_account_diy_design->unlock($customer_creation_id);
        echo true;
    }
    public function aftersavedesigncallback()
    {
    	$issuccess = false;
    	$customer_creation_id = '';
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->load->model('account/diy_design');

            $this->request->post['option'] =base64_decode($this->request->post['option']);
            $customer_creation_id = $this->model_account_diy_design->addDiyDesign($this->request->post);
            $this->model_account_diy_design->unlock($customer_creation_id);
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

    public function afteruploaddesigncallback()
    {
        $issuccess = false;
        $customer_creation_id = '';
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->load->model('account/diy_design');

            $this->request->post['option'] =base64_decode($this->request->post['option']);
            $customer_creation_id = $this->model_account_diy_design->addBakDiyDesign($this->request->post);
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
	
	
	public function batdelete()
    { 
		 $arr = array ('success' => false);
		
		 if ($this->customer->isLogged()) {
			$this->load->model('account/diy_design');
			foreach($this->request->post['selected'] as $result){
				$this->model_account_diy_design->deleteDiyDesign($result);
			}
			$arr['success']  = true;
		  }
		  
        $out = json_encode($arr);
        $this->response->setOutput($out);              
    }
    public function combine_design()
    {
        $arr = array ('success' => false);

        $cover = 0;
        if ($this->customer->isLogged()) {

            $this->load->model('account/diy_design');
            $pages = array();
            $newdesign = array();
            $firstpages = array();
            $list = $this->request->post['data'];
            $list = htmlspecialchars_decode($list);
            $list = json_decode($list,true);
            $cover =0;
            $minsort = 0;
            $sort_select = [];
            foreach ($list as $result)
            {
                $id = $result['id'];
                $sort = $result['sort'];
                while($sort_select[$sort])
                {
                    $sort++;
                }
                $sort_select[$sort] = $id;
            }
            if($this->request->post['cover']){
                $cover = $this->request->post['cover'];
            }else{
                $cover = 0;
            }
            ksort($sort_select);
            $sort_select = array_values($sort_select);
            $type = '';
            foreach($sort_select as $k => $result) {
                if(!$cover)
                    $cover = $result;
                $url = S3_SERVER . 'upload.creator5.permanent/cootoo/creations/';

                $this->load->model('account/diy_design');

                $design = $this->model_account_diy_design->getDiyDesignByDiyId($result);
                $content = file_get_contents($url . $design['save_key'] . '.json');
                $json_content = json_decode($content, true);
                $objcts = $json_content['pages'];
                if($k==0)
                {
                    $type = $json_content['product']['type'];
                }
                if($type=='gift')
                {
                    $pages = array_merge($pages, $objcts);
                    if ($result == $cover) {
                        $newdesign = $json_content;
                    }
                }
                else
                {
                    if ($result == $cover) {
                        $newdesign = $json_content;
                        $firstpages = $objcts;
                    } else {
                        unset($objcts[0]);
                        $pages = array_merge($pages, $objcts);
                    }
                }

            }
            if($type=='gift')
            {
                $newdesign['pages'] = $pages;
                $numpages = count($newdesign['pages']);
            }
            else
            {
                $newdesign['pages'] = array_merge($firstpages,$pages);
                $numpages = (count($newdesign['pages'])-1)*2;
            }
            $design['save_key'] = md5($design['save_key'].time()).date(Ymd);

            $file_key = 'cootoo/creations/'.$design['save_key'].'.json';

            $index = 1;
            if(!$newdesign['product']['units'][$index])
            {
                $index = 0;
            }
            $newdesign['product']['units'][$index]['num_pages'] = $numpages;
            $maxpage = $newdesign['product']['units'][$index]['max_pages'];
            if($maxpage<$numpages)
                $maxpage = $numpages;
            $newdesign['product']['units'][$index]['max_pages'] = $maxpage;
            $design['customer_id'] = $this->customer->getId();
            $design['pages'] = $numpages;
            $id = $this->model_account_diy_design->copydesignByCombine($design);

            $this->mv_file_to_s3(json_encode($newdesign),$file_key,'json');

            if ($id) {
                $data['success'] = sprintf($this->language->get('combinesuccess'), $this->url->link('account/diydesign'));
            } else {
                $data['success'] = '';
            }
        }
        $arr['success']  = true;


        $out = json_encode($arr);
        $this->response->setOutput($out);
    }



    public function split_design()
    {
        $arr = array ('success' => false);

        if ($this->customer->isLogged()) {

            $this->load->model('account/diy_design');
            $customer_creation_id = $this->request->post['customer_creation_id'];
            $pages = $this->request->post['pages'];
            $selected_pages = array();
            if($pages)
            {
                $pages = explode(',',$pages);
                foreach ($pages as $page) {
                    $itemarr = explode('-',$page);
                    $start = $itemarr[0];
                    $end = $itemarr[1];
                    if($end){
                        while ($start<=$end)
                        {
                            $selected_pages[] = $start;
                            $start++;
                        }
                    }else{
                        $selected_pages[] = $start;
                    }
                }
            }
            $selected_pages = array_unique($selected_pages);
            sort($selected_pages);



            $url = S3_SERVER . 'upload.creator5.permanent/cootoo/creations/';

            $this->load->model('account/diy_design');
            $pages = array();

            $design = $this->model_account_diy_design->getDiyDesignByDiyId($customer_creation_id);
            $content = file_get_contents($url . $design['save_key'] . '.json');
            //print_r($content);
            //print_r(PHP_EOL);
            $json_content = json_decode($content, true);
            $inner_cover_editable = $json_content['units'][1]['inner_cover_editable'];
            $objcts = $json_content['pages'];
            $firstPage = false;
            if(!$inner_cover_editable)
            {
                if($selected_pages[0] != 1)
                {
                    $firstPage = $objcts[1];
                    $firstPage['objects']= array();
                }
            }
            $selected_pages = $this->convertPages($selected_pages,$inner_cover_editable);
           // print_r($selected_pages);
            //die();
            $lastPage = false;
            if(!$inner_cover_editable)
            {

                if($selected_pages[count($selected_pages)-1]!=count($pages))
                {
                    $lastPage = $objcts[1];
                    $lastPage['objects'] = array();
                }
            }

            $pre_pages[] = $objcts[0];
            if($firstPage)
            {
                $pre_pages[] = $firstPage;
            }



            //unset($objcts[0]);

            $min_page = 2;

            foreach ($objcts as $index => $onepage)
            {
                if(in_array(($index+1),$selected_pages)){

                    $pages[] = $onepage;
                }
            }




            $newdesign = $json_content;

            $pages = array_merge($pre_pages,$pages);
            if($lastPage)
            {
                $pages[] = $lastPage;
            }


            $newdesign['pages'] = $pages;

            $design['save_key'] = md5($design['save_key'].time()).date(Ymd);

            $file_key = 'cootoo/creations/'.$design['save_key'].'.json';
            //$newdesign['product']['units'][0]['num_pages'] = count($newdesign['pages']);
            if($inner_cover_editable)
            {
                $page_count = (count($newdesign['pages'])-1)*2;
            }
            else
            {
                $page_count = (count($newdesign['pages'])-2)*2;
            }

            $index = 1;
            if(!$newdesign['product']['units'][$index])
            {
                $index = 0;
            }

            $newdesign['product']['units'][$index]['num_pages'] = $page_count;
            $newdesign['product']['units'][$index]['min_pages'] = $min_page;
            $design['pages'] = $page_count;
            $id = $this->model_account_diy_design->copydesignByCombine($design);

            //print_r(json_encode($newdesign));
           // die();
            $this->mv_file_to_s3(json_encode($newdesign),$file_key,'json');

            if ($id) {
                $data['success'] = sprintf($this->language->get('combinesuccess'), $this->url->link('account/diydesign'));
            } else {
                $data['success'] = '';
            }
        }
        $arr['success']  = true;


        $out = json_encode($arr);
        $this->response->setOutput($out);
    }


    private function convertPages($select_pages,$editable)
    {
        $conv_pages = array();
        foreach ($select_pages as $i)
        {
            if($editable)
            {
                $conv_pages[] = floor(($i-1)/2)+2;
            }
            else
            {
                $conv_pages[] = floor($i/2)+2;
            }
        }
        return $conv_pages;
    }

    private function mv_file_to_s3($content,$file,$ext){
        $bucket = 'upload.creator5.permanent';
        require_once DIR_SYSTEM.'library/aws/aws-autoloader.php';
        error_reporting(0);
        $content_type = '';
        ini_set('display_errors',1);
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

        $res = $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => $file,
            'Body'   => $content,
            'ContentType'  => $ext,
            'ACL'    => 'public-read',
        ]);
        return true;


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