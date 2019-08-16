<?php
class ControllerCheckoutConfirm extends Controller {
	public function index() {
		$redirect = '';

		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			if (!isset($this->session->data['shipping_address'])) {
				$redirect = $this->url->link('checkout/checkout', '', 'SSL');
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$redirect = $this->url->link('checkout/checkout', '', 'SSL');
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$redirect = $this->url->link('checkout/checkout', '', 'SSL');
		}

		

		// Validate if payment method has been set.
		if (!isset($this->session->data['payment_method'])) {
			$redirect = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!$redirect) {
			$order_data = array();

			$order_data['totals'] = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();

			$this->load->model('extension/extension');
			$this->load->language('checkout/checkout');
			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			if (isset($this->session->data['payment_method']['title'])) {
				$order_data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$order_data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$order_data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$order_data['payment_code'] = '';
			}

			array_multisort($sort_order, SORT_ASC, $results);

			$savecodes = array();
			foreach ($results as $result) {

				if($result['code']=='total'||$result['code']=='credit'||$result['code']=='coupon'||$result['code']=='shipping'||$result['code']=='subtotal')
				{

					$savecodes[] = $result['code'];
					continue;
				}
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
				}
			}
			
			$this->load->model('total/hurry');
			$this->model_total_hurry->getTotal($order_data['totals'], $total, $taxes);
			foreach ($savecodes as $code) {
				if($code!='total'){
					if ($this->config->get($code . '_status')) {
						$this->load->model('total/' . $code);

						$this->{'model_total_' . $code}->getTotal($order_data['totals'], $total, $taxes);
					}
				}
			}

			if($this->config->get('pp_standard_exchange_charge')&&$this->session->data['payment_method']['code']=='pp_standard'){

				$order_data['totals'][] = array(
					'code'       => 'exchange_charge',
					'title'      => $this->language->get('text_exchange_charge'),
					'value'      =>$this->config->get('pp_standard_exchange_charge')*$total,
					'sort_order' => $this->config->get('hurry_sort_order')
				);
				$total = $total+($this->config->get('pp_standard_exchange_charge')*$total);
			}

			$total_code = 'total';
			if ($this->config->get($total_code . '_status')) {
				$this->load->model('total/' . $total_code);

				$this->{'model_total_' . $total_code}->getTotal($order_data['totals'], $total, $taxes);
			}
			$sort_order = array();


			foreach ($order_data['totals'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $order_data['totals']);

			$this->load->language('checkout/checkout');

			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');

			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				$order_data['store_url'] = HTTP_SERVER;
			}

			if ($this->customer->isLogged()) {
				$this->load->model('account/customer');

				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

				$order_data['customer_id'] = $this->customer->getId();
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $customer_info['lastname'];
				$order_data['email'] = $customer_info['email'];
				$order_data['telephone'] = $customer_info['telephone'];
				$order_data['fax'] = $customer_info['fax'];
				$order_data['custom_field'] = json_decode($customer_info['custom_field'], true);
			} elseif (isset($this->session->data['guest'])) {
				$order_data['customer_id'] = 0;
				$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['guest']['firstname'];
				$order_data['lastname'] = $this->session->data['guest']['lastname'];
				$order_data['email'] = $this->session->data['guest']['email'];
				$order_data['telephone'] = $this->session->data['guest']['telephone'];
				$order_data['fax'] = $this->session->data['guest']['fax'];
				$order_data['custom_field'] = $this->session->data['guest']['custom_field'];
			}
			if (isset($this->session->data['hurry'])) {
				$order_data['hurry'] = $this->session->data['hurry'];
			} else {
				$order_data['hurry'] = '';
			}
			$order_data['payment_firstname'] = '';
			$order_data['payment_lastname'] = '';
			$order_data['payment_company'] = '';
			$order_data['payment_address_1'] = '';
			$order_data['payment_address_2'] = '';
			$order_data['payment_city'] = '';
			$order_data['payment_postcode'] = '';
			$order_data['payment_zone'] = '';
			$order_data['payment_zone_id'] = '';
			$order_data['payment_country'] = '';
			$order_data['payment_country_id'] = '';
			$order_data['payment_address_format'] = '';
			$order_data['payment_custom_field'] = array();


			$data['column_diy_design_name'] = $this->language->get('column_diy_design_name');
			if ($this->cart->hasShipping()) {
				$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
				$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
				$order_data['shipping_telephone'] = $this->session->data['shipping_address']['telephone'];
				$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
				$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
				$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
				$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
				$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
				$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
				$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
				$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

				if (isset($this->session->data['shipping_method']['title'])) {
					$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$order_data['shipping_method'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$order_data['shipping_code'] = '';
				}
			} else {
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
                $order_data['shipping_telephone'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}

			$order_data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$order_data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'onetimefee'       => isset($product['onetimefee'])?$product['onetimefee']:0,
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward'],
					'customer_creation_id'     => $product['customer_creation_id']
				);
			}

			// Gift Voucher
			$order_data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = array(
						'description'      => $voucher['description'],
						'code'             => token(10),
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

			$order_data['comment'] = $this->session->data['comment'];
			$order_data['fapiao'] = $this->session->data['fapiao'];
			$order_data['total'] = $total;
			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('affiliate/affiliate');

				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['currency_id'] = $this->currency->getId();
			$order_data['currency_code'] = $this->currency->getCode();
			$order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}

			$this->load->model('checkout/order');

			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

			$data['text_recurring_item'] = $this->language->get('text_recurring_item');
			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_add_price'] = $this->language->get('column_add_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_one_time_fee'] = $this->language->get('column_one_time_fee');
			$this->load->model('tool/upload');
			$data['one_time_fee'] = false;
			$data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					if(isset($option['onetimefee'])){
						$data['one_time_fee'] = true;
						/*
						if($option['price_prefix']&&$option['price']){
							$v = (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value).'('.$option['price_prefix'].$option['price'].'   '.$this->language->get('text_onetimefee').')';
						}else{
							$v = (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value).'   '.'('.$this->language->get('text_onetimefee').')';
						}
					$option_data[] = array(
						'name'  => $option['name'],
							'value' => $v
						);*/
						
						
					}else{
						$v = (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value);
						if($option['price_prefix']&&$option['price']){
							$op_price = $this->currency->format($option['price']);
							if(bccomp($option['price'],0.0)!==0){
								$v = (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value).'('.$option['price_prefix'].$op_price.')';
							}
						}
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $v
					);
				}
				}
				$diydesign='';
				$customer_creation_id = '';
				$edituri='';
				$add_price = 0;
				if (isset($product['customer_creation_id'])&&!empty($product['customer_creation_id'])) {
					$this->load->model('account/diy_design');
					$diydesign = $this->model_account_diy_design->getDiyDesignByDiyId($product['customer_creation_id']);

					if(!empty($diydesign)){
						if(empty($diydesign['option'])){
						 $edituri = $this->url->link('product/design', 'product_id='.$diydesign['product_id'] .'&customer_creation_id='.$diydesign['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$diydesign['creation_id']).'#/'.$diydesign['save_key'].'/'; 
						 }else{
							$option = base64_decode($diydesign['option']);
						 	$edituri = $this->url->link('product/design', 'product_id='.$diydesign['product_id'] .'&customer_creation_id='.$diydesign['customer_creation_id'].'&option='.$option.'&minimum=1&action=edit&creation_id='.$diydesign['creation_id']).'#/'.$diydesign['save_key'].'/'; 
						 }
					}
					$edituri = str_replace('&amp;', '&', $edituri); 
					$customer_creation_id = $diydesign['customer_creation_id'];		
					$add_price = $diydesign['add_price'];					
				}
				$recurring = '';

				if ($product['recurring']) {
					$frequencies = array(
						'day'        => $this->language->get('text_day'),
						'week'       => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month'      => $this->language->get('text_month'),
						'year'       => $this->language->get('text_year'),
					);

					if ($product['recurring']['trial']) {
						$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
					}

					if ($product['recurring']['duration']) {
						$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					} else {
						$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					}
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$total = false;
				}
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$add_price = $this->currency->format($this->tax->calculate($add_price, $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$add_price = false;
				}
				$data['products'][] = array(
					'cart_id'    => $product['cart_id'],
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'one_time_fee'=> $this->currency->format($product['one_time_fee']),
					'recurring'  => $recurring,
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'customer_creation_id'        => $customer_creation_id,
					'diydesign_name'      => $diydesign?$diydesign['name']?$diydesign['name']:$this->language->get('text_default_design_name'):$this->language->get('text_default_design_name'),
					'add_price' => $add_price,
					'diydesign_edituri'   => $edituri,
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'total'      => $total,
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
				);
			}



			// Gift Voucher
			$data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'])
					);
				}
			}

			$data['totals'] = array();

			foreach ($order_data['totals'] as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value']),
				);
			}


			$data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
		} else {
			$data['redirect'] = $redirect;
		}


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/confirm.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/checkout/confirm.tpl', $data));
		}
	}
}
