<?php
class ModelExtensionExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}

	function getFreePayment($type) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'  AND code = 'free_checkout'");
		return $query->rows;
	}

    function getPaymentByCode($type,$code) {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'  AND code = '".$code."'");
        return $query->rows;
    }
	
}