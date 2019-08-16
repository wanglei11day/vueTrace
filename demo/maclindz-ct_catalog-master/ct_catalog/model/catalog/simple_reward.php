<?php
class ModelCatalogSimpleReward extends Model {
	public function getSimpleReward($simple_reward_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "simple_reward WHERE simple_reward_id = '" . (int)$simple_reward_id . "'");

        $query->row['category'] = json_decode($query->row['category'], true);
        $query->row['product'] = json_decode($query->row['product'], true);

		return $query->row;
	}

	public function getSimpleRewards() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "simple_reward sr LEFT JOIN " . DB_PREFIX . "simple_reward_to_store sr2s ON (sr.simple_reward_id = sr2s.simple_reward_id) WHERE sr.status = TRUE AND ((sr.date_start = '0000-00-00' OR sr.date_start < NOW()) AND (sr.date_end = '0000-00-00' OR sr.date_end > NOW())) AND sr2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY sr.sort_order ASC");

		return $query->rows;
	}

	public function getSimpleRewardValue($simple_reward_id, $customer_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "simple_reward_value WHERE simple_reward_id = '" . (int)$simple_reward_id . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

		return $query->row;
	}
}
