<?php
class ModelAccountRole extends Model {
	public function addRole($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "role SET  name = '" . $this->db->escape($data['name']) . "', type = '" . $this->db->escape($data['type']) . "', date_added = NOW()");

		$role_id = $this->db->getLastId();

		return $role_id;
	}



	public function editRole($role_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "role SET name = '" . $this->db->escape($data['name']) . "', type = '" . $this->db->escape($data['type'])  . ", date_modified = NOW() WHERE role_id  = '" . (int)$role_id . "'");
	}

	public function deleteRole($role_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "role WHERE role_id = '" . (int)$role_id . "'");
	}

	public function getRole($role_id) {
		$role_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "role WHERE role_id = '" . (int)$role_id . "'");

        $role_data = array(
            'role_id'     => $role_query->row['role_id'],
            'name'      => $role_query->row['name'],
            'type'       => $role_query->row['type']
        );
        return $role_data;

	}




    public function joinRole($data)
    {
        return $this->db->query("INSERT INTO " . DB_PREFIX . "customer_role SET customer_id = '" . (int)$this->customer->getId() . "', creator = '1', role_id = '" . $data['role_id']. "'");
    }



    public function getAllRoles($filter_name='') {

	    $sql = "SELECT * FROM  " . DB_PREFIX . "role  WHERE 1";
	    if($filter_name)
        {
            $sql = " AND name like  '%" . $this->db->escape($filter_name) . "%'";
        }
        $query = $this->db->query($sql);
        if(!$query->rows)
        {
            $data['name'] = 'admin';
            $data['type'] = '1';
            $data['role_id'] = $this->addRole($data);
            if(!$data['role_id']) return array();
            return array($data);
        }
        return $query->rows;
    }

	public function getTotalRoles() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "role WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		return $query->row['total'];
	}
}