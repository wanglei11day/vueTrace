<?php
class ModelAccountSection extends Model {
	public function addSection($data) {



		$this->db->query("INSERT INTO " . DB_PREFIX . "project_section SET customer_id = '" . (int)$this->customer->getId() . "', name = '" . $this->db->escape($data['name']) . "', page_start = '" . (int)($data['page_start']). "', page_end = '" . (int)($data['page_end']) . "', pages = '" . (int)($data['pages'])  . "', blanks = '" . (int)($data['blanks'])  . "', project_id = '" . (int)($data['project_id']) . "', sort = '" . (int)($data['sort']) . "', date_added = NOW()");

		$section_id = $this->db->getLastId();


		return $section_id;
	}

	public function editSection($section_id, $data) {
		$this->event->trigger('pre.customer.edit.section', $data);
        $this->db->query("UPDATE " . DB_PREFIX . "project_section SET name = '" . $this->db->escape($data['name']) . "', pages = '" . (int)($data['pages']) . "', blanks = '" . (int)($data['blanks']) . "', page_start = '" . (int)($data['page_start']). "', page_end = '" . (int)($data['page_end']) . "', project_id = '" . (int)($data['project_id']) . "', sort = '" . (int)($data['sort'])  . "' WHERE section_id  = '" . (int)$section_id . "' AND customer_id = '" . (int)$this->customer->getId() . "', date_modified = NOW()");

		$this->event->trigger('post.customer.edit.section', $section_id);
	}


    public function updateDesign($data) {

        $creation_id = $this->db->escape($data['creation_id']);
        $section_id = $this->db->escape($data['section_id']);

        $save_key = $this->db->escape($data['save_key']);
        $picqty = isset($data['picqty'])?$data['picqty']:0;
        $empty_page_count = isset($data['empty_page_count'])?$data['empty_page_count']:0;
        $low_resolution_count = isset($data['low_resolution_count'])?$data['low_resolution_count']:0;
        $total_page_count = isset($data['total_page_count'])?$data['total_page_count']:0;

        $customer_id = $this->customer->getId();

        $image = "";
        if(isset($data['thumbnail'])){
            $image = $data['thumbnail'];
        }
        $add_price = $data['add_price'];
        if(isset($data['design_name'])){
            $diy_design_name =$this->db->escape($data['design_name']);
        }else{
            $diy_design_name = $this->language->get('text_default_design_name');
        }
        if(isset($this->session->data['editorarg'])){
            $editorarg =$this->db->escape($this->session->data['editorarg']);
        }else{
            $editorarg = json_encode(array());
        }
        $this->db->query("UPDATE " . DB_PREFIX . "project_section SET `customer_id` = '" .
            (int)$customer_id. "', `creation_id` = '".$creation_id."'".", `save_key` = '".$save_key."', `add_price` = '".$add_price."', `status` = 'designing', `name` = '".$diy_design_name. "', `editorarg` = '".$editorarg."', `image` = '".$image."', `picqty` = '".(int)$picqty."', `empty_page_count` = '".(int)$empty_page_count."', `low_resolution_count` = '".(int)$low_resolution_count."', `total_page_count` = '".(int)$total_page_count. "', date_modified = NOW() WHERE section_id='".$section_id. "'");
        return $section_id;
    }




