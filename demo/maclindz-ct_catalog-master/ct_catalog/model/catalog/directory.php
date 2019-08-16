<?php
class ModelCatalogDirectory extends Model {
	public function addDirectory($data) {
		$this->event->trigger('pre.admin.directory.add', $data);


		$this->db->query("INSERT INTO " . DB_PREFIX . "directory SET parent_id = '" . (int)$data['parent_id'] . "', `thumb` = '" . $this->db->escape($data['thumb']) . "', `name` = '" . $this->db->escape($data['name']) . "', `filename` = '" . $this->db->escape($data['filename']) . "', size = '" . (int)$data['size']. "', width = '" . (int)$data['width'] . "', height = '" . (int)$data['height']. "', sort_order = '" . (int)$data['sort_order'] . "', customer_id = '" . (int)$this->customer->getId(). "', type = '" . (int)$data['type'] . "', date_modified = NOW(), date_added = NOW()");

		$directory_id = $this->db->getLastId();

		

		
		
		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "directory_path` WHERE directory_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "directory_path` SET `directory_id` = '" . (int)$directory_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "directory_path` SET `directory_id` = '" . (int)$directory_id . "', `path_id` = '" . (int)$directory_id . "', `level` = '" . (int)$level . "'");

		return $directory_id;
		
	}
	

	
	private function getChildrenDirectory($directory, $path){
		$children_data = array();
		$children = $this->getDirectoriesById($directory['directory_id']);
		
		foreach ($children as $child) {
			$data = array(
				'filter_directory_id'  => $child['directory_id'],
				'filter_sub_directory' => true	
			);		
								
			$children_data[] = array(
				'name'  	=> $child['name'],
				'thumb'  	=> $child['thumb'],
				'directory_id'  	=> $child['directory_id'],
				'children' 	=> $this->getChildrenDirectory($child, $path . '_' . $child['directory_id']),
				'href'  => $this->url->link('product/directory', 'path=' . $path . '_' . $child['directory_id'])	
			);
			
		}
		return $children_data;
	}

	public function getCurrentSize()
    {
        $sql = "SELECT SUM(size) AS filesize  FROM "  . DB_PREFIX . "directory d WHERE d.customer_id='".(int)$this->customer->getId()."'";
        $query = $this->db->query($sql);
        return $query->row['filesize']?$query->row['filesize']:0;
    }

	public function editDirectory($directory_id, $data) {
		$this->event->trigger('pre.admin.directory.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "directory SET parent_id = '" . (int)$data['parent_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "', type = '" . (int)$data['type'] . "', date_modified = NOW() WHERE directory_id = '" . (int)$directory_id . "' AND customer_id = '" . (int)$this->customer->getId(). "'");

		

		
		
		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "directory_path` WHERE path_id = '" . (int)$directory_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $directory_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "directory_path` WHERE directory_id = '" . (int)$directory_path['directory_id'] . "' AND level < '" . (int)$directory_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "directory_path` WHERE directory_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "directory_path` WHERE directory_id = '" . (int)$directory_path['directory_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "directory_path` SET directory_id = '" . (int)$directory_path['directory_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "directory_path` WHERE directory_id = '" . (int)$directory_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "directory_path` WHERE directory_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "directory_path` SET directory_id = '" . (int)$directory_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "directory_path` SET directory_id = '" . (int)$directory_id . "', `path_id` = '" . (int)$directory_id . "', level = '" . (int)$level . "'");
		}
		
	}

