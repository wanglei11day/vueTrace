<?php

	class ModelCheckoutCftypCouponAffiliate extends Model {
	
		function getAffiliate($coupon_id) {
			if(!preg_match("/^\d+$/", $coupon_id)) return false;
			$res = $this->db->query("SELECT ca.ctype, a.affiliate_id, m.marketing_id, m.code from " . DB_PREFIX . "cftyp_coupon_affiliate ca left join " . DB_PREFIX . "affiliate a on a.affiliate_id = ca.affiliate_id left join " . DB_PREFIX . "marketing m on m.marketing_id = ca.marketing_id where ca.coupon_id = '" . (int)$coupon_id . "'");
			if($res->num_rows < 1) return false;
			if($res->row['ctype'] == 'a') {
				$this->load->model('affiliate/affiliate');
				$affiliate = $this->model_affiliate_affiliate->getAffiliate($res->row['affiliate_id']);
				if(!$affiliate || !$affiliate['status'] || !$affiliate['approved']) return false;
				$affiliate['cftyp_ctype'] = 'a';
				return $affiliate;
			}

			if(!$res->row['marketing_id'] || !$res->row['code']) return false;
			return array(
				'cftyp_ctype' => 'm',
				'marketing_id' => $res->row['marketing_id'],
				'code' => $res->row['code']
			);
		}	
	}
	
