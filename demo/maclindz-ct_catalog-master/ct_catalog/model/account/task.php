<?php
class ModelAccountTask extends Model {
	public function addTask($data) 
	{
		$this->event->trigger('pre.customer.add.task', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_task SET creator_id = '" . (int)$this->customer->getId() . "', name = '" . $this->db->escape($data['name']) . "', priority = '" . $this->db->escape($data['priority']). "', status = '" . $this->db->escape($data['status']). "', description = '" . $this->db->escape($data['description']). "', tag = '" . $this->db->escape($data['tag']) . "', department_id = '" . (int)$data['department_id'] . "', customer_creation_id = '" . (int)$data['customer_creation_id'] . "', customer_id = '" . $this->db->escape($data['customer_id']). "', date_end = '" . $this->db->escape($data['date_end']) . "', sort = '" . $this->db->escape($data['sort']) . "', date_modified = NOW(), date_added = NOW()");

		$task_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer_design SET visiable = '0' ,customer_id='". $this->db->escape($data['customer_id'])."' WHERE customer_creation_id = '" . (int)$data['customer_creation_id'] . "'");
		$this->event->trigger('post.customer.add.task', $task_id);

		return $task_id;
	}

	public function editTask($task_id, $data) {
		$this->event->trigger('pre.customer.edit.task', $data);

		$task_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_task WHERE task_id = '" . (int)$task_id . "'");

		if($task_query->row){
			$this->db->query("UPDATE " . DB_PREFIX . "customer_design SET visiable = '1' WHERE customer_creation_id = '" . $task_query->row['customer_creation_id'] . "'");
		}
		$this->db->query("UPDATE " . DB_PREFIX . "customer_task SET name = '" . $this->db->escape($data['name']) . "', priority = '" . $this->db->escape($data['priority']). "', status = '" . $this->db->escape($data['status']). "', description = '" . $this->db->escape($data['description']). "', tag = '" . $this->db->escape($data['tag']) . "', customer_id = '" . $this->db->escape($data['customer_id']). "', department_id = '" . (int)$data['department_id'] . "', customer_creation_id = '" . $this->db->escape($data['customer_creation_id']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', sort = '" . $this->db->escape($data['sort']) . "', date_modified = NOW() WHERE task_id = '".$task_id."'");


		$this->db->query("UPDATE " . DB_PREFIX . "customer_design SET visiable = '0' WHERE customer_creation_id = '" . (int)$data['customer_creation_id'] . "'");

		$this->event->trigger('post.customer.edit.task', $task_id);
	}


	public function updateStatus($task_id,$status)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "customer_task SET status = '$status' WHERE task_id = '" . (int)$task_id . "'");

    }

	public function deleteTask($task_id) {
		$this->event->trigger('pre.customer.delete.task', $task_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_task WHERE task_id = '" . (int)$task_id . "' AND creator_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.delete.task', $task_id);
	}

	public function getTask($task_id) {
		$task_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_task WHERE task_id = '" . (int)$task_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if ($task_query->num_rows) {
			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$task_query->row['customer_id'] . "'");
			$excutor = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];

			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$task_query->row['creator_id'] . "'");
			$creator = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];
			

			$project_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design` WHERE customer_creation_id = '" . (int)$task_query->row['customer_creation_id'] . "'");

			$project_name = $project_query->row['name'];
			
			$task_data = array(
				'task_id'     => $task_query->row['task_id'],
				'name'      => $task_query->row['name'],
				'tag'       => $task_query->row['tag'],
				'status'       => $task_query->row['status'],
				'priority'       => $task_query->row['priority'],
				'description'       => $task_query->row['description'],
				'creator_id'        => $task_query->row['creator_id'],

				'customer_creation_id'      => $task_query->row['customer_creation_id'],
				'date_end'      => $task_query->row['date_end'],
				'customer_id'       => $task_query->row['customer_id'],
				'save_key'			=> $project_query->row['save_key'],
				'product_id'        => $project_query->row['product_id'],
				'creation_id'        => $project_query->row['creation_id'],
				'empty_page_count'        => $project_query->row['empty_page_count'],
				'low_resolution_count'        => $project_query->row['low_resolution_count'],	
				'total_page_count'        => $project_query->row['total_page_count'],
				'excutor'   =>$excutor,
				'sort'           => $task_query->row['sort'],
				'creator'        => $creator,
				);

			return $task_data;
		} else {
			return false;
		}
	}


	public function getTaskById($task_id) {
		$task_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_task WHERE task_id = '" . (int)$task_id . "'");

		if ($task_query->num_rows) {
			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$task_query->row['customer_id'] . "'");
			$excutor = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];

			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$task_query->row['creator_id'] . "'");
			$creator = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];
			

			$project_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design` WHERE customer_creation_id = '" . (int)$task_query->row['customer_creation_id'] . "'");

            $project_history_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design_log` WHERE customer_creation_id = '" . (int)$task_query->row['customer_creation_id'] . "' ORDER BY customer_design_log_id DESC ");
            if($project_history_query->row)
            {
                $save_key = $project_history_query->row['save_key'];
            }
            else{
                $save_key = $project_query->row['save_key'];
            }
			$project_name = $project_query->row['name'];
			
			$task_data = array(
				'task_id'     => $task_query->row['task_id'],
				'name'      => $task_query->row['name'],
				'tag'       => $task_query->row['tag'],
				'status'       => $task_query->row['status'],
				'priority'       => $task_query->row['priority'],
				'description'       => $task_query->row['description'],
				'creator_id'        => $task_query->row['creator_id'],

				'customer_creation_id'      => $task_query->row['customer_creation_id'],
				'date_end'      => $task_query->row['date_end'],
				'customer_id'       => $task_query->row['customer_id'],
				'save_key'			=> $save_key,
				'product_id'        => $project_query->row['product_id'],
				'creation_id'        => $project_query->row['creation_id'],
				'empty_page_count'        => $project_query->row['empty_page_count'],
				'low_resolution_count'        => $project_query->row['low_resolution_count'],	
				'total_page_count'        => $project_query->row['total_page_count'],
				'excutor'   =>$excutor,
				'sort'           => $task_query->row['sort'],
				'creator'        => $creator,
				);

			return $task_data;
		} else {
			return false;
		}
	}

	public function getPostTask($task_id) {
		$task_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_task WHERE task_id = '" . (int)$task_id . "' AND creator_id = '" . (int)$this->customer->getId() . "'");

		if ($task_query->num_rows) {
			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$task_query->row['customer_id'] . "'");
			$excutor = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];

			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$task_query->row['creator_id'] . "'");
			$creator = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];
			

			$project_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design` WHERE customer_creation_id = '" . (int)$task_query->row['customer_creation_id'] . "'");

			$project_name = $project_query->row['name'];
			
			$task_data = array(
				'task_id'     => $task_query->row['task_id'],
				'name'      => $task_query->row['name'],
				'tag'       => $task_query->row['tag'],
				'status'       => $task_query->row['status'],
				'priority'       => $task_query->row['priority'],
				'description'       => $task_query->row['description'],
				'creator_id'        => $task_query->row['creator_id'],
				'customer_creation_id'      => $task_query->row['customer_creation_id'],
				'date_end'      => $task_query->row['date_end'],
				'customer_id'       => $task_query->row['customer_id'],
				'save_key'			=> $project_query->row['save_key'],
				'product_id'        => $project_query->row['product_id'],
				'creation_id'        => $project_query->row['creation_id'],
				'empty_page_count'        => $project_query->row['empty_page_count'],
				'low_resolution_count'        => $project_query->row['low_resolution_count'],	
				'total_page_count'        => $project_query->row['total_page_count'],
				'excutor'   =>$excutor,
				'sort'           => $task_query->row['sort'],
				'creator'        => $creator,
				'project'           => $project_name,
				'actions'			=>array()
			);

			return $task_data;
		} else {
			return false;
		}
	}

	public function getTaskTags(){
			$query = $this->db->query("SELECT tag FROM " . DB_PREFIX . "customer_task WHERE customer_id = '" . (int)$this->customer->getId() . "' AND status != 2 AND status != 3  GROUP by tag");
			return $query->rows;
	}


	public function getHistoryTasks($data) {
		$task_data = array();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_task WHERE (creator_id = '" . (int)$this->customer->getId() . "' OR customer_id = '" . (int)$this->customer->getId() . "') AND (status = 3 )";


		if (!empty($data['filter_tag'])) {
				$sql .= " AND tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
		}

		$sort_data = array(
			'tag'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'tag') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY sort";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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
		foreach ($query->rows as $result) {
			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['customer_id'] . "'");
            if(!$customer_query->row){
                continue;
            }
			$excutor = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];

			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['creator_id'] . "'");
			$creator = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];
			

			$project_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design` WHERE customer_creation_id = '" . (int)$result['customer_creation_id'] . "'");

			$project_name = $project_query->row['name'];
			
			$task_data[] = array(
				'task_id'     => $result['task_id'],
				'name'      => $result['name'],
				'tag'       => $result['tag'],
				'status'       => $result['status'],
				'priority'       => $result['priority'],
				'description'       => $result['description'],
				'creator_id'        => $result['creator_id'],
				'customer_creation_id'      => $result['customer_creation_id'],
				'date_end'      => $result['date_end'],
				'customer_id'       => $result['customer_id'],
				'save_key'			=> $project_query->row['save_key'],
				'product_id'        => $project_query->row['product_id'],
				'creation_id'        => $project_query->row['creation_id'],
				'empty_page_count'        => $project_query->row['empty_page_count'],
				'low_resolution_count'        => $project_query->row['low_resolution_count'],	
				'total_page_count'        => $project_query->row['total_page_count'],
				'excutor'   =>$excutor,
				'sort'           => $result['sort'],
				'creator'        => $creator,
				'date_added'     => $result['date_added'],
				'date_modified'     => $result['date_modified'],
				'project'           => $project_name,
				'actions'			=>array()

			);
		}

		return $task_data;
	}


    public function getTasksByIds($member_id,$ids) {
        $task_data = array();
        $sql = "SELECT * FROM " . DB_PREFIX . "customer_task WHERE customer_id = '" . $member_id . "'  AND task_id in ('$ids')";
        $sql .= " ORDER BY sort ASC";

        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['customer_id'] . "'");
            $excutor = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];

            $customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['creator_id'] . "'");
            if(!$customer_query->row){
                continue;
            }
            $creator = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];


            $project_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design` WHERE customer_creation_id = '" . (int)$result['customer_creation_id'] . "'");

            $project_name = $project_query->row['name'];

            $task_data[] = array(
                'task_id'     => $result['task_id'],
                'name'      => $result['name'],
                'tag'       => $result['tag'],
                'status'       => $result['status'],
                'priority'       => $result['priority'],
                'description'       => $result['description'],
                'creator_id'        => $result['creator_id'],
                'customer_creation_id'      => $result['customer_creation_id'],
                'date_end'      => $result['date_end'],
                'customer_id'       => $result['customer_id'],
                'save_key'			=> $project_query->row['save_key'],
                'product_id'        => $project_query->row['product_id'],
                'creation_id'        => $project_query->row['creation_id'],
                'empty_page_count'        => $project_query->row['empty_page_count'],
                'low_resolution_count'        => $project_query->row['low_resolution_count'],
                'total_page_count'        => $project_query->row['total_page_count'],
                'excutor'   =>$excutor,
                'sort'           => $result['sort'],
                'creator'        => $creator,
                'date_added'     => $result['date_added'],
                'date_modified'     => $result['date_modified'],
                'project'           => $project_name,
                'actions'			=>array()

            );
        }

        return $task_data;
    }


    public function getDepartmentTasks($data)
    {

        $task_data = array();

        $sql = "SELECT * FROM " . DB_PREFIX . "customer_task a  ";


        if (!empty($data['filter_department_id'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "department_path b  ON b.department_id = a.department_id";
        }


        if (!empty($data['filter_creator'])) {
            $sql .= " LEFT JOIN  " . DB_PREFIX . "customer c ON  c.executor_id = a.customer_id ";
        }


        if (!empty($data['filter_executor'])) {
            $sql .= " LEFT JOIN  " . DB_PREFIX . "customer c ON  c.customer_id = a.customer_id ";
        }

        $sql .= " WHERE 1 ";

        if (!empty($data['filter_task'])) {
            $sql .= " AND a.name LIKE '%" . $this->db->escape($data['filter_task']) . "%'";
        }

        if (!empty($data['filter_department_id'])) {
            $sql .= " AND b.path_id = '" . $data['filter_department_id'] . "'";
        }


        if (!empty($data['filter_creator'])) {
            $sql .= " c.name LIKE '%" . $this->db->escape($data['filter_creator']) . "%'";
        }


        if (!empty($data['filter_executor'])) {
            $sql .= " d.name  LIKE '%" . $this->db->escape($data['filter_executor']) . "%'";
        }



        $sort_data = array(
            'a.task_id'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'tag') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY a.task_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

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
        foreach ($query->rows as $result) {
            $customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['customer_id'] . "'");
            $excutor = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];

            $customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['creator_id'] . "'");
            if(!$customer_query->row){
                // continue;
            }
            $creator = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];


            $project_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design` WHERE customer_creation_id = '" . (int)$result['customer_creation_id'] . "'");

            $project_name = $project_query->row['name'];

            $task_data[] = array(
                'task_id'     => $result['task_id'],
                'name'      => $result['name'],
                'tag'       => $result['tag'],
                'status'       => $result['status'],
                'priority'       => $result['priority'],
                'description'       => $result['description'],
                'creator_id'        => $result['creator_id'],
                'customer_creation_id'      => $result['customer_creation_id'],
                'date_end'      => $result['date_end'],
                'customer_id'       => $result['customer_id'],
                'save_key'			=> $project_query->row['save_key'],
                'product_id'        => $project_query->row['product_id'],
                'creation_id'        => $project_query->row['creation_id'],
                'empty_page_count'        => $project_query->row['empty_page_count'],
                'low_resolution_count'        => $project_query->row['low_resolution_count'],
                'total_page_count'        => $project_query->row['total_page_count'],
                'excutor'   =>$excutor,
                'sort'           => $result['sort'],
                'creator'        => $creator,
                'date_added'     => $result['date_added'],
                'date_modified'     => $result['date_modified'],
                'project'           => $project_name,
                'actions'			=>array()

            );
        }

        return $task_data;
    }
	public function getTasks($data) {
		$task_data = array();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_task WHERE customer_id = '" . (int)$this->customer->getId() . "'  AND status != 2 AND status != 3 ";

		if (!empty($data['filter_tag'])) {
				$sql .= " AND tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
		}

		$sort_data = array(
			'tag'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'tag') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY sort";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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
		foreach ($query->rows as $result) {
			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['customer_id'] . "'");
			$excutor = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];

			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['creator_id'] . "'");
            if(!$customer_query->row){
               // continue;
            }
			$creator = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];
			

			$project_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design` WHERE customer_creation_id = '" . (int)$result['customer_creation_id'] . "'");

			$project_name = $project_query->row['name'];
			
			$task_data[] = array(
				'task_id'     => $result['task_id'],
				'name'      => $result['name'],
				'tag'       => $result['tag'],
				'status'       => $result['status'],
				'priority'       => $result['priority'],
				'description'       => $result['description'],
				'creator_id'        => $result['creator_id'],
				'customer_creation_id'      => $result['customer_creation_id'],
				'date_end'      => $result['date_end'],
				'customer_id'       => $result['customer_id'],
				'save_key'			=> $project_query->row['save_key'],
				'product_id'        => $project_query->row['product_id'],
				'creation_id'        => $project_query->row['creation_id'],
				'empty_page_count'        => $project_query->row['empty_page_count'],
				'low_resolution_count'        => $project_query->row['low_resolution_count'],	
				'total_page_count'        => $project_query->row['total_page_count'],
				'excutor'   =>$excutor,
				'sort'           => $result['sort'],
				'creator'        => $creator,
				'date_added'     => $result['date_added'],
				'date_modified'     => $result['date_modified'],
				'project'           => $project_name,
				'actions'			=>array()

			);
		}

		return $task_data;
	}


	

	public function getPostTaskTags(){
			$query = $this->db->query("SELECT tag FROM " . DB_PREFIX . "customer_task WHERE creator_id = '" . (int)$this->customer->getId() . "' AND status != 2 AND status != 3  GROUP by tag");
			return $query->rows;
	}




	public function getPostTasks($data) {
		
		$task_data = array();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_task WHERE creator_id = '" . (int)$this->customer->getId() . "' AND status != 3 ";

		if (!empty($data['filter_tag'])) {
				$sql .= " AND tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
		}

		$sort_data = array(
			'tag'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'tag') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY sort";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 200;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['customer_id'] . "'");
			if(!$customer_query->row){
			    continue;
            }
			$excutor = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];

			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$result['creator_id'] . "'");
			$creator = $customer_query->row['firstname'].' '.$customer_query->row['lastname'];
			

			$project_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_design` WHERE customer_creation_id = '" . (int)$result['customer_creation_id'] . "'");

			$project_name = $project_query->row['name'];
			
			$task_data[] = array(
				'task_id'     => $result['task_id'],
				'name'      => $result['name'],
				'tag'       => $result['tag'],
				'status'       => $result['status'],
				'priority'       => $result['priority'],
				'description'       => $result['description'],
				'creator_id'        => $result['creator_id'],
				'customer_creation_id'      => $result['customer_creation_id'],
				'date_end'      => $result['date_end'],
				'customer_id'       => $result['customer_id'],
				'save_key'			=> $project_query->row['save_key'],
				'product_id'        => $project_query->row['product_id'],
				'creation_id'        => $project_query->row['creation_id'],
				'empty_page_count'        => $project_query->row['empty_page_count'],
				'low_resolution_count'        => $project_query->row['low_resolution_count'],	
				'total_page_count'        => $project_query->row['total_page_count'],
				'excutor'   =>$excutor,
				'sort'           => $result['sort'],
				'creator'        => $creator,
				'date_added'     => $result['date_added'],
				'date_modified'     => $result['date_modified'],
				'project'           => $project_name
			);
		}

		return $task_data;
	}

	public function getTotalTasks() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_task WHERE customer_id = '" . (int)$this->customer->getId() . "'  AND status != 2 AND status != 3 ");

		return $query->row['total'];
	}

	public function getTotalPostTasks() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_task WHERE creator_id = '" . (int)$this->customer->getId() . "'  AND status != 2 AND status != 3 ");

		return $query->row['total'];
	}

	public function getTotalHistoryTasks() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_task WHERE (creator_id = '" . (int)$this->customer->getId() . "' OR customer_id = '" . (int)$this->customer->getId() . "') AND (status = 2 OR status = 3 ) ");

		return $query->row['total'];
	}
}