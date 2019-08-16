<?php
	class ControllerProductTask extends Controller {
		
		public function index() {
            $this->session->data['editor_save_redirect'] = $this->url->link('account/task');
            if (!$this->customer->isLogged()&&$this->session->data['weixinbrower']) {
                $this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');
                $this->response->redirect($this->url->link('account/login', '', 'SSL'));
            }
			if (!$this->customer->isLogged()) {
				$data['isLogin'] = 0;
			}else{
				$data['isLogin'] = 1;
			}
			if(isset($this->request->get['product_id'])){
				$data['product_id'] = (int)$this->request->get['product_id'];
			}else{
				$data['product_id'] = 0;
			}
			if(isset($this->request->get['minimum'])){
				$data['minimum'] = (int)$this->request->get['minimum'];
			}else{
				$data['minimum'] = 0;
			}

			if(isset($this->request->get['history_id'])){
				$history_id = (int)$this->request->get['history_id'];
			}else{
				$history_id = false;
			}

			$data['hide_edit'] = false;
			$data['creator'] = false;
			if(isset($this->request->get['task_id'])){

				$this->load->model('account/task');
				$task = $this->model_account_task->getTaskById($this->request->get['task_id']);
				if($task['customer_id']!=$this->customer->getId()){
					$data['hide_edit'] = true;
				}
				if(empty($task)){
					$data['customer_creation_id'] = '';
				}else{
					$data['customer_creation_id'] = $task['customer_creation_id'];
					$data['creator'] = $task['creator_id'];
				}
			}else{
				$data['customer_creation_id'] = '';
			}
			
			if(!empty($this->session->data['design_option'])){
				$data['option'] = $this->session->data['design_option'];
			}else{
				$data['option'] = '';
			}
			
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			
			//$data['hide_edit'] = false;
			$data['base'] = $server;
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$data['design_url'] = HTTP_SERVER;
			$data['design_cart'] = $this->url->link('product/design');
			$data['shopping_cart'] = $this->url->link('checkout/cart');
			$data['language_id'] = $this->config->get('config_language_id');
			$data['language'] = $this->load->controller('common/language');
			$data['currency'] = $this->load->controller('common/currency');
			$data['search'] = $this->load->controller('common/search');
			$data['cart'] = $this->load->controller('common/cart');
			
			$this->load->language('product/design');

			
			$data['txt_guide'] = $this->language->get('txt_guide');
			$data['txt_guide_title'] = $this->language->get('txt_guide_title');
			$data['txt_close_guide'] = $this->language->get('txt_close_guide');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['txt_add'] = $this->language->get('txt_add');
			$data['txt_cancel'] = $this->language->get('button_cancel_return');
			$data['txt_reduce'] = $this->language->get('txt_reduce');
			$data['text_modify_name'] = $this->language->get('text_modify_name');
			$data['txt_page'] = $this->language->get('txt_page');
			$data['kwclocaleurl'] = $this->language->get('kwclocaleurl');
			$design_name=$this->language->get('text_default_design_name');
			$data['text_tip_info']=$this->language->get('text_tip_info');
			$data['text_tip_title']=$this->language->get('text_tip_title');
			$data['text_tip_continue']=$this->language->get('text_tip_continue');
			$data['text_tip_cancel']=$this->language->get('text_tip_cancel');
			$data['text_save_design_name']=$this->language->get('text_save_design_name');
			$data['text_design_name']=$this->language->get('text_design_name');
			$data['button_confirm']=$this->language->get('button_confirm');
			$data['button_cancel']=$this->language->get('button_cancel');
            $data['text_save_success']=$this->language->get('text_save_success');
            $data['text_upload']=$this->language->get('text_upload');
            $data['entry_telephone'] = $this->language->get('entry_telephone');
            $data['entry_password'] = $this->language->get('entry_password');
            $data['entry_comfirm_password'] = $this->language->get('entry_comfirm_password');
            $data['text_forgotten'] = $this->language->get('text_forgotten');
			$data['guides']=$this->language->get('text_guides');
			
			$data['current_pages'] = 20;
			if (isset($this->request->get['product_id'])) {
				$product_id = (int)$this->request->get['product_id'];
				$data['cancel'] =  $this->url->link('product/product','product_id='.$product_id);
				if ($data['customer_creation_id']) {
					
					$this->load->model('account/diy_design');
					if($history_id){
						$diydesign = $this->model_account_diy_design->getDiyDesignByHistoryId($history_id);
					}else{
						$diydesign = $this->model_account_diy_design->getDiyDesignByDiyId($data['customer_creation_id']);
					}
					
					if(isset($data['option'])){
						$data['option'] = $diydesign['option'];
						$this->session->data['design_option'] = $diydesign['option'];
					}
					$data['current_pages'] = $diydesign['pages'];
					$design_name= $diydesign['name'];
				}

				//product_info
				$this->load->model('catalog/product');
				$product_info = $this->model_catalog_product->getProduct($product_id);	
				$data['product_info'] = $product_info;	
				$product_editor_arg_str = $this->trimall($product_info['editorarg']);
				$product_editor_arg_str = html_entity_decode($product_editor_arg_str);
				$product_editor_arg_arr = explode(';',$product_editor_arg_str);
				$product_editor_arg = array();
				if(!empty($product_editor_arg_arr)){
					foreach($product_editor_arg_arr as $oea){
						$product_editor_arg_arr = explode(':',$oea);
						if(count($product_editor_arg_arr)==2){
							$product_editor_arg[$product_editor_arg_arr[0]] = $product_editor_arg_arr[1];
						}
					}
				}
				
				$editorarg = $product_editor_arg;
				/*
				$editorarg = array(
					'000'=>'',
					'001'=>'',
					'201'=>'430',
					'202'=>'215',
					'203'=>'215',
					'204'=>'215',
					'007'=>'300',
					'152'=>'300',
					'209'=>'true',
					'210'=>'true',
					'211'=>'3.0 , 3.0 , 3.0 , 3.0',
					'212'=>'3.0 , 3.0 , 3.0 , 3.0',
					'213'=>'5.0 , 5.0 , 5.0 , 5.0',
					'214'=>'5.0 , 5.0 , 5.0 , 5.0',
					'205'=>'36,10',
					'110'=>'3',
					'120'=>'',
					'122'=>'10',
					'123'=>'300',
					'124'=>'2',
					'121'=>'18',
					'701'=>'pdf',
					'702'=>'pdf',
					'703'=>'cootoo201510221713c.pdf',
					'704'=>'cootoo201510221713p.pdf',
					'128'=>'0',
					'129'=>'10',
					'999'=>'0',
					'801'=>'none',
					'802'=>'cootoo',
					'803'=>'120',
					'804'=>'0.25',
					'805'=>'3000000',
					'806'=>'1000',
					'807'=>'true',
					'808'=>'true',
					'809'=>'10',
					'810'=>'false',
					'811'=>'811',
					'812'=>'',
					'002'=>'',
					'100'=>'cardsimple',
					'101'=>'Book'
				);*/
				$data['editorarg'] = $editorarg;
				
				// Attributes
				$data['product_attributes'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);	

				// product_options
				$product_options = $this->model_catalog_product->getProductOptions($product_id);
				
				$option_array = array();
				if(!empty($data['option'])){
					$option_array = json_decode($data['option'],true);
					//print_r('xxx'.arrayToString($option_array).'yyy');
					
					$option_data = array();
					foreach ($product_options as $option) {
						//echo json_encode($option);
						//echo '<br>';
						if ($option['type'] != 'file'){
							$value = $option['value'];
						}
						if(!empty($option_array)){
							foreach($option_array as $k=>$v){
								$product_option_id = $k;
								$product_option_value_id = $v;
								//echo 'aa'.$product_option_id.'  bb'.$product_option_value_id.'<br>';
								if($option['product_option_id'] == $product_option_id){
									//echo 'aa'.$product_option_id.'  bb'.$product_option_value_id.'<br>';
									foreach($option['product_option_value'] as $k=>$v){
										//echo 'cc'.$k.'  dd'.$v.'<br>';
										if($v['product_option_value_id'] == $product_option_value_id){
											
											$option_editor_arg_str = $this->trimall($v['editorarg']);
											$option_editor_arg_str = html_entity_decode($option_editor_arg_str);
											$option_editor_arg_arr = explode(';',$option_editor_arg_str);

											$option_editor_arg = array();
											if(!empty($option_editor_arg_arr)){
												foreach($option_editor_arg_arr as $oea){
													$option_editor_arg_arr = explode(':',$oea);
													if(count($option_editor_arg_arr)==2){
														$option_editor_arg[$option_editor_arg_arr[0]] = $option_editor_arg_arr[1];
													}
												}
											}
											
											if(!empty($option_editor_arg)){
												foreach($option_editor_arg as $key=>$item){
													if(strcasecmp($item,'True')==0)
														$item='true';
													if($item==''){
														continue;
													}
													if(isset($editorarg[$key])&&is_numeric($editorarg[$key])){
														if(is_numeric($item)){
															$editorarg[$key] += $item;
														}
													}else{
														$editorarg[$key] = $item;
													}
													
												}
											}
											
											
											$option_data[] = array(
												'product_option_id' => $option['product_option_id'],
												'product_option_value_id' => $v['product_option_value_id'],
												'name'  => $option['name'],
												'value' => $v['name']
											);
										}
									}
									
								}
							}
						}
						
						
					}
					$data['option_data'] = $option_data;
					
					//print_r("+++".arrayToString($option_data));
				}else{
					$products = $this->cart->getProducts();
					foreach ($products as $product) {
						$option_data = array();
						if($product_id == $product['product_id']){					
							foreach ($product['option'] as $option) {
								if ($option['type'] != 'file') {
									$value = $option['value'];
								}
								$option_data[] = array(
									'product_option_value_id' => $option['product_option_value_id'],
									'name'  => $option['name'],
									'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
								);
							}
							$data['option_data'] = $option_data;
						}else{
							$data['option_data'] = $option_data;
						}
					}
				}
			} else {
				$product_id = 0;
				
			}		
			if(isset($this->request->get['action'])){
				$data['action'] = $this->request->get['action'];
			}else{
				$data['action'] = '';
			}
			$data['design_name'] = $design_name;
			
			$data['fontfaces'] = array();
			if(isset($editorarg[811])){
				$rdat = $this->getFontfaces($editorarg[811]);
				$data['fontfaces'] = $rdat['fontfaces'];
				$editorarg[811] = $rdat['namelist'];
			}
			$data['cancel'] =  $this->url->link('account/task');
			$data['editorarg'] = $editorarg;
			$data['config_template'] = $this->config->get('config_template');
			$this->session->data['editorarg'] = json_encode($editorarg);
			$data['editorquicksignup'] = $this->load->controller('common/editor_quicksignup');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/design.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/design.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/design.tpl', $data));
			}
		}
	function getFontfaces($data){
		$returndata = array();
		$namelist = '';
		$arr = explode(",",$data);
		foreach ($arr as $item){
			$font = explode("#",$item);
			if(count($font)==2){
				if(empty($namelist)){
					$namelist = "'".$font[0]."'";
				}else{
					$namelist = $namelist.",'".$font[0]."'";
				}
				$returndata['fontfaces'][] = array(
				   'name'=>$font[0],
				   'eot_file'=>HTTP_SERVER.'fonts/'.$font[1].'.eot',
				   'woff_file'=>HTTP_SERVER.'fonts/'.$font[1].'.woff',
				   'ttf_file'=>HTTP_SERVER.'fonts/'.$font[1].'.ttf',
				   'svg_file'=>HTTP_SERVER.'fonts/'.$font[1].'.svg'
				);
			}
		}
		$returndata['namelist'] = $namelist;
		return $returndata;
	}
	function trimall($str)//删除空格
	{
		$qian=array(" ","　","\t","\n","\r");
		$hou=array("","","","","");
		return str_replace($qian,$hou,$str); 
	}
		public function add() {

			$this->load->language('checkout/cart');

			$json = array();

			if (isset($this->request->post['product_id'])) {
				$product_id = (int)$this->request->post['product_id'];
			} else {
				$product_id = 0;
			}

			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($product_id);
			$option = array();
			if ($product_info) {
				if (isset($this->request->post['quantity']) && ((int)$this->request->post['quantity'] >= $product_info['minimum'])) {
					$quantity = (int)$this->request->post['quantity'];
				} else {
					$quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
				}
				
				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
					
				} else {
					$option = array();
				}
				$recurring_id = 0;
				if(isset($this->request->post['group_id'])){
					$product_options = $this->model_catalog_product->getProductGroupOptions($this->request->post['product_id'],$this->request->post['group_id']);
					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['option'][$product_option['product_option_id']] = $this->language->get('error_required');
						}
					}
					if (isset($this->request->post['recurring_id'])) {
						$recurring_id = $this->request->post['recurring_id'];
					} else {
						$recurring_id = 0;
					}

					$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

					if ($recurrings) {
						$recurring_ids = array();

						foreach ($recurrings as $recurring) {
							$recurring_ids[] = $recurring['recurring_id'];
						}

						if (!in_array($recurring_id, $recurring_ids)) {
							$json['error']['recurring'] = $this->language->get('error_recurring_required');
						}
					}
				}
				
				
				
				

				if (!$json) {
					//$new_cart_key = $this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);
					$_SESSION['cart_product_id'] = $this->request->post['product_id'];
					$_SESSION['cart_quantity'] = $quantity;
					$_SESSION['cart_option'] = $option;
					$_SESSION['cart_recurring_id'] = $recurring_id;
					
					$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
					
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);

					// Totals
					$this->load->model('extension/extension');

					$total_data = array();
					$total = 0;
					$taxes = $this->cart->getTaxes();

					// Display prices
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$sort_order = array();

						$results = $this->model_extension_extension->getExtensions('total');

						foreach ($results as $key => $value) {
							$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
						}

						array_multisort($sort_order, SORT_ASC, $results);

						foreach ($results as $result) {
							if ($this->config->get($result['code'] . '_status')) {
								$this->load->model('total/' . $result['code']);

								$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
							}
						}

						$sort_order = array();

						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}

						array_multisort($sort_order, SORT_ASC, $total_data);
					}
					$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
				} else {
					$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
				}
			}
			$this->session->data['design_option'] = json_encode($option);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
		
		public function add2cart() {
			$cart_product_id = $_SESSION['cart_product_id'];
			$cart_quantity =$_SESSION['cart_quantity'];
			$cart_option = $_SESSION['cart_option'];
			$cart_recurring_id = $_SESSION['cart_recurring_id'];
			
			$customer_creation_id = $_GET['customer_creation_id'];
			$kw_api_key = "LpdszAWhxAMb6kPhT21zAnOX";
			$kw_book_key = $_GET['save_key'];
			$thumbnail = $_GET['thumbnail'];
			$server_url = "ftp://cootoo:fTuLnjCI@www.hyd-spm.com/";
			$designfile_url = $server_url.$kw_book_key."/";
			
			$add_price = $_GET['add_price'];
			$designfile_data = array(
				'cart_key' => '',
				'customer_creation_id'  => $customer_creation_id,
				'kw_api_key'  => $kw_api_key,
				'kw_book_key'  => $kw_book_key,
				'designfile_url'  => $designfile_url,
				'thumbnail'  => $thumbnail
			);
			
			
			$cart_key = $this->cart->add($cart_product_id, $cart_quantity, $cart_option, $cart_recurring_id, $add_price, $designfile_data);
			
			/********************
			1、写入内容到文件,追加内容到文件
			2、打开并读取文件内容
			********************/
			
			$file  = 'cart_key.txt';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
			$content = "\n第一次写入的内容  add_price is ".$add_price." end\n";

			if($f = file_put_contents($file, $content,FILE_APPEND)){// 这个函数支持版本(PHP 5) 
				//echo "写入成功。<br />";
			}
			$content = " cart_key is ".$cart_key." cart_product_id is ".$cart_product_id." cart_recurring_id is ".$cart_recurring_id;
			if($f = file_put_contents($file, $content,FILE_APPEND)){// 这个函数支持版本(PHP 5)
				//echo "写入成功。<br />";
			}
		}
	}
?>