<?php
class ModelAccountSchool extends Model {
    public function addSchool($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "school SET customer_id = '" . (int)$this->customer->getId() . "', name = '" . $this->db->escape($data['name']) . "', org_type = '" . (int)$data['org_type']. "', telephone = '" . $this->db->escape($data['telephone'])  . "', address = '" . $this->db->escape($data['address']) . "', description = '" . $this->db->escape($data['description'])  . "', date_added = NOW()");

        $school_id = $this->db->getLastId();
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_school SET customer_id = '" . (int)$this->customer->getId() . "', creator = '1', school_id = '" . $school_id. "'");
        return $school_id;
    }

    public function editSchool($school_id, $data) {
        $this->event->trigger('pre.customer.edit.school', $data);
        $this->db->query("UPDATE " . DB_PREFIX . "school SET name = '" . $this->db->escape($data['name']) . "', org_type = '" . (int)$data['org_type'] . "', telephone = '" . $this->db->escape($data['telephone']). "', address = '" . $this->db->escape($data['address']) . "', description = '" . $this->db->escape($data['description'])  . "' WHERE school_id  = '" . (int)$school_id . "' AND customer_id = '" . (int)$this->customer->getId() . "', date_modified = NOW()");

        $this->event->trigger('post.customer.edit.school', $school_id);
    }

    public function modifyName($data) {
        $this->db->query("UPDATE  " . DB_PREFIX . "school SET name = '" . $this->db->escape($data['name']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', date_modified = NOW() WHERE school_id  = '" . (int)$data['school_id'] . "' AND customer_id = '" . (int)$this->customer->getId()."'" );
        return true;
    }

    public function deleteSchool($school_id) {
        $this->event->trigger('pre.customer.delete.school', $school_id);

        $this->db->query("DELETE FROM " . DB_PREFIX . "school WHERE school_id = '" . (int)$school_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

        $this->event->trigger('post.customer.delete.school', $school_id);
    }

    public function getSchool($school_id) {
        $school_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "school WHERE school_id = '" . (int)$school_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
        return $school_query->row;

    }

    public function getJoinSchooles() {
        $query = $this->db->query("SELECT b.*,a.creator FROM  " . DB_PREFIX . "customer_school a left join   " . DB_PREFIX . "school b on b.school_id = a.school_id  WHERE a.customer_id = '" . (int)$this->customer->getId() . "'");

        return $query->rows;
    }


    public function joinSchool($data)
    {
        return $this->db->query("INSERT INTO " . DB_PREFIX . "customer_school SET customer_id = '" . (int)$this->customer->getId() . "', creator = '1', school_id = '" . $data['school_id']. "'");
    }

    public function leaveSchool($school_id)
    {
        return $this->db->query("DELETE FROM " . DB_PREFIX . "customer_school WHERE customer_id = '" . (int)$this->customer->getId() . "' AND school_id = '" . (int) $school_id . "'");
    }



    public function getAllSchooles($filter_name) {

        $sql = "SELECT school_id,name FROM  " . DB_PREFIX . "school  WHERE 1 ";
        if($filter_name)
        {
            $sql .=  " AND name like  '%" . $this->db->escape($filter_name) . "%'";
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalSchooles() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "school WHERE customer_id = '" . (int)$this->customer->getId() . "'");
        return $query->row['total'];
    }
}