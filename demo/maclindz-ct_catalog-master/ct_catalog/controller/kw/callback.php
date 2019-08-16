<?php
class ControllerKwCallback extends Controller {
	public function index() {
		$this->load->language('tool/upload');
		$logger = new Log('kwcallback.log');
		$logger->write('kwcallback - started');
		$logger->write(print_r($this->request->get,true));
		if(!empty($this->request->get['success'])){
			$save_key = $this->request->get['success'];
			$this->load->model('account/diy_design');
			$this->model_account_diy_design->updateDesignStatus($save_key,'downloadable');
					
		}
		$json['success'] =true;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}