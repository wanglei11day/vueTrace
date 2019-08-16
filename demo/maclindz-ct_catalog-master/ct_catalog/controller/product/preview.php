<?php
	class ControllerProductPreview extends Controller {
		
		public function index() {
			
			
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			
			$data['base'] = $server;
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$data['design_url'] = HTTP_SERVER;
			$data['design_cart'] = $this->url->link('product/design');
			$data['shopping_cart'] = $this->url->link('checkout/cart');
			
			$data['language'] = $this->load->controller('common/language');
			$data['currency'] = $this->load->controller('common/currency');
			$data['search'] = $this->load->controller('common/search');
			$data['cart'] = $this->load->controller('common/cart');
			
			$this->load->language('product/design');
			$data['title'] = $this->language->get('title');
			$data['txt_add'] = $this->language->get('txt_add');
			$data['txt_cancel'] = $this->language->get('button_cancel_return');
			$data['txt_reduce'] = $this->language->get('txt_reduce');
			$data['text_modify_name'] = $this->language->get('text_modify_name');
			$data['txt_page'] = $this->language->get('txt_page');
			$data['kwclocaleurl'] = $this->language->get('kwclocaleurl');
			$design_name=$this->language->get('text_default_design_name');
			$data['current_pages'] = 20;
			if (isset($this->request->get['file'])) {
				$data['pdf'] = $server.'image/pdf/'.$this->request->get['file'].'.pdf';
			}else{
				$data['pdf'] = $server.'image/pdf/default.pdf';
			}
			$data['editorquicksignup'] = $this->load->controller('common/editor_quicksignup');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/preview/preview.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/preview/preview.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/preview/preview.tpl', $data));
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
					
					$json['option'] = $option;
					$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
				} else {
					$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
				}
			}
			$json['json_option']= json_encode($option);
			
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