<?php
/**
 * @author 		tshirtecommerce - https://tshirtecommerce.com
 * @date: 		June, 16, 2016
 * 
 * API 			4.1.3
 * 
 * @copyright  	Copyright (C) 2015 https://tshirtecommerce.com. All rights reserved.
 * @license    	GNU General Public License version 2 or later; see LICENSE
 *
 */
 
class ControllerTshirtecommerceDesignerApi extends Controller
{
	public function login()
	{
		$data 		= array('error' => 0, 'id' => 0);
		$is_login 	= 0;
		$id 		= 0;
		$email 		= '';
		$password 	= '';
		
		$this->load->model('account/customer');
		
		if(isset($this->request->post['username'])) $email 	= $this->request->post['username'];
		if(isset($this->request->post['password'])) $password = $this->request->post['password'];
	
		if ($this->customer->login($email, $password))
		{
			$is_login = 1;
		}
		
		if ($this->customer->isLogged())
		{
			$logged 							= array('login' => TRUE, 'email' => $email, 'id' => $this->customer->isLogged());
			$_SESSION['default']['is_logged'] 				= $logged;
			$this->session->data['is_logged'] 	= $logged;

			$is_login 	= 1;
			$id 		= md5($this->customer->isLogged());
		}
		
		$data['error'] 	= $is_login;
		$data['id'] 	= $id;
		
		echo json_encode($data);
		return;
	}
}
