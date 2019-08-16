<?php
class ControllerModuleTrackingInput extends Controller {
	private $_cookie_ttl = 86400000;
	public function index($setting = false) {
		if(!$setting || !$this->config->get('tracking_input_status') || $setting['language_id'] != $this->config->get('config_language_id') || (isset($this->session->data['tracking_input_show']) && !$this->session->data['tracking_input_show']) || ($this->config->get('tracking_input_no_cookie_only') && (isset($this->request->request['tracking']) || isset($this->request->cookie['tracking']))) || ($this->config->get('tracking_input_show') == 'once' && isset($this->request->cookie['__octfsh__']) && (!isset($this->session->data['tracking_input_show']) || !$this->session->data['tracking_input_show']))) return '';
		$this->set_config_template();
		$this->document->addScript('catalog/view/javascript/triyp.min.js');
		//$this->document->addScript('catalog/view/javascript/triyp.js');
		$this->session->data['tracking_input_show'] = true;
		if($this->config->get('tracking_input_show') == 'once') setcookie('__octfsh__', '1', time() + 2592000, '/');

		$data['show_close_button'] = $this->config->get('tracking_input_show_close_button');
		
		$data['image_close'] = file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/image/close.png') ? 'catalog/view/theme/' . $this->config->get('config_template') . '/image/close.png' : 'catalog/view/theme/default/image/close.png';
		$data['image_loading'] = file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/image/loading.gif') ? 'catalog/view/theme/' . $this->config->get('config_template') . '/image/loading.gif' : 'catalog/view/theme/default/image/loading.gif';		

		$data['send_link'] = html_entity_decode($this->url->link('module/tracking_input/send', '', (isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] == 'on' ? 'SSL' : 'NONSSL')), ENT_QUOTES, 'UTF-8');
		$data['close_link'] = html_entity_decode($this->url->link('module/tracking_input/close', '', (isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] == 'on' ? 'SSL' : 'NONSSL')), ENT_QUOTES, 'UTF-8');		
		$data['text_thankyou'] = isset($setting['text_thankyou']) && utf8_strlen($setting['text_thankyou']) > 0 ? addcslashes(str_replace(array("\r\n", "\n", "\r"), array(' ', ' ', ' '), html_entity_decode($setting['text_thankyou'], ENT_QUOTES, 'UTF-8')), "'") : '';
		$data['error_message'] = isset($setting['error_message']) && utf8_strlen($setting['error_message']) > 0 ? addcslashes(str_replace(array("\r\n", "\n", "\r"), array(' ', ' ', ' '), html_entity_decode($setting['error_message'], ENT_QUOTES, 'UTF-8')), "'") : '';
		$data['json'] = array();
		foreach(array('send_link', 'close_link', 'text_thankyou', 'error_message') as $_v) {
			$data['json'][$_v] = $data[$_v];	
		}
		$data['json'] = json_encode($data['json']);
		
		
		$data['text_message'] = html_entity_decode($setting['text'], ENT_QUOTES, 'UTF-8');
		$data['text_heading'] = html_entity_decode($setting['text_heading'], ENT_QUOTES, 'UTF-8');
		$data['send_button'] = $setting['button'];
		$this->language->load('affiliate/tracking_input');
		$data['text_loading'] = $this->language->get('text_please_wait');
	
		$_tpl = 'module/tracking_input_' . (isset($setting['template']) ? $setting['template'] : ('default_' . (substr($setting['position'], 0, 3) === 'col' ? 'column' : 'row')));
		if(version_compare(VERSION, '2.2', '<')) {
			$_tpl = '/template/' . $_tpl . '.tpl';
			$_tpl = (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $_tpl) ? $this->config->get('config_template') : 'default') . $_tpl;
		}
		return $this->load->view($_tpl, $data);
	}
	
	public function send() {
		$_REQUEST['format'] = $_GET['format'] = 'raw';//+mod by yp mijoshop
		$this->load->model('affiliate/affiliate');
		if(!isset($this->request->post['t']) || !strlen(($this->request->post['t'] = trim($this->request->post['t'])))) {
			$this->response->setOutput('error');			
		} else {		
			$mark = false;
			$aff = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->post['t']);
			if(!$aff && !isset($this->request->cookie['tracking']) && $this->config->get('tracking_input_allow_marketing')) {
				$this->load->model('checkout/marketing');
				$mark = $this->model_checkout_marketing->getMarketingByCode($this->request->post['t']);
			}			
			if(!$mark && (!$aff || !$aff['status'] || !$aff['approved'])) {
				$this->response->setOutput('error');			
			} else {
				$this->session->data['tracking_input_show'] = false;
				if(isset($this->request->cookie['tracking'])) setcookie('tracking', '', time() - 86400, '/');
				setcookie('tracking', $this->request->post['t'], ($this->_cookie_ttl ? time() + intval($this->_cookie_ttl) : 0), '/');
				$this->session->data['tracking'] = $this->request->post['t'];
				if($mark) $this->db->query("UPDATE `" . DB_PREFIX . "marketing` SET clicks = (clicks + 1) WHERE code = '" . $this->db->escape($this->request->post['t']) . "'");				
				$this->response->setOutput($this->config->get('tracking_input_redirect') ? '1' : '-1');
			}
		}
	}
	
	public function close() {
		if($this->config->get('tracking_input_show') != 'page') $this->session->data['tracking_input_show'] = false;
	}
	
	private function set_config_template() {
		if(version_compare(VERSION, '2.2', '<')) return;
		if ($this->config->get('config_theme') == 'theme_default') {
			$dir_theme = $this->config->get('theme_default_directory');
		} else {
			$dir_theme = $this->config->get('config_theme');
		}
		$this->config->set('config_template', $dir_theme);
	}	
}
