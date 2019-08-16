<?php 
class ControllerGalleryAlbum extends Controller {
  public function index() {

    $this->load->language('gallery/gallery');
	
	$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_gallery'),
			'href' => $this->url->link('gallery/album')
		);
	 
	$data['heading_title'] = $this->language->get('heading_title');
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['column_right'] = $this->load->controller('common/column_right');
	$data['content_top'] = $this->load->controller('common/content_top');
	$data['content_bottom'] = $this->load->controller('common/content_bottom');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
     

    if (version_compare(VERSION, '2.2.0.0', '>=')) {
    $this->response->setOutput($this->load->view('gallery/album', $data));    
    } else {   
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/album.tpl')) {
      $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/gallery/album.tpl', $data));
    } else {
      $this->response->setOutput($this->load->view('default/template/gallery/album.tpl', $data));
    }
    }
  }
}
?>