    public function changeSort($sort)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "project_section SET  locked = '" . (int)($sort['locked'])   . "' , date_modified = NOW() WHERE section_id  = '" . (int)$sort['section_id'] . "'");
    }

	public function deleteSection($section_id) {

		return $this->db->query("DELETE FROM " . DB_PREFIX . "project_section WHERE section_id = '" . (int)$section_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

	}

	public function getSection($section_id) {
		$section_query = $this->db->query("SELECT  a.*,b.option,b.product_id FROM " . DB_PREFIX . "project_section a LEFT JOIN " . DB_PREFIX . "school_project b ON b.project_id = a.project_id WHERE a.section_id = '" . (int)$section_id . "' AND a.customer_id = '" . (int)$this->customer->getId() . "'");

        return $section_query->row;

	}






    public function getSections($filter) {
        $sql = "SELECT * FROM  " . DB_PREFIX . "project_section WHERE customer_id = '" . (int)$this->customer->getId() . "'";
	    if($filter['filter_name'])
        {
            $sql.=  " AND name like  '%" . $this->db->escape($filter['filter_name']) . "%'";
        }
        if($filter['filter_project_id'])
        {
            $sql.=  " AND project_id =  '" . $filter['filter_project_id'] . "%'";
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }

	public function getTotalSections() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "project_section WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		return $query->row['total'];
	}


    public function delSectionImage($filter) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "section_image WHERE id = '" . (int)$filter['id'] . "' AND section_id = '" . (int)$filter['section_id'] . "'");
    }


    public function addSectionImage($data) {
        $this->event->trigger('pre.admin.directory.add', $data);

        $this->db->query("INSERT INTO " . DB_PREFIX . "section_image SET section_id = '" . (int)$data['section_id'] . "', `thumb` = '" . $this->db->escape($data['thumb']) . "', `name` = '" . $this->db->escape($data['name']) . "', `filename` = '" . $this->db->escape($data['filename']) . "', size = '" . (int)$data['size']. "', width = '" . (int)$data['width'] . "', height = '" . (int)$data['height']. "', sort_order = '" . (int)$data['sort_order'] . "', customer_id = '" . (int)$this->customer->getId(). "', type = '" . (int)$data['type'] . "', date_modified = NOW(), date_added = NOW()");

        $directory_id = $this->db->getLastId();

        return $directory_id;

    }


    /**
     * @param $data
     * @return bool
     * 更新section 图片
     */
    public function updateSectionImage($data) {

        $section_id = $data['section_id'];
        if($section_id)
        {
            $this->db->query("UPDATE " . DB_PREFIX . "section_image SET  name = '" . $this->db->escape($data['name']). "', `note` = '" . $this->db->escape($data['note']) . "', `tag` = '" . $this->db->escape($data['tag'])    . "' , date_modified = NOW()  WHERE section_id  = '" . (int)$section_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
            return true;
        }
        else
        {
            return false;
        }
    }



    public function getSectionImages($data = array()) {
        $andsql = '';

        if (!empty($data['filter_section_id'])) {
            $andsql .= " AND c1.section_id = '" . (int)$data['filter_section_id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.filename LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_in_use'])) {
            $andsql .= " AND c1.in_use = '" . (int)$data['filter_in_use'] . "'";
        }

        if (!empty($data['order'])) {
            $order  = $data['order'];
        }
        else
        {
            $order = 'c1.type ASC';
        }





        $sql =
            "SELECT 
    c1.name AS name,
    c1.filename,
    c1.section_id,
    c1.thumb,
    c1.size ,
    c1.width ,
    c1.height,
    c1.type AS type ,
    c1.date_added,
    c1.id
    FROM " . DB_PREFIX . "section_image c1 "
            . "  WHERE 1 $andsql";
        $sql .= " ORDER BY ".$order;
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
    public function getTotalSectionImages($data = array()) {
        $andsql = '';

        if (!empty($data['filter_section_id'])) {
            $andsql .= " AND c1.parent_id = '" . (int)$data['filter_directory_id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $andsql .= " AND c1.filename LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sql =
            " SELECT
	COUNT(*) AS total
    FROM " . DB_PREFIX . "section_image c1 "
            . "  WHERE 1 $andsql";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }


    public function lock($data) {

        $section_id = $data['section_id'];
        if($section_id)
        {

            $this->db->query("UPDATE " . DB_PREFIX . "project_section SET  locked = '" . (int)($data['locked'])   . "' , date_modified = NOW() WHERE section_id  = '" . (int)$section_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

            return true;
        }
        else
        {
            return false;
        }
    }

    public function rename($data) {

        $section_id = $data['section_id'];
        if($section_id)
        {
            $this->db->query("UPDATE " . DB_PREFIX . "project_section SET  name = '" . ($data['name'])   . "' , date_modified = NOW()  WHERE section_id  = '" . (int)$section_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
            return true;
        }
        else
        {
            return false;
        }
    }

    public function markReview($data) {

        $section_id = $data['section_id'];
        if($section_id)
        {
            $this->db->query("UPDATE " . DB_PREFIX . "project_section SET  review = '" . (int)($data['review'])   . "' , date_modified = NOW() WHERE section_id  = '" . (int)$section_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
            return true;
        }
        else
        {
            return false;
        }
    }
}