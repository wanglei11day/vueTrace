<?php

class ControllerCommonTheader extends Controller {
	public function index() {

        $this->load->language('common/header');



        require_once( DIR_SYSTEM . 'pavothemes/loader.php' );
        $config = $this->registry->get('config');
        $helper = ThemeControlHelper::getInstance( $this->registry, $config->get('config_template') );
        $helper->triggerUserParams( array('header_layout','productlayout') );
        $data['helper'] = $helper;
        $data['is_weixin'] = $this->session->data['weixinbrower'];
        $data['weixinlogin'] = $this->url->link('login/weixin', '', 'SSL');
        $themeConfig = (array)$config->get('themecontrol');

        $headerlayout = $helper->getConfig('header_layout','header-v1');
        $data['headerlayout'] = $headerlayout;


		// Analytics
		$this->load->model('extension/extension');

		$data['analytics'] = array();

		$analytics = $this->model_extension_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get($analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('analytics/' . $analytic['code']);
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
                require(DIR_SYSTEM.'config/resource.php');
		$data['version'] = $resource_version;

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$this->document->addStyle('catalog/view/theme/'.$this->config->get('config_template').'/stylesheet/language_'.$this->config->get('config_language_id').'.css');

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['shopping_cart_total'] = $this->cart->countProducts();
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$this->load->model('account/diy_design');
		$diydesign_total = 0;
		if($this->customer->getId()){
			$diydesign_total = $this->model_account_diy_design->getTotalDiyDesignByCustomerId($this->customer->getId());
		}
		$data['text_my_design'] = $this->language->get('text_my_design').'('.$diydesign_total.')';
		$data['text_my_order'] = $this->language->get('text_order');
        $data['text_setting'] = $this->language->get('text_setting');
		$data['text_account'] = $this->language->get('text_welcome').$this->customer->getFirstname();
		$data['text_register'] = $this->language->get('text_register');
		$data['welcome'] =$this->language->get('header_welcome');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_header_text'] = $this->language->get('text_header_text');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');
		$data['text_menu_home'] = $this->language->get('text_menu_home');
		$data['text_menu_newest'] = $this->language->get('text_menu_newest');
		$data['text_menu_category'] = $this->language->get('text_menu_category');
		$data['text_menu_cart'] = $this->language->get('text_menu_cart');
		$data['text_menu_my'] = $this->language->get('text_menu_my');
		$data['home'] = $this->url->link('common/home');
		$data['menu_home'] = $this->url->link('common/home');
		$data['menu_newest'] = $this->url->link('common/home','home_id=175','SSL');
		$data['menu_category'] = $this->url->link('common/home','home_id=176','SSL');
		$data['menu_cart'] = $this->url->link('checkout/cart');
		$data['menu_my'] = $this->url->link('account/account', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['design'] = $this->url->link('account/diydesign', '', 'SSL');
		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		if (isset($this->request->get['route'])) {
			$data['menu_route'] = $this->request->get['route'];
		}else{
			$data['menu_route']='common/home';
		}

		if($data['menu_route']=='common/home'){
			if(isset($this->request->get['home_id'])){
				$home_id =$this->request->get['home_id'];
				if($home_id==175){
					$data['menu_route'] = 'product/latest';
				} 
				if($home_id==176){
					$data['menu_route'] = 'product/category';
				} 
			}
		}
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}
		$data['quicksignup'] = $this->load->controller('common/quicksignup');
		$data['signin_or_register'] = $this->language->get('signin_or_register');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/tshirt-'.$headerlayout.'.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/tshirt-'.$headerlayout.'.tpl', $data);
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/t_header.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/common/t_header.tpl', $data);
			} else {
				return $this->load->view('default/template/common/t_header.tpl', $data);
			}
		}
	
	}
}
