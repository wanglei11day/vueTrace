<?php
class ModelCatalogGallimage extends Model {
    public function imgOri($filename) {
    if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $filename;
		} else {
			return $this->config->get('config_url') . 'image/' . $filename;
		}
    }
    
	public function getGallName($gallimage_id){
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "gallimage_description where gallimage_id= " . $gallimage_id . " limit 1");
		return $query->row['name'];
	}
	
	public function getGallimage($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "gallimage_image gi LEFT JOIN " . DB_PREFIX . "gallimage_image_description gid ON (gi.gallimage_image_id  = gid.gallimage_image_id) WHERE gi.gallimage_id = '" . (int)$this->request->get['gallimage_id'] . "' AND gid.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY gi.sort_order ASC";
        
        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getGallalbum($gallimage_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallimage g LEFT JOIN " . DB_PREFIX . "gallimage_description gd ON (g.gallimage_id = gd.gallimage_id) LEFT JOIN " . DB_PREFIX . "gallimage_to_store g2s ON (g.gallimage_id = g2s.gallimage_id) WHERE g.gallimage_id = '" . (int)$gallimage_id . "' AND gd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND g2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND g.status = '1'");

		return $query->row;
	}
    
    public function getTotalGallimages($gallimage_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallimage_image WHERE gallimage_id= " . $gallimage_id . "");

		return $query->row['total'];
	}
	
}