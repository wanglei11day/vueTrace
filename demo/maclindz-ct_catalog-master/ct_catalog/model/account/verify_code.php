<?php
class ModelAccountVerifyCode extends Model {
	public function addCode($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_verify_code` SET `phone` = '" . $this->db->escape($data['phone']) . "', `code` = '" . $this->db->escape($data['code'])  . "', `date_added` = NOW()");
	}

    public function updateStatus($phone,$code,$status) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer_verify_code SET status = '".$status."' WHERE phone = '" . $this->db->escape($phone) . "' AND code = '".$code."''");
    }

    public function getCodeByCode($code) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_verify_code WHERE code = '".$code."' order by date_added desc");

        return $query->row;
    }
    public function countCodesByPhone($phone) {
        
        $query = $this->db->query("SELECT count(code_id) as total FROM " . DB_PREFIX . "customer_verify_code WHERE phone = '" . $this->db->escape($phone) ."' AND date(date_added) = '".date('Y-m-d')."'");

        return $query->row['total'];
    }
}