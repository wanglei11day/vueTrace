<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');
		$logger = new Log('checkoutsuccess.log');
		$logger->write('checkoutsuccess - started\n');

		if (isset($this->session->data['order_id'])) {
			//添加获取pdf操作
			$products = $this->cart->getProducts();
			foreach ($products as $product) {
				
				// designfile_data
				if (!empty($product['creation_id'])) {
					$creation_id = $product['creation_id'];
					$this->load->model('account/diy_design');
					$diydesign = $this->model_account_diy_design->getDiyDesignByDiyId($product['creation_id']);
					$edituri = $this->url->link('product/design', 'product_id='.$diydesign['product_id'] .'&minimum=1&creation_id='.$diydesign['creation_id']).'#/'.$diydesign['save_key'].'/';			
					
					//发送编译PDF的POST
					$url = "http://creator.koffeeware.com/api/".KW_API_KEY."/".$diydesign['save_key'];
					
					$jsondata = array(
						"descriptor_base_uri" => "https://s3.cn-north-1.amazonaws.com.cn/upload.creator5.permanent",
						"to_ftp" => array(
							"host" => "www.icootoo.com",//"91.121.195.199",
							"login" => "ftpuser",
							"pass" => "1qasde32w"
						),
						"callback_url" => HTTP_SERVER.'kwcallback.php'
					);
					$logger->write('\nposturl====='.$url."/build/");
					$content = json_encode($jsondata);
					$logger->write('\npostdata=====');
					$logger->write(print_r($jsondata,true));
					
					//初始化
					$curl = curl_init();
					//设置抓取的url
					curl_setopt($curl, CURLOPT_URL, $url."/build/");
					//设置头文件的信息作为数据流输出，调试用
					//curl_setopt($curl, CURLOPT_HEADER, true);
					//设置HTTP报头信息
					curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
					//设置获取的信息以文件流的形式返回，而不是直接输出。
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					//执行命令
					$post_result = curl_exec($curl);
					//获取执行状态
					$post_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
					$logger->write('\nstatus====='.$post_status.'\n');
					print_r("\n ----> post_status is ".$post_status);
					//关闭URL请求
					curl_close($curl);
					
					if($post_status == 200 || $post_status == 201){
						//显示获得的数据
						print_r("\n ----> post_result is ".$post_result);
						
						//文件已经创建，用GET方法获取
						/*
						function send_get($geturl) {
							$options = array(
								'http' => array(
								'method' => 'GET',//or GET
								'header' => 'Content-type:application/x-www-form-urlencoded',
								'content' => '',
								'timeout' => 15 * 60 // 超时时间（单位:s）
								)
							);
						
							$context = stream_context_create($options);
							$result = file_get_contents($geturl, true, $context);
							return $result;
						}

						//发送GET请求
						$get_result = send_get($url."/check/");
						print_r("\n ----> get_result is ".$get_result);
						
						//解析xml文件
						$result_xml = simplexml_load_string($get_result);
						$xml_url = $result_xml->url;
						print_r("\n ----> xml_url is ".$xml_url);
						$xml_error = $result_xml->error;
						print_r("\n ----> xml_error is ".$xml_error);
						$xml_run = $result_xml->run;
						print_r("\n ----> xml_run is ".$xml_run);
						$xml_download = $result_xml->download;
						print_r("\n ----> xml_download is ".$xml_download);
						$xml_percent = $result_xml->percent;
						print_r("\n ----> xml_percent is ".$xml_percent);*/
						
					}else{
						$data['error_warning'] = 'checkout failed';
						//$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
					}
					
				}
			
			}
		
		
			
			$this->cart->clear();

			// Add to activity log
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'order_id'    => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_account', $activity_data);
			} else {
				$activity_data = array(
					'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
					'order_id' => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_guest', $activity_data);
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}
}