<?php 
/**
 * @author tshirtecommerce - https://tshirtecommerce.com
 * @date: 05 Sep 2016
 * 
 * API 			4.1.3
 * 
 * @copyright  Copyright (C) 2015 https://tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

class ControllerTshirtecommerceProducts extends Controller
{
	
	public function get_products_linked()
	{
		$arr = array();

		$product_array = $this->db->query("SELECT `design_product_id` FROM `" . DB_PREFIX . "product` WHERE `design_product_id` IS NOT NULL AND `design_product_id` <> ''")->rows;

		if($product_array !=false && count($product_array) > 0)
		{
			foreach ($product_array as $value) {
				$temp = explode(':', $value['design_product_id']);

				$arr[] = (count($temp) > 1) ? (isset($temp[2]) ? $temp[2] : 0) : $value['design_product_id'];
			}
		}
		$arr = array_unique($arr);

		echo json_encode($arr);

		return json_encode($arr);
	}

	public function get_product_changed()
	{
		$json = array();
		if(isset($this->request->post['id']))
		{
			$design_product_id = $this->request->post['id'];
			$query = $this->db->query("SELECT `product_id` AS parent_id,`quantity` AS max_order,`price`,`minimum` AS min_order ,`design_product_id` AS design_id FROM `" . DB_PREFIX . "product` WHERE `design_product_id`='".$design_product_id."'");
			$json = $query->row;
		}
		
		echo json_encode($json);
		return;
	}
}