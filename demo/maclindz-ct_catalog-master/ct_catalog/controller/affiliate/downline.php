<?php

class ControllerAffiliateDownline extends Controller {
	public function index() {
		if(!$this->config->get('mta_ypx_downline')) $this->response->redirect($this->url->link('affiliate/account', '', 'SSL'));
		$aff_id = $this->affiliate->isLogged();
		if (!$aff_id) {
	  		$this->session->data['redirect'] = $this->url->link('affiliate/downline', '', 'SSL');	  
	  		$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'));
    	}
		$data['affiliate_id'] = $aff_id;
		$this->language->load('affiliate/downline');
		
		foreach(array('heading_title', 'text_self', 'text_legend', 'text_self', 'text_account', 'text_collapse', 'text_expand', 'text_abbr_te', 'text_abbr_elm', 'text_te', 'text_elm') as $_v) {
			$data[$_v] = $this->language->get($_v);
		}	
		$data['show'] = array();
		foreach(array('email', 'phone', 'earnings') as $_v) {
			$data['show'][$_v] = $this->config->get('mta_ypx_downline_' . $_v);
		}
		$data['image_loading'] = file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/image/loading.gif') ? 'catalog/view/theme/' . $this->config->get('config_template') . '/image/loading.gif' : 'catalog/view/theme/default/image/loading.gif';
    	$this->document->setTitle($data['heading_title']);
			
		$this->load->model('affiliate/downline');		
		
		$data['top_count'] = $this->model_affiliate_downline->countSubs($aff_id);		
		
/////////////////////////////////////////

      	$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('affiliate/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('affiliate/downline', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		

		$data['button_continue'] = $this->language->get('button_continue');		
		$data['continue'] = $this->url->link('affiliate/account', '', 'SSL');		

///////////////////////////////
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/affiliate_downline.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/affiliate_downline.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/affiliate_downline.css');
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/downline.tpl')) {
			$template = $this->config->get('config_template') . '/template/affiliate/downline.tpl';
		} else {
			$template = 'default/template/affiliate/downline.tpl';
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
				
		$this->response->setOutput($this->load->view($template, $data));
	}	
	
	public function l() {
		$_REQUEST['format'] = $_GET['format'] = 'raw';//+mod by yp mijoshop
		$affiliate_id = isset($this->request->request['affiliate_id']) ? intval($this->request->request['affiliate_id']) : 0;	
		$self_id = $this->affiliate->isLogged();
		if (!$self_id || !$affiliate_id || !$this->config->get('mta_ypx_downline')) {
			$this->response->setOutput('');
			return;
    	}	
		$limit = $this->config->get('mta_ypx_downline_limit');
		if($limit) {
			$this->load->model('affiliate/mta_affiliate');
			$self_aff = $this->model_affiliate_mta_affiliate->getAffiliate($self_id);
			$max_level = intval($self_aff['level_original']) + intval($limit);
		} else {
			$max_level = false;
		}

		$this->load->model('affiliate/downline');
		if(!$this->model_affiliate_downline->canSee(strval($self_id), $affiliate_id, $max_level)) {
			$this->response->setOutput('');
			return;
    	}	
		$res = $this->model_affiliate_downline->loadSubs($affiliate_id, $max_level);		
		$out = array();
		foreach($res as $v) {				
			if($this->config->get('mta_ypx_downline_earnings')) {
				$v['e_te'] = $this->currency->format(($v['e_te'] ? $v['e_te'] : 0), $this->config->get('config_currency'));				
				$v['e_elm'] = $this->currency->format(($v['e_elm'] ? $v['e_elm'] : 0), $this->config->get('config_currency'));
			}
			$out[] = $v;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($out));
	}

}