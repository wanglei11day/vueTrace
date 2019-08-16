<?php
class ModelAccountDepartment extends Model {
    public function addDepartment($data) {
        $this->event->trigger('pre.admin.department.add', $data);



        $this->db->query("INSERT INTO " . DB_PREFIX . "department SET parent_id = '" . (int)$data['parent_id'] . "', `thumb` = '" . $this->db->escape($data['thumb']) . "', `name` = '" . $this->db->escape($data['name'])  . "', `email` = '" . $this->db->escape($data['email']) . "', member_id = '" . (int)$data['member_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', customer_id = '" . (int)$this->customer->getId(). "', type = '" . (int)$data['type'] . "', date_modified = NOW(), date_added = NOW()");

        $department_id = $this->db->getLastId();





        // MySQL Hierarchical Data Closure Table Pattern
        $level = 0;

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "department_path` WHERE department_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

        foreach ($query->rows as $result) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "department_path` SET `department_id` = '" . (int)$department_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

            $level++;
        }
        $this->db->query("INSERT INTO `" . DB_PREFIX . "department_path` SET `department_id` = '" . (int)$department_id . "', `path_id` = '" . (int)$department_id . "', `level` = '" . (int)$level . "'");

        return $department_id;

    }



    public function getChildrenDepartment($department_id){
        $children = $this->getDepartmentsById($department_id);
        return $children;
    }




public function updateDepartmentName($department_id,$name)
{

    return $this->db->query("UPDATE " . DB_PREFIX . "department SET name = '" . $this->db->escape($name) . "', date_modified = NOW() WHERE department_id = '" . (int)$department_id . "' AND customer_id = '" . (int)$this->customer->getId(). "'");
}
    public function editDepartment($department_id, $data) {
        $this->event->trigger('pre.admin.department.edit', $data);

        $this->db->query("UPDATE " . DB_PREFIX . "department SET parent_id = '" . (int)$data['parent_id']  . "', `email` = '" . $this->db->escape($data['email']). "', `name` = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "', type = '" . (int)$data['type'] . "', date_modified = NOW() WHERE department_id = '" . (int)$department_id . "' AND customer_id = '" . (int)$this->customer->getId(). "'");





        // MySQL Hierarchical Data Closure Table Pattern
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "department_path` WHERE path_id = '" . (int)$department_id . "' ORDER BY level ASC");

        if ($query->rows) {
            foreach ($query->rows as $department_path) {
                // Delete the path below the current one
                $this->db->query("DELETE FROM `" . DB_PREFIX . "department_path` WHERE department_id = '" . (int)$department_path['department_id'] . "' AND level < '" . (int)$department_path['level'] . "'");

                $path = array();

                // Get the nodes new parents
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "department_path` WHERE department_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

                // Get whats left of the nodes current path
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "department_path` WHERE department_id = '" . (int)$department_path['department_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

                // Combine the paths with a new level
                $level = 0;

                foreach ($path as $path_id) {
                    $this->db->query("REPLACE INTO `" . DB_PREFIX . "department_path` SET department_id = '" . (int)$department_path['department_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

                    $level++;
                }
            }
        } else {
            // Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "department_path` WHERE department_id = '" . (int)$department_id . "'");

            // Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "department_path` WHERE department_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "department_path` SET department_id = '" . (int)$department_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "department_path` SET department_id = '" . (int)$department_id . "', `path_id` = '" . (int)$department_id . "', level = '" . (int)$level . "'");
        }

    }

    public function deleteDepartment($department_id) {
        $this->event->trigger('pre.admin.department.delete', $department_id);

        $this->db->query("DELETE FROM " . DB_PREFIX . "department_path WHERE department_id = '" . (int)$department_id . "'");

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "department_path WHERE path_id = '" . (int)$department_id . "'");

        foreach ($query->rows as $result) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "customer_task WHERE department_id = '" . (int)$result['department_id'] . "'");
            $this->deleteDepartment($result['department_id']);
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "department WHERE department_id = '" . (int)$department_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_task WHERE department_id = '" . (int)$department_id . "'");

        $this->cache->delete('department');

        $this->event->trigger('post.admin.department.delete', $department_id);

    }

    public function deleteDepartmentTask($department_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "department_path WHERE department_id = '" . (int)$department_id . "'");
    }

    public function getAllDepartments(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "department");
        return $query->rows;
    }

