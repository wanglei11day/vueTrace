<?php
/**
 * @author tshirtecommerce - https://tshirtecommerce.com
 * @date: June, 16, 2016
 * 
 * API 			4.1.3
 * 
 * @copyright  Copyright (C) 2015 https://tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

class ModelTshirtecommerceOrder extends Model
{
	public function getOptions($order_id, $product_id = 0)
	{
		$query = $this->db->query("SELECT pt.order_product_id,pt.options,op.product_id,op.quantity FROM `" . DB_PREFIX 
			. "tshirtdesign_order` pt INNER JOIN (SELECT order_product_id,product_id,quantity FROM `" . DB_PREFIX 
			. "order_product` WHERE order_id = '" . (int)$order_id . "' AND product_id = '" 
			. (int)$product_id . "') op ON pt.order_product_id = op.order_product_id ORDER BY pt.order_product_id");

		if($query->num_rows) return $query->rows;

	 	return false;
	}

	public function getCarts($cart_id)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cart` WHERE cart_id='" . (int)$cart_id . "'");

		if($query->num_rows) return $query->rows;

		return false;
	}

	public function getPrices($order_id)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE order_id='" . (int)$order_id . "' ORDER BY order_product_id");

		if($query->num_rows) return $query->rows;

		return false;
	}

	public function getItemOrder($order_id, $product_id = 0)
	{
	 	$prices = array();
	 	$query 	= $this->db->query("SELECT op.product_id,op.quantity, op.price, op.total FROM `" . DB_PREFIX 
	 		. "order_product` op WHERE product_id='" . (int)$product_id . "' AND order_id='" . (int)$order_id . "'");

	 	if($query->num_rows) return $query->rows;

	 	return false;
	}

	public function getItem($order_product_id)
	{		
		$item = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tshirtdesign_order` WHERE order_product_id = '" . (int)$order_product_id . "'");

		if ($item->num_rows) 
		{
			$string = $item->row['options'];

			if ($string == '') return false;
			else return json_decode($string);
		}
		else
		{
			return false;
		}
	}
	
	// get product
	public function getProduct($product_id)
	{
		$item = $this->db->query("SELECT product_id,t_max_size,t_max_file_size, design_product_id,tshirt_category,tshirt_art_cate_id FROM `" . DB_PREFIX . "product` WHERE status = 1 AND product_id = '".$product_id."'");

		if ($item->num_rows)
		{			
			$data = array(
				'product_id' 		=> $item->row['product_id'],
				'design_product_id' => $item->row['design_product_id'],
                't_max_size' => $item->row['t_max_size'],
                't_max_file_size' => $item->row['t_max_file_size'],
                'tshirt_category' => $item->row['tshirt_category'],
                'tshirt_art_cate_id'=>$item->row['tshirt_art_cate_id'],
			);
			return $data;
		}

		return false;
	}
	
	// get all product design
	public function getProducts()
	{
		$items = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE status = 1 AND design_product_id != ''");

		$products = 0;
		if ($items->num_rows)
		{
			$products = array();
			foreach($items->rows as $item)
			{				
				$design_product_id 	= $item['design_product_id'];
				$n 					= explode(':', $design_product_id);

				if ( count($n) == 1) $products[] = $item;
			}
		}
		
		return $products;
	}
}