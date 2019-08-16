<?php
class ControllerAccountHome extends Controller {
    public function index() {
        $this->document->addStyle('hicootoo/catalog/view/javascript/view/common.css');
        $this->document->addStyle('hicootoo/catalog/view/javascript/view/control.css');
        $this->document->addStyle('hicootoo/catalog/view/javascript/view/jeDate.css');
        $this->document->addStyle('hicootoo/catalog/view/javascript/bootstrap/css/bootstrap.min.css');
        $this->document->addScript('hicootoo/catalog/view/javascript/bootstrap/js/bootstrap.min.js');
        $this->document->addScript('hicootoo/catalog/view/javascript/view/js/jquery.min.js');
        $this->document->addScript('hicootoo/catalog/view/javascript/view/js/jedeta.js');
        $this->document->addScript('hicootoo/catalog/view/javascript/view/js/control.js');


        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->load->language('account/account');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL')
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        $data['text_my_task'] = $this->language->get('text_my_task');

        $data['text_my_department'] = $this->language->get('text_my_department');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_balance'] = $this->language->get('text_balance');
        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_project'] = $this->language->get('text_project');
        $data['text_coupon'] = $this->language->get('text_coupon');
        $data['text_my_orders'] = $this->language->get('text_my_orders');
        $data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_jiameng'] = $this->language->get('text_jiameng');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_link_to_affiliate'] = $this->language->get('text_link_to_affiliate');
        $data['text_my_design'] = $this->language->get('text_my_design');
        $data['text_album'] = $this->language->get('text_album');
        $data['text_my_album'] = $this->language->get('text_my_album');
        $data['text_myphoto'] = $this->language->get('text_myphoto');
        $data['text_add_album'] = $this->language->get('text_add_album');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_transaction'] = $this->language->get('text_transaction');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_recurring'] = $this->language->get('text_recurring');

        $data['edit'] = $this->url->link('account/edit', '', 'SSL');

        $data['coupon'] = $this->url->link('account/coupons', '', 'SSL');
        $data['password'] = $this->url->link('account/password', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['design'] = $this->url->link('account/diydesign', '', 'SSL');
        $data['project'] = $this->url->link('account/diydesign', '', 'SSL');
        $data['album'] = $this->url->link('account/album', '', 'SSL');
        $data['department'] = $this->url->link('account/department', '', 'SSL');
        $data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['return'] = $this->url->link('account/return', '', 'SSL');
        $data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
        $data['task'] = $this->url->link('account/task', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
        $data['recurring'] = $this->url->link('account/recurring', '', 'SSL');
        $data['link_to_affiliate'] = $this->url->link('affiliate/account_combine', '', 'SSL');
        $this->load->model('account/school');
        $data['joinedSchools']  = $this->model_account_school->getJoinSchooles();

        $data['bookTypes'] =$this->language->get('year_book_type');
        $data['bookTypes'] =$this->language->get('year_book_type');
        $data['eccoupon'] = $this->load->controller('module/eccustomercoupon');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/home.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/home.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/home.tpl', $data));
        }
    }

    public function country() {
        $json = array();

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = array(
                'country_id'        => $country_info['country_id'],
                'name'              => $country_info['name'],
                'iso_code_2'        => $country_info['iso_code_2'],
                'iso_code_3'        => $country_info['iso_code_3'],
                'address_format'    => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status'            => $country_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
