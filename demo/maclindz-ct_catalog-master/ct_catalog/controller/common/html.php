<?php
class ControllerCommonHtml extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}
		$this->load->model('extension/module');
		if(isset($this->request->get['module_id'])){
			$setting_info = $this->model_extension_module->getModule($this->request->get['module_id']);
			if(!empty($setting_info)){
				if ($setting_info && $setting_info['status']) {
					$data['modules'][] = $this->load->controller('module/html', $setting_info);
				}
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/html.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/html.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/common/html.tpl', $data));
				}
				return;
			}
			
		}
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

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_error'),
			'href' => $this->url->link('product/category', $url)
		);

		$this->document->setTitle($this->language->get('text_error'));

		$data['heading_title'] = $this->language->get('text_error');

		$data['text_error'] = $this->language->get('text_error');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

	
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
		}
		
		
	}
}