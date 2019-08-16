<?php
class ModelAccountPasswordReset extends Model {
	public function addPasswordReset($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "password_reset SET customer_id = '" . (int)$data['customer_id'] . "', token = '" . $this->db->escape($data['token']) . "', date_added = NOW()");
		$password_reset_id = $this->db->getLastId();
		return $password_reset_id;
	}

	
	
	public function updatePasswordReset($password_reset_id,$data) {
		$this->db->query("UPDATE " . DB_PREFIX . "password_reset SET customer_id = '" . (int)$data['customer_id'] . "', token = '" . $this->db->escape($data['token']) . "' WHERE password_reset_id = '".$password_reset_id."'");
	}

	public function deletePasswordReset($password_reset_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "password_reset WHERE password_reset_id = '" . (int)$password_reset_id. "'");

	}

	public function getPasswordReset($password_reset_id) {
		$password_reset_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "password_reset WHERE password_reset_id = '" . (int)$password_reset_id . "'");
		return $password_reset_query->row;
		
	}

	public function getPasswordByCustomerId($customer_id) {
		$password_reset_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "password_reset WHERE customer_id = '" . (int)$customer_id . "'");
		return $password_reset_query->row;
	}
	
	public function getPasswordByToken($token) {
		$password_reset_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "password_reset WHERE token = '" . $token . "'");
		return $password_reset_query->row;
	}

	
}