<?php
class ControllerGalleryGallery extends Controller {
	public function index() {
		$this->load->language('gallery/gallery');

		$this->load->model('catalog/gallimage');
		
		$this->load->model('tool/image');
		
		$this->document->addStyle('catalog/view/javascript/jquery/gallery-album/gallery.css');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['gallimage_id'])) {
			$gallimage_id = (int)$this->request->get['gallimage_id'];
		} else {
			$gallimage_id = 0;
		}
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_gallery'),
			'href' => $this->url->link('gallery/album')
		);
        
        $gallimage_info = $this->model_catalog_gallimage->getGallalbum($gallimage_id);
        
        $page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
        
        if ($gallimage_info['gallpage'] == '' || $gallimage_info['gallpage'] == 0) {
			$limit = 8;
		} else {
			$limit = $gallimage_info['gallpage'];
		}
        
        $url = '&gallimage_id=' . $gallimage_id;

		$gallimage_info = $this->model_catalog_gallimage->getGallalbum($gallimage_id);

		if ($gallimage_info) {
			$this->document->setTitle($gallimage_info['meta_title']);
			$this->document->setDescription($gallimage_info['meta_description']);
			$this->document->setKeywords($gallimage_info['meta_keyword']);
            
            if ($gallimage_info['popstyle'] == 'lightgall') {
            $this->document->addScript('catalog/view/javascript/jquery/gallery-album/lightbox/js/lightbox.min.js');    
            $this->document->addStyle('catalog/view/javascript/jquery/gallery-album/lightbox/css/lightbox.css');    
            } else if ($gallimage_info['popstyle'] == 'blueimp') {
            $this->document->addScript('catalog/view/javascript/jquery/gallery-album/blueimp/js/jquery.blueimp-gallery.min.js');
            $this->document->addScript('catalog/view/javascript/jquery/gallery-album/blueimp/js/bootstrap-image-gallery.min.js');    
		    $this->document->addStyle('catalog/view/javascript/jquery/gallery-album/blueimp/css/blueimp-gallery.css');    
            $this->document->addStyle('catalog/view/javascript/jquery/gallery-album/blueimp/css/bootstrap-image-gallery.css'); 
            } else {
            $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');    
            }

			$data['breadcrumbs'][] = array(
				'text' => $gallimage_info['name'],
				'href' => $this->url->link('gallery/gallery', 'gallimage_id=' .  $gallimage_id)
			);

			$data['heading_title'] = $gallimage_info['name'];

			$data['button_continue'] = $this->language->get('button_continue');
			
			if ($gallimage_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($gallimage_info['image'], $gallimage_info['awidth'], $gallimage_info['aheight']);
			} else {
				$data['thumb'] = '';
			}
			
			$data['gallimage_id'] = $gallimage_info['gallimage_id'];
            $data['awidth'] 	= $gallimage_info['awidth'];
			$data['aheight'] 	= $gallimage_info['aheight'];
            $data['position'] 	= $gallimage_info['position'];
            $data['resize'] 	= $gallimage_info['resize'];
            $data['imgperrow'] 	= $gallimage_info['imgperrow'];
			$data['gwidth'] 	= $gallimage_info['gwidth'];
			$data['gheight'] 	= $gallimage_info['gheight'];
			$data['pwidth'] 	= $gallimage_info['pwidth'];
			$data['pheight'] 	= $gallimage_info['pheight'];
            $data['popstyle'] 	= $gallimage_info['popstyle'];

			$data['description'] = html_entity_decode($gallimage_info['description'], ENT_QUOTES, 'UTF-8');
			
			$data['gallimages'] = array();
            
            $filter_data = array(
                'start' => ($page - 1) * $gallimage_info['gallpage'],
                'limit' => $gallimage_info['gallpage']
            );

		
            $total_gallimages = $this->model_catalog_gallimage->getTotalGallimages($gallimage_id);

            $results = $this->model_catalog_gallimage->getGallimage($filter_data);

            if ($results) {
            foreach ($results as $result) {
                if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $data['gwidth'], $data['gheight']);                   
                        $popupimage = ($gallimage_info['resize'] ? $this->model_tool_image->resize($result['image'], $data['pwidth'], $data['pheight']) : 'image/' . $result['image']);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $data['gwidth'], $data['gheight']);
                        $popupimage = ($gallimage_info['resize'] ? $this->model_tool_image->resize('placeholder.png', $data['pwidth'], $data['pheight']) : 'image/placeholder.png');
                    }
                if (strstr($result['link'], 'product/preview') === false) {
                	$pdf = false;
                }else{
                	$pdf = true;
                }
                
                if (file_exists(DIR_IMAGE . $result['image'])) {
                    $data['gallimages'][] = array(
                        'title' => $result['title'],
                        'link'  => $result['link'],
                        'pdf'   =>$pdf,
                        'image' => $image,
                        'popup' => $popupimage
                    );
                }
            }
            }
            
            $pagination = new Pagination();
            $pagination->total = $total_gallimages;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('gallery/gallery', $url . '&page={page}');
            
            if ($limit > $total_gallimages) {
            $data['pagination'] = '';
            $data['results'] = '';    
            } else {
            $data['pagination'] = $pagination->render();   
            $data['results'] = sprintf($this->language->get('text_pagination'), ($total_gallimages) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_gallimages - $limit)) ? $total_gallimages : ((($page - 1) * $limit) + $limit), $total_gallimages, ceil($total_gallimages / $limit));      
            }
            
            // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
            if (version_compare(VERSION, '2.2.0.0', '>=')) {
			if ($page == 1) {
			    $this->document->addLink($this->url->link('gallery/gallery', $url, true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('gallery/gallery', $url, true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('gallery/gallery', $url . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($total_gallimages / $limit) > $page) {
			    $this->document->addLink($this->url->link('gallery/gallery', $url . '&page='. ($page + 1), true), 'next');
			}
            } else {  
            if ($page == 1) {
			    $this->document->addLink($this->url->link('gallery/gallery', $url, 'SSL'), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('gallery/gallery', $url, 'SSL'), 'prev');
			} else {
			    $this->document->addLink($this->url->link('gallery/gallery', $url . '&page='. ($page - 1), 'SSL'), 'prev');
			}

			if ($limit && ceil($total_gallimages / $limit) > $page) {
			    $this->document->addLink($this->url->link('gallery/gallery', $url . '&page='. ($page + 1), 'SSL'), 'next');
			}    
            }

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

            if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $this->response->setOutput($this->load->view('gallery/gallery', $data));    
            } else {    
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/gallery.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/gallery/gallery.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/gallery/gallery.tpl', $data));
			}
            }
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('gallery/gallery', 'gallimage_id=' . $gallimage_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

            if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $this->response->setOutput($this->load->view('error/not_found', $data));    
            } else { 
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
            }
		}
	}

	public function agree() {
		$this->load->model('catalog/gallimage');

		if (isset($this->request->get['gallimage_id'])) {
			$gallimage_id = (int)$this->request->get['gallimage_id'];
		} else {
			$gallimage_id = 0;
		}

		$output = '';

		$gallimage_info = $this->model_catalog_gallimage->getGallimage($gallimage_id);

		if ($gallimage_info) {
			$output .= html_entity_decode($gallimage_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
		}

		$this->response->setOutput($output);
	}
}