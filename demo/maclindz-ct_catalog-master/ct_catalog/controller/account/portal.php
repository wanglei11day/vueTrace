<?php 
class ControllerAccountPortal extends Controller {
    private $error = array();
    
    public function index() {
        if ($this->customer->isLogged()) {  
      		$this->redirect($this->url->link('account/account', '', 'SSL'));
    	}
        $this->language->load('account/login');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['action'] = $this->url->link('portal/login', '', 'SSL');
        $this->data['actionentercode'] = $this->url->link('portal/entercouponcode', '', 'SSL');

        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_password'] = $this->language->get('entry_password');

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_login'] = $this->language->get('button_login');
        $this->data['button_entercode'] = "输入商品卡";

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
            $this->data['redirect'] = $this->request->post['redirect'];
        } elseif (isset($this->session->data['redirect'])) {
              $this->data['redirect'] = $this->session->data['redirect'];
              
            unset($this->session->data['redirect']);              
        } else {
            $this->data['redirect'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
    
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } else {
            $this->data['email'] = '';
        }

        if (isset($this->request->post['password'])) {
            $this->data['password'] = $this->request->post['password'];
        } else {
            $this->data['password'] = '';
        }
        $this->data['facebooklogin'] = $this->getChild('module/facebooklogin');  
        $this->template = 'portal/login.tpl';
		$this->data['styles'] = $this->document->getStyles();
        $this->response->setOutput($this->render());
      }
}
?>