	public function deleteDirectory($directory_id) {
		$this->event->trigger('pre.admin.directory.delete', $directory_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "directory_path WHERE directory_id = '" . (int)$directory_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "directory_path WHERE path_id = '" . (int)$directory_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteDirectory($result['directory_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "directory WHERE directory_id = '" . (int)$directory_id . "'");
		
		$this->cache->delete('directory');

		$this->event->trigger('post.admin.directory.delete', $directory_id);
		
	}
	
	public function getAllDirectories(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "directory");
		return $query->rows;
	}

	public function repairDirectories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "directory WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $directory) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "directory_path` WHERE directory_id = '" . (int)$directory['directory_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "directory_path` WHERE directory_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "directory_path` SET directory_id = '" . (int)$directory['directory_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "directory_path` SET directory_id = '" . (int)$directory['directory_id'] . "', `path_id` = '" . (int)$directory['directory_id'] . "', level = '" . (int)$level . "'");

			$this->repairDirectories($directory['directory_id']);
		}
	}

	public function getDirectory($directory_id) {

		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')  FROM " . DB_PREFIX . "directory_path cp LEFT JOIN " . DB_PREFIX ."directory cd1 ON (cp.path_id = cd1.directory_id AND cp.directory_id != cp.path_id)	 WHERE cp.directory_id = c.directory_id  GROUP BY cp.directory_id) AS path FROM " . DB_PREFIX . "directory c  WHERE c.directory_id = '" . (int)$directory_id . "'");
		return $query->row;
	}

	
	public function getDirectoriesById($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "directory c LEFT JOIN " . DB_PREFIX . "directory_description cd ON (c.directory_id = cd.directory_id) LEFT JOIN " . DB_PREFIX . "directory_to_store c2s ON (c.directory_id = c2s.directory_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}
	
	
	public function getDirectories($data = array()) {
	    $andsql = '';

        if (empty($data['filter_directory_id'])) {
            $andsql .= " AND c1.customer_id = '" . $this->customer->getId() . "' AND c1.parent_id = '" . (int)$data['filter_directory_id'] . "'";
        }
        else
        {
            $andsql .= " AND c1.parent_id = '" . (int)$data['filter_directory_id'] . "'";
        }
        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.filename LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_type'])) {
            $andsql .= " AND c1.type = '" . $this->db->escape($data['type']) . "'";
        }


        $sql =
            "SELECT 
    c1.name AS name,
    c1.filename,
    c1.parent_id,
    c1.thumb,
    c1.size ,
    c1.width ,
    c1.height,
    c1.type AS type ,
    c1.date_added,
    c1.directory_id
    FROM " . DB_PREFIX . "directory c1 "
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



    public function getShareDirectories($data = array()) {
        $andsql = '';

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.filename LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_type'])) {
            $andsql .= " AND c1.type = '" . $this->db->escape($data['type']) . "'";
        }

        if (isset($data['filter_directory_id'])&&$data['filter_directory_id']) {
            $andsql .= " AND c1.parent_id  = '" . (int)($data['filter_directory_id']) . "'";
        }

        $sql = "
        SELECT
	c1.name AS name,
    c1.filename ,
    c1.parent_id,
    c1.thumb,
    c1.size ,
    c1.width ,
    c1.height,
    c1.type AS type ,
    0 as sort,
    c1.date_added,
    c1.directory_id 
FROM
	(
	SELECT DISTINCT
		c.path_id 
	FROM
		oc_customer a
		LEFT JOIN oc_department b ON b.member_id = a.customer_id
		LEFT JOIN oc_department_path c ON c.department_id = b.department_id 
	WHERE
		a.customer_id = '" . (int)$this->customer->getId(). "'
		AND b.type = 0 
	) a
	LEFT JOIN oc_department b ON b.department_id = a.path_id 
	LEFT JOIN oc_department_image_group c ON c.department_id  = b.department_id
	LEFT JOIN oc_image_group_directory d ON c.image_group_id  = d.image_group_id
	LEFT JOIN oc_directory c1 ON c1.directory_id  = d.directory_id
WHERE
	b.type = 1  AND c1.directory_id is NOT NULL  $andsql ";

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
        $sql = "SELECT c1.name AS name,c1.filename ,c1.thumb,c1.size ,c1.width ,c1.height, c1.type AS type ,c1.directory_id  FROM " . DB_PREFIX . "directory c1 ". "  WHERE customer_id = '" . (int)$this->customer->getId(). "' AND c1.directory_id IN ($image_ids)";

        $query = $this->db->query($sql);

        return $query->rows;
    }


	public function isDirectory($directory_id) {
		$sql = "SELECT * FROM "  . DB_PREFIX . "directory d  WHERE d.directory_id = '".$directory_id."'";
		$query = $this->db->query($sql);

		return $query->row['type'];
	}


	public function checkExist($directory_id,$name) {
		$sql = "SELECT COUNT(*) AS total FROM "  . DB_PREFIX . "directory_path dp LEFT JOIN " . DB_PREFIX . "directory d ON (d.directory_id = dp.directory_id)   WHERE dp.path_id = '".$directory_id."' AND d.customer_id='".(int)$this->customer->getId()."' AND d.name='".$name."'";
		$query = $this->db->query($sql);
		
		if(empty($query->row['total'])){
				return false;
		}
		return true;
			
	}

	

	public function getTotalDirectories($data = array()) {

        $andsql = '';

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.filename LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.customer_id = \"".(int)$this->customer->getId()."\" AND c1.filename LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }


        if (isset($data['filter_type'])) {
            $andsql .= " AND c1.type = '" . $this->db->escape($data['type']) . "'";
        }

        $andsql .= " AND c1.parent_id = '" . (int)$data['filter_directory_id'] . "'";
        $sql =
            "SELECT 
    COUNT(*) AS total
    FROM " . DB_PREFIX . "directory c1 LEFT JOIN " . DB_PREFIX . "directory_path dp on c1.directory_id = dp.directory_id"
            . "  WHERE dp.path_id = '".(int)$data['filter_directory_id']."' $andsql";
        $query = $this->db->query($sql);
		return $query->row['total'];
	}



    public function getTotalShareDirectories($data = array()) {

        $andsql = '';

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.customer_id = \"".(int)$this->customer->getId()."\" AND c1.filename LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }


        if (isset($data['filter_type'])) {
            $andsql .= " AND c1.type = '" . $this->db->escape($data['type']) . "'";
        }

        $sql = "
        SELECT
	COUNT(*) AS total
FROM
	(
	SELECT DISTINCT
		c.path_id 
	FROM
		oc_customer a
		LEFT JOIN oc_department b ON b.member_id = a.customer_id
		LEFT JOIN oc_department_path c ON c.department_id = b.department_id 
	WHERE
		a.customer_id = '" . (int)$this->customer->getId(). "'
		AND b.type = 0 
	) a
	LEFT JOIN oc_department b ON b.department_id = a.path_id 
	LEFT JOIN oc_department_image_group c ON c.department_id  = b.department_id
	LEFT JOIN oc_image_group_directory d ON c.image_group_id  = d.image_group_id
	LEFT JOIN oc_directory c1 ON c1.directory_id  = d.directory_id
WHERE
	b.type = 1  AND c1.directory_id is NOT NULL  $andsql ";
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function updateName($data){

        $this->db->query("UPDATE " . DB_PREFIX . "directory SET filename = '" . $data['name'] . "' WHERE directory_id = '" . $data['path_id'] . "' AND customer_id  = '".$this->customer->getId()."'");
    }
}
