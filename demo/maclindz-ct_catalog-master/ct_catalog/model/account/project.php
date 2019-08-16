<?php
class ModelAccountProject extends Model {
	public function addProject($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "school_project SET customer_id = '" . (int)$this->customer->getId() . "', name = '" . $this->db->escape($data['name']). "', order_date = '" . $this->db->escape($data['order_date']) . "', options = '" . $this->db->escape($data['options']). "', product_id = '" . (int)($data['product_id']) . "', pages = '" . (int)($data['pages']). "', year = '" . (int)($data['year']). "', type = '" . (int)($data['type']). "', qty = '" . (int)($data['qty'])  . "', school_id = '" . (int)($data['school_id']) . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");

		$project_id = $this->db->getLastId();
        $adminRole = $this->getAdminRole();

        $this->db->query("INSERT INTO " . DB_PREFIX . "project_customer SET customer_id = '" . (int)$this->customer->getId() . "', role_id = '".$adminRole['id']."', project_id = '" . $project_id. "'");

		return $project_id;
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


    public function copyProject($data) {
	    $project_id = $data['project_id'];
	    $project = $this->getProject($project_id);
	    if($project)
        {
            return $this->addProject($data);
        }
        else
        {
            return false;
        }
    }


    public function changeOrg($data) {

        $project_id = $data['project_id'];
        $school_id = $data['school_id'];
        if($project_id&&$school_id)
        {
            $this->db->query("UPDATE " . DB_PREFIX . "school_project SET  school_id = '" . (int)($data['school_id'])   . "', date_modified = NOW() WHERE project_id  = '" . (int)$project_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

            return true;
        }
        else
        {
            return false;
        }
    }

	public function editProject($project_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "school_project SET name = '" . $this->db->escape($data['name']) . "', order_date = '" . $this->db->escape($data['order_date']). "', options = '" . $this->db->escape($data['options']) . "', pages = '" . (int)($data['pages']). "', year = '" . (int)($data['year']). "', type = '" . (int)($data['type']). "', qty = '" . (int)($data['qty']). "', product_id = '" . (int)($data['product_id']) . "', school_id = '" . (int)($data['school_id']) . "', description = '" . $this->db->escape($data['description'])  . "' WHERE project_id  = '" . (int)$project_id . "' AND customer_id = '" . (int)$this->customer->getId() . "', date_modified = NOW()");

	}

	public function deleteProject($project_id) {
		$res = $this->db->query("DELETE FROM " . DB_PREFIX . "school_project WHERE project_id = '" . (int)$project_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
		if($res)
        {
            $this->db->query("DELETE FROM " . DB_PREFIX . "project_section WHERE project_id = '" . (int)$project_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "section_image WHERE project_id = '" . (int)$project_id . "'");
        }
        return $res;
	}

	public function getProject($project_id) {
		$project_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "school_project WHERE project_id = '" . (int)$project_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

        return $project_query->row;

	}


    /**
     * 获取最近10张图片
     * @param $project_id
     * @param int $default_size
     * @return mixed
     */
    public function getProjectRecentPhotos($project_id,$default_size=10)
    {
        $sql = "SELECT a.*,b.name,b.type FROM  " . DB_PREFIX . "section_image a   WHERE a.project_id = '" . (int)$project_id . "' ORDER BY id desc LIMIT " . (int)$default_size;

        $query = $this->db->query($sql);
        return $query->rows;
    }

	public function getJoinProjectes($data) {

        if (!$data['start']) {
            $data['start'] = 0;
        }

        if (!$data['limit']) {
            $data['limit'] = 10;
        }

        $sql = "SELECT a.*,b.name,b.type FROM  " . DB_PREFIX . "project_customer a inner join   " . DB_PREFIX . "school_project b on b.project_id = a.project_id  WHERE a.customer_id = '" . (int)$this->customer->getId() . "'LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];



        if($data['filter_name'])
        {
            $sql .=  " AND name like  '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        $query = $this->db->query($sql);
		return $query->rows;
	}


    public function getSchoolProjectes($data) {

	    $school_id = $data['school_id'];

        if (!$data['start']) {
            $data['start'] = 0;
        }

        if (!$data['limit']) {
            $data['limit'] = 10;
        }

        $sql = "SELECT b.* FROM  " . DB_PREFIX . "project_customer a inner join   " . DB_PREFIX . "school_project b on b.project_id = a.project_id   WHERE a.customer_id = '" . (int)$this->customer->getId() . "'  AND b.school_id = '" . (int)$school_id . "' LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

        $query = $this->db->query($sql);
        return $query->rows;
    }


    public function getProjectCustomers($data) {

        $project_id = $data['filter_project_id'];

        $sql = "SELECT  c.firstname,c.lastname,b.name as role,b.type,a.section_id,d.name as section_name FROM  " . DB_PREFIX . "project_customer a inner join   " . DB_PREFIX . "role b on b.id = a.role_id inner join   " . DB_PREFIX . "customer c on c.customer_id = a.customer_id left join   " . DB_PREFIX . "project_section d on d.section_id = a.section_id   WHERE  a.project_id = '" . (int)$project_id . "' ORDER BY b.type desc";
        $query = $this->db->query($sql);
        return $query->rows;
    }


    public function joinProject($data)
    {
        return $this->db->query("INSERT INTO " . DB_PREFIX . "project_customer SET customer_id = '" . (int)$this->customer->getId() . "', creator = '1', project_id = '" . $data['project_id']. "'");
    }




    public function getProjectes($filter) {
        $sql = "SELECT * FROM  " . DB_PREFIX . "school_project WHERE customer_id = '" . (int)$this->customer->getId() . "'";
	    if($filter['filter_name'])
        {
            $sql.=  " AND name like  '%" . $this->db->escape($filter['filter_name']) . "%'";
        }
        if($filter['filter_school_id'])
        {
            $sql.=  " AND school_id =  '" . $filter['filter_school_id'] . "%'";
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }

	public function getTotalProjectes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "school_project WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		return $query->row['total'];
	}


    /**添加团队成员
     * @param $data
     * @return mixed
     */
    public function addProjectCustomer($data) {


        $this->db->query("INSERT INTO " . DB_PREFIX . "project_customer SET customer_id = '" . (int)($data['customer_id']) . "', project_id = '" . (int)($data['project_id']) . "', role_id = '" .(int)($data['role_id'])  . "', date_added = NOW()");

        $id = $this->db->getLastId();

        return $id;
    }




}