    public function repairDepartments($parent_id = 0) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "department WHERE parent_id = '" . (int)$parent_id . "'");

        foreach ($query->rows as $department) {
            // Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "department_path` WHERE department_id = '" . (int)$department['department_id'] . "'");

            // Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "department_path` WHERE department_id = '" . (int)$parent_id . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "department_path` SET department_id = '" . (int)$department['department_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "department_path` SET department_id = '" . (int)$department['department_id'] . "', `path_id` = '" . (int)$department['department_id'] . "', level = '" . (int)$level . "'");

            $this->repairDepartments($department['department_id']);
        }
    }

    public function getDepartment($department_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(c.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "department_path cp  WHERE cp.department_id = c.department_id  GROUP BY cp.department_id) AS path FROM " . DB_PREFIX . "department c  WHERE c.department_id = '" . (int)$department_id . "'");

        return $query->row;
    }


    public function addCustomerDepartment($data) {
        return $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_department` SET department_id = '" . (int)$data['department_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "'");
    }






    public function getDepartmentsById($parent_id = 0) {
        $query = $this->db->query("SELECT c.* FROM " . DB_PREFIX . "department c LEFT JOIN " . DB_PREFIX . "department_path cd ON (c.department_id = cd.department_id)  WHERE cd.path_id = '" . (int)$parent_id . "'");
        return $query->rows;
    }




    public function searchDepartment($data = array()) {
        $andsql = '';

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'  AND c1.type = 0";
        }


        if (isset($data['filter_type'])) {
            $andsql .= " AND c1.type = '" . $this->db->escape($data['type']) . "'";
        }

        $andsql .= " AND d.path_id = '" . (int)$data['filter_department_id'] . "'";
        $sql =
            "SELECT 
    c1.name AS name,
    c1.parent_id,
    c1.thumb,
    c1.type AS type ,
    d.level,
    c1.member_id,
    c1.department_id
    FROM " . DB_PREFIX . "department c1 LEFT JOIN ". DB_PREFIX . "department_path d ON d.department_id = c1.department_id "
            . "  WHERE 1 $andsql";
        $sql .= " ORDER BY c1.type DESC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getDepartments($data = array()) {
        $andsql = '';

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'  AND type = 0";
        }


        if (isset($data['filter_type'])) {
            $andsql .= " AND c1.type = '" . $this->db->escape($data['type']) . "'";
        }

        $andsql .= " AND c1.parent_id = '" . (int)$data['filter_department_id'] . "'";
        $sql =
            "SELECT 
    c1.name AS name,
    c1.parent_id,
    c1.thumb,
    c1.type AS type ,
    d.level,
    c1.member_id,
    c1.department_id
    FROM " . DB_PREFIX . "department c1 LEFT JOIN ". DB_PREFIX . "department_path d ON d.department_id = c1.department_id AND d.path_id = c1.department_id"
            . "  WHERE 1 $andsql";
        $sql .= " ORDER BY c1.type DESC,c1.name ASC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }


    public function searchTaskProjects($department_id){
        $sql =
            "SELECT 
   *
    FROM " . DB_PREFIX . "customer_task a  left join oc_department_path b ON b.department_id = a.department_id WHERE b.path_id  = '".$department_id."'";
        //die($sql);
        $query = $this->db->query($sql);

        return $query->rows;
    }



    public function getTaskProjects($member_id,$department_id){
        $sql =
            "SELECT 
   *
    FROM " . DB_PREFIX . "customer_task a  WHERE a.customer_id =  '" . $member_id . "' AND department_id = '".$department_id."'";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getDepartmentTaskProjects($department_id){
        $sql = "
        SELECT
	t.*
FROM
	" . DB_PREFIX . "department_path dp
	INNER JOIN " . DB_PREFIX . "department d ON  d.department_id = dp.department_id 
	LEFT JOIN " . DB_PREFIX . "customer_task t ON  t.customer_id = d.member_id AND t.department_id = d.department_id 
WHERE
	dp.path_id = '".$department_id."' 
	AND d.type = 0
        ";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getMemberDepartments($data = array()) {
        $andsql = '';

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_type'])) {
            $andsql .= " AND c1.type = '" . $this->db->escape($data['type']) . "'";
        }

        $sql =
            "SELECT 
    c1.name AS name,
    c1.parent_id,
    c1.thumb,
    c1.type AS type ,
    c1.department_id
    FROM " . DB_PREFIX . "department c1 
    LEFT JOIN "

            . "  WHERE c1.member_id =  '" . (int)$this->customer->getId() . "' $andsql";
        $sql .= " ORDER BY c1.type DESC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getImages($image_ids) {
        $image_ids = "'".implode("','",$image_ids)."'";
        $sql = "SELECT c1.name AS name,c1.name ,c1.thumb,c1.size ,c1.width ,c1.height, c1.type AS type ,c1.department_id  FROM " . DB_PREFIX . "department c1 ". "  WHERE customer_id = '" . (int)$this->customer->getId(). "' AND c1.department_id IN ($image_ids)";

        $query = $this->db->query($sql);

        return $query->rows;
    }


    public function getDepartmentById($department_id) {
        $sql = "SELECT * FROM "  . DB_PREFIX . "department d  WHERE d.department_id = '".$department_id."'";
        $query = $this->db->query($sql);

        return $query->row;
    }


    public function isDepartment($department_id) {
        $sql = "SELECT * FROM "  . DB_PREFIX . "department d  WHERE d.department_id = '".$department_id."'";
        $query = $this->db->query($sql);

        return $query->row['type'];
    }


    public function getCustomerDepartment($filter) {
        $sql = "SELECT b.customer_id FROM "  . DB_PREFIX . "customer_department a LEFT JOIN "  . DB_PREFIX  ."customer b ON b.customer_id = a.customer_id WHERE b.email = '".$filter['email']."'  AND a.department_id = '".$filter['department_id']."'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getCustomerByDepartmentId($department_id) {
        $sql = "SELECT a.* FROM "    . DB_PREFIX  ."department a  WHERE a.department_id = '".$department_id."'";
        $query = $this->db->query($sql);
        return $query->row;
    }



    public function getCustomer($filter) {
        $sql = "SELECT b.customer_id FROM "    . DB_PREFIX  ."customer b  WHERE b.email = '".$filter['email']."'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getDepartmentCustomer($supervise) {
        $sql = "SELECT a.customer_id FROM "    . DB_PREFIX  ."department a  WHERE a.name = '".$supervise."' AND a.type = '0'";
        $query = $this->db->query($sql);
        return $query->row;
    }



    public function checkDepartmentExist($department_id,$name) {
        $sql = "SELECT COUNT(*) AS total FROM "  . DB_PREFIX . "department_path dp LEFT JOIN " . DB_PREFIX . "department d ON (d.department_id = dp.department_id)   WHERE dp.path_id = '".$department_id."' AND d.customer_id='".(int)$this->customer->getId()."' AND d.name='".$name."'";

        $query = $this->db->query($sql);

        if(empty($query->row['total'])){
            return false;
        }
        return true;

    }

    public function checkExist($department_id,$customer_id) {
        $sql = "SELECT COUNT(*) AS total FROM "  . DB_PREFIX . "department d    WHERE d.parent_id = '".$department_id."' AND d.member_id='".(int)$customer_id."' AND d.type = 0";
        $query = $this->db->query($sql);
        if(empty($query->row['total'])){
            return false;
        }
        return true;

    }


    public function getMyRootDepartments() {


        $sql = "
        select
d.*
from
(
SELECT
	dp.path_id parent_id
FROM
	oc_department_path dp
	INNER JOIN "  . DB_PREFIX . "department d ON d.department_id = dp.department_id 
WHERE
	d.member_id = '".(int)$this->customer->getId()."' 
	
GROUP BY dp.path_id ) a
LEFT JOIN "  . DB_PREFIX . "department d ON a.parent_id = d.department_id
	where d.parent_id = 0 AND d.type = 1
	ORDER BY d.name ASC
        ";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getMyDepartment($department_id) {
        $sql = "SELECT d.* FROM "  . DB_PREFIX . "department d WHERE d.department_id = '".$department_id."' AND d.member_id='".(int)$this->customer->getId()."' ";
        $query = $this->db->query($sql);
        return $query->row;
    }


    public function getMyDepartmentLevel($department_id) {
        $sql = "SELECT dp.department_id FROM "  . DB_PREFIX . "department_path dp LEFT JOIN " . DB_PREFIX . "department d ON d.department_id = dp.department_id     WHERE dp.path_id = '".$department_id."' AND d.member_id = ".(int)$this->customer->getId() .' AND d.type=0' ;
        $query = $this->db->query($sql);
        $deps = $query->rows;
        $min_level = 99999;

        $min_row = array();
        foreach ($deps as $dep)
        {
            $dep_id = $dep['department_id'];
            $sql = "SELECT dp.level FROM "  . DB_PREFIX . "department_path dp  WHERE dp.path_id = '".$dep_id."' AND dp.department_id = ".(int)$dep_id ;
            $query = $this->db->query($sql);
            $row = $query->row;
            if($min_level > $row['level'])
            {
                $min_level = $row['level'];
                $min_row = $row;
            }
        }
        return $min_row;
    }

    public function getMyDepartmentLevel2($department_id) {
        $sql = "SELECT dp.level FROM "  . DB_PREFIX . "department_path dp  WHERE dp.department_id = '".$department_id."' AND dp.path_id = ".$department_id;
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getDepartMentWithLevel($department_id,$level) {
        $sql = "SELECT dp.level FROM "  . DB_PREFIX . "department_path dp  WHERE dp.department_id = '".$department_id."' AND dp.level = ".$level;
        $query = $this->db->query($sql);
        return $query->row;
    }



    public function getTotalDepartments($data = array()) {

        $andsql = '';

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.name LIKE '%" . $this->db->escape($data['filter_name']) . "%' AND type = 0";
        }


        if (isset($data['filter_type'])) {
            $andsql .= " AND c1.type = '" . $this->db->escape($data['type']) . "'";
        }

        if (isset($data['filter_department_id'])&&$data['filter_department_id'] ==0) {

            $sql = "
SELECT
COUNT(*) AS total
FROM
(
SELECT 
    c1.name AS name,
    c1.name ,
    c1.parent_id,
    c1.thumb,
    c1.size ,
    c1.width ,
    c1.height,
    c1.type AS type ,
    0 as sort,
    c1.department_id
FROM
oc_customer c
INNER JOIN oc_group_department gd ON c.customer_group_id = gd.group_id
left JOin oc_department c1 ON c1.department_id = gd.department_id 
WHERE c.customer_id = '" . (int)$this->customer->getId(). "' $andsql 
UNION ALL
SELECT 
    c1.name AS name,
    c1.name,
    c1.parent_id,
    c1.thumb,
    c1.size ,
    c1.width ,
    c1.height,
    c1.type AS type ,
    1 as sort,
    c1.department_id
    FROM " . DB_PREFIX . "department c1 "
                . "  WHERE (c1.customer_id = '0' OR c1.customer_id =  '" . (int)$this->customer->getId() . "') $andsql) c1";
        }
        else
        {
            $andsql .= " AND c1.parent_id = '" . (int)$data['filter_department_id'] . "'";
            $sql =
                "SELECT 
    COUNT(*) AS total
    FROM " . DB_PREFIX . "department c1 "
                . "  WHERE (c1.customer_id = '0' OR c1.customer_id =  '" . (int)$this->customer->getId() . "') $andsql";
        }
        $query = $this->db->query($sql);
        return $query->row['total'];
    }

}
