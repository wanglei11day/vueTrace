<?php
class ControllerCommonCart extends Controller {
	public function index() {
		$this->load->language('common/cart');

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

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_recurring'] = $this->language->get('text_recurring');
		$data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_remove'] = $this->language->get('button_remove');

		$this->load->model('tool/image');
		$this->load->model('tool/upload');

		$data['products'] = array();

		foreach ($this->cart->getProducts() as $product) {
			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
			} else {
				$image = '';
			}

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

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
				);
			}

			
			$diydesign='';
			$customer_creation_id = '';
			$edituri='';
			$add_price = 0;
			if ($product['customer_creation_id']) {
				$this->load->model('account/diy_design');
				$diydesign = $this->model_account_diy_design->getDiyDesignByDiyId($product['customer_creation_id']);
				if(!empty($diydesign)){
					if(empty($diydesign['option'])){
						$edituri = $this->url->link('product/design', 'product_id='.$diydesign['product_id'] .'&customer_creation_id='.$diydesign['customer_creation_id'].'&minimum=1&action=edit&creation_id='.$diydesign['creation_id']).'#/'.$diydesign['save_key'].'/'; 
					 }else{
						$option = base64_decode($diydesign['option']);
						
						 $edituri = $this->url->link('product/design', 'product_id='.$diydesign['product_id'] .'&customer_creation_id='.$diydesign['customer_creation_id'].'&option='.$option.'&minimum=1&action=edit&creation_id='.$diydesign['creation_id']).'#/'.$diydesign['save_key'].'/'; 
					 }
					 $edituri = str_replace('&amp;', '&', $edituri); 
					 $customer_creation_id = $diydesign['customer_creation_id'];		
					$add_price = $diydesign['add_price'];
				}
			}
				

			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
			} else {
				$total = false;
			}

			$data['products'][] = array(
				'cart_id'   => $product['cart_id'],
				'thumb'     => isset($diydesign['image'])?$diydesign['image']:$image,
				'name'      => $product['name'],
				'model'     => $product['model'],
				'option'    => $option_data,
				'recurring' => ($product['recurring'] ? $product['recurring']['name'] : ''),
				'quantity'  => $product['quantity'],
				'price'     => $price,
				'total'     => $total,
				'add_price' => $add_price,
				'customer_creation_id'        => $customer_creation_id,
				'diydesign_name'      => $diydesign?$diydesign['name']?$diydesign['name']:$this->language->get('text_default_design_name'):$this->language->get('text_default_design_name'),
				'diydesign_edituri' =>$edituri,
				'href'      => isset($diydesign['image'])?$edituri:$this->url->link('product/product', 'product_id=' . $product['product_id'])
			);
		}

		// Gift Voucher
		$data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'])
				);
			}
		}

		$data['totals'] = array();

		foreach ($total_data as $result) {
			$data['totals'][] = array(
				'title' => $result['title'],
				'text'  => $this->currency->format($result['value']),
			);
		}

		$data['cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/cart.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/cart.tpl', $data);
		} else {
			return $this->load->view('default/template/common/cart.tpl', $data);
		}
	}

	public function info() {
		$this->response->setOutput($this->index());
	}

    public function update_order() {
	    $order_product_id = $this->request->get['order_product_id'];
        $order_product = $_SESSION['order_product'];
        $this->load->model('account/order');
        $this->model_account_order->update_tshirt_order($order_product_id,json_encode($order_product['product']));
    }
}
