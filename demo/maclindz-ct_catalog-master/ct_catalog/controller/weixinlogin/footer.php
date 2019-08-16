<?php
class ControllerWeixinloginFooter extends Controller {
	public function index() {
		$data = array();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/weixinlogin/footer.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/weixinlogin/footer.tpl', $data);
		} else {
			return $this->load->view('default/template/weixinlogin/footer.tpl', $data);
		}
	}
}