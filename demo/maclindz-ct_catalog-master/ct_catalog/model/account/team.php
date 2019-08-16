<?php
class ModelAccountTeam extends Model {
	public function addTeam($data) {
		$this->event->trigger('pre.customer.add.team', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "team SET customer_id = '" . (int)$this->customer->getId() . "', name = '" . $this->db->escape($data['name'])   . "', date_added = NOW()");

		$team_id = $this->db->getLastId();

		$adminRole = $this->getAdminRole();

        $this->db->query("INSERT INTO " . DB_PREFIX . "team_customer SET customer_id = '" . (int)$this->customer->getId() . "', role_id = '".$adminRole['id']."', team_id = '" . $team_id. "'");

		$this->event->trigger('post.customer.add.team', $team_id);

		return $team_id;
	}

	private function getAdminRole()
    {
        $role_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "role WHERE type = '1'");

        if(!$role_query->row)
        {
            $data['name'] = 'admin';
            $data['type'] = '1';
            $data['role_id'] = $this->db->query("INSERT INTO " . DB_PREFIX . "role SET customer_id = '" . (int)$this->customer->getId() . "', name = '" . $this->db->escape($data['name']) . "', type = '" . $this->db->escape($data['type']) . "', date_added = NOW()");
            return $data;

        }
        return $role_query->row;

    }

	public function editTeam($team_id, $data) {
		$this->event->trigger('pre.customer.edit.team', $data);
        $this->db->query("UPDATE " . DB_PREFIX . "team SET name = '" . $this->db->escape($data['name']) . "' WHERE team_id  = '" . (int)$team_id . "' AND customer_id = '" . (int)$this->customer->getId() . "', date_modified = NOW()");

		$this->event->trigger('post.customer.edit.team', $team_id);
	}

	public function deleteTeam($team_id) {
		$this->event->trigger('pre.customer.delete.team', $team_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "team WHERE team_id = '" . (int)$team_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.delete.team', $team_id);
	}

	public function getTeam($team_id) {
		$team_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "team WHERE team_id = '" . (int)$team_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

        $team_data = array(
            'team_id'     => $team_query->row['team_id'],
            'name'      => $team_query->row['name']
        );
        return $team_data;

	}

	public function getJoinTeams() {
		$query = $this->db->query("SELECT b.*,a.role_id FROM  " . DB_PREFIX . "team_customer a left join   " . DB_PREFIX . "team b on b.team_id = a.team_id  WHERE a.customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->rows;
	}


    public function joinTeam($data)
    {
        return $this->db->query("INSERT INTO " . DB_PREFIX . "team_customer SET customer_id = '" . (int)$this->customer->getId() . "', role_id = '1', team_id = '" . $data['team_id']. "'");
    }





    public function getAllTeams($filter_name) {

	    if(!$filter_name)
        {
            return array();
        }
        $query = $this->db->query("SELECT team_id,name FROM  " . DB_PREFIX . "team  WHERE name like  '%" . $this->db->escape($filter_name) . "%'");

        return $query->rows;
    }

	public function getTotalTeams() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "team WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		return $query->row['total'];
	}

	
    public function getTeamCustomers($team_id) {

        if(!$team_id)
        {
            return array();
        }
        $query = $this->db->query("SELECT c.customer_id,c.firstname,c.lastname,c.email,c.telephone FROM  " . DB_PREFIX . "team a left join   " . DB_PREFIX . "team_customer b on b.team_id = a.id LEFT JOIN " . DB_PREFIX . "customer c ON b.customer_id = c.customer_id   WHERE a.id =  '" . (int)($team_id) . "'");

        return $query->rows;
    }


    /**添加团队成员
     * @param $data
     * @return mixed
     */
    public function addTeamCustomer($data) {


        $this->db->query("INSERT INTO " . DB_PREFIX . "team_customer SET customer_id = '" . (int)($data['customer_id']) . "', team_id = '" . (int)($data['team_id']) . "', role_id = '" .(int)($data['role_id'])  . "', date_added = NOW()");

        $id = $this->db->getLastId();

        return $id;
    }

    /**检查是否存在
     * @param $data
     * @return mixed
     */
    public function checkExist($team_id,$customer_id) {

        $sql = "SELECT COUNT(*) AS total FROM "  . DB_PREFIX . "team_customer     WHERE team_id = '".$team_id."' AND customer_id='".(int)$customer_id."'";
        $query = $this->db->query($sql);
        if(empty($query->row['total'])){
            return false;
        }
        return true;
    }

}