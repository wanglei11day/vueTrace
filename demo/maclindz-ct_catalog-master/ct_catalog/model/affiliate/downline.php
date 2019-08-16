<?php

class ModelAffiliateDownline extends Model {

	public function canSee($self_id, $affiliate_id, $max_level = false) {
		if($self_id == $affiliate_id) return true;
		$this->load->model('affiliate/mta_affiliate');
		$aff = $this->model_affiliate_mta_affiliate->getAffiliate($affiliate_id);
		return ($aff && in_array(intval($self_id), $aff['parents']) && (!$max_level || intval($aff['level_original']) <= $max_level));
	}

	public function countSubs($affiliate_id) {
		$res = $this->db->query("select count(*) as c from " . DB_PREFIX . "mta_affiliate where `parent_affiliate_id` = '" . (int)$affiliate_id . "'");
		return $res->row['c'];
	}
	
	public function loadSubs($affiliate_id, $max_level = false) {		
		$q = "SELECT (select COUNT(*) from " . DB_PREFIX . "mta_affiliate where `parent_affiliate_id` = mta1.`affiliate_id` ";
		if($max_level) $q .= " and level_original <= '" . $max_level . "' ";
		$q .= ") AS c, CONCAT(a.firstname, ' ', a.lastname) AS `name`, a.affiliate_id";
		if($this->config->get('mta_ypx_downline_email')) $q .= ", a.email";
		if($this->config->get('mta_ypx_downline_phone')) $q .= ", a.telephone"; 
		if($this->config->get('mta_ypx_downline_earnings')) $q .= ", (select sum(`amount`) from " . DB_PREFIX . "affiliate_transaction where affiliate_id  = mta1.`affiliate_id` and `amount` > 0) as e_te,
		(select sum(`amount`) from " . DB_PREFIX . "affiliate_transaction where affiliate_id  = mta1.`affiliate_id` and `amount` > 0 and date_added > subdate(now(), INTERVAL 1 month)) as e_elm";
		$q .= " FROM " . DB_PREFIX . "mta_affiliate mta1, " . DB_PREFIX . "affiliate a WHERE mta1.`parent_affiliate_id` = '" . (int)$affiliate_id . "' 
AND a.`affiliate_id`=mta1.`affiliate_id` ";
		if($max_level) $q .= " and mta1.level_original <= '" . $max_level . "' ";
		$q .= " GROUP BY mta1.`affiliate_id`";
		$res = $this->db->query($q);
		return $res->rows;
	}	

}
