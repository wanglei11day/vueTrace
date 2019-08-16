<?php 
/**
* 
*/
class ControllerTshirtecommerceStore extends Controller
{	
	public function store_ajax_key()
	{
		$data = array(
			'error'	=> 0,
			'msg' => '',
			'reload' => 0
		);
		if( empty($this->request->get['api_key']) || empty($this->request->get['arts']) || empty($this->request->get['order_id']) )
		{
			$data['error'] = 1;
			$data['msg'] = 'Data design not found!';
		}
		else
		{
			$ids = str_replace(':', '-', $this->request->get['arts']);
			$url = 'http://api.9file.net/api/order/ids/'.$ids.'/order_number/'.$this->request->get['order_id'].'/api_key/'.$this->request->get['api_key'];
			$result = $this->openURL($url);
			if($result != false)
			{
				$arts 	= json_decode($result, true);
				if(isset($arts['error']))
				{
					$data['error'] = 1;
					$data['msg'] = 'Data design not found!';
				}
				else
				{
					$params	= '';
					$art_prices	= array();
					foreach($arts as $id => $art)
					{
						if($art['price'] > 0)
						{
							$art_prices[$id] = $art['price'];
							$art['key']	= 0;
						}
						if($params == '')
						{
							$params	= $id.':'.$art['key'];
						}
						else
						{
							$params	.= ';'.$id.':'.$art['key'];
						}
					}
					//print_r($art_prices);exit;
					if(count($art_prices) > 0)
					{
						$data['error'] = 1;
						$data['reload']	= 1;
					}
					$this->store_art_update($_GET['order_id'], $params, $art_prices, $_GET['api_key']);
				}
			}
			else
			{
				$data['error'] = 1;
				$data['msg'] = 'Data design not found!';
			}		
		}
		//print_r($data);exit;
		echo json_encode($data);
		return;
	}

	protected function store_art_update($design_id, $params, $art_prices = array(), $api = '')
	{
		if(empty($api)) return;

		$array = explode(';', $params);
		
		$arts = array();
		for($i = 0; $i < count($array); $i++)
		{
			$art = explode(':', $array[$i]);
			if(count($art) > 1)
			{
				$arts[$art[0]] = $art[1];
			}
		}
		if (count($arts))
		{
			if (defined('DS') == false) define('DS', DIRECTORY_SEPARATOR);
			if (defined('ROOT') == false) define('ROOT', dirname(DIR_SYSTEM).DS.'tshirtecommerce');
			
			include_once (ROOT .DS. 'includes' .DS. 'functions.php');
			$dg = new dg();
			
			// update sales
			$file = ROOT .DS. 'data' .DS. 'store' .DS. 'arts_info.json';
			if(file_exists($file))
			{
				// call cache
				$cache = $dg->cache('store');
				$sales = $cache->get('sales');
				if($sales == null)
				{
					$sales = array();
				}
				$time = time();
				$month = date('Y_m', $time);
				$day = date('d', $time);
				
				if(empty($sales[$month]))
				{
					$sales[$month] = array();
				}
				
				if(empty($sales[$month][$day]))
				{
					$sales[$month][$day] = array();
				}
			
				$rows = json_decode(@file_get_contents($file), true );
				foreach($arts as $clipar_id => $value)
				{
					if(isset($art_prices[$clipar_id]))
					{
						continue;
					}
					
					if(isset($rows[$clipar_id]))
					{
						if(isset($rows[$clipar_id]['sales']))
						{
							$rows[$clipar_id]['sales'] = $rows[$clipar_id]['sales'] + 1;
						}
						else
						{
							$rows[$clipar_id]['sales'] = 1;
						}
						
						if( isset($sales[$month][$day][$clipar_id]) )
						{
							$sales[$month][$day][$clipar_id] = $sales[$month][$day][$clipar_id] + 1;
						}
						else
						{
							$sales[$month][$day][$clipar_id] = 1;
						}
					}
				}
				$dg->WriteFile($file, json_encode($rows));
				$cache->set('sales', $sales, 933120000);
			}
			
			$cache = $dg->cache('cart');
			$design = $cache->get($design_id);
			
			if(isset($design['vector']))
			{
				$vectors = json_decode($design['vector'], true);
				if(count($vectors))
				{
					foreach($vectors as $view => $items)
					{
						if (count($items))
						{
							foreach($items as $id => $item)
							{
								if (isset($item['clipar_type']) && empty($item['clipar_paid']))
								{
									if( isset($art_prices[$item['clipart_id']]) )
									{
										$items[$id]['price'] = $art_prices[$item['clipart_id']];
										continue;
									}
									if( isset( $arts[ $item['clipart_id'] ] ) )
									{									
										$items[$id]['clipar_paid'] = 1;
										if((isset($item['file']) && is_string($item['file']) && $item['file'] == 'svg') || (isset($item['file']['type']) && $item['file']['type'] == 'svg'))
										{
											$svg = $this->store_str_svg($item['svg'], $arts[ $item['clipart_id'] ]);
										}
										else
										{
											$key_active = str_replace(' ', '+', $arts[ $item['clipart_id'] ]);
											$svg = $item['svg'];
											$key = md5( $key_active );
											$url = 'http://api.9file.net/api/orderPNG/id/'.$item['clipart_id'].'/key/'.$key.'/api_key/'.$api;
											$result = openURL($url);
											if($result != false)
											{
												$data = json_decode($result, true);
												if(isset($data['content']))
												{										
													$png = $this->encrypt_compress($key_active, base64_decode($data['content']));
													$img = 'data:image/png;base64,' . base64_encode($png);
										
													$temp1 = explode('xlink:href="', $item['svg']);
													if(count($temp1) > 1)
													{
														$temp2 = explode('">', $temp1[1]);
														if(count($temp2) > 1)
														{
															$svg = $temp1[0] .'xlink:href="'. $img .'">'. $temp2[1];
														}
													}
												}
											}
										}
										$items[$id]['svg'] = $svg;
									}
								}
							}
							$vectors[$view]	= $items;
						}
					}
				}
				$design['vector'] = json_encode($vectors);
				$cache->set($design_id, $design);
			}
		}
	}

	protected function store_str_svg($svg, $key)
	{
		$key = str_replace(' ', '+', $key);
		if ($svg == '')
		{
			return '';
		}
		$params = explode('/', $svg);
		$n = count($params);
		
		$str = '';
		for($i=0; $i<$n; $i++)
		{
			$number = $params[$i];
			$s = substr($key, $number, 1);
			$str .= $s;
		}
		
		$output = base64_decode($str);
		return $output;
	}

	protected function encrypt_compress($key, $str)
	{
		$s = array();
		for ($i = 0; $i < 256; $i++)
		{
			$s[$i] = $i;
		}
		$j = 0;
		for ($i = 0; $i < 256; $i++)
		{
			$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
		}
		$i = 0;
		$j = 0;
		$res = '';
		$count = strlen($str);
		for ($y = 0; $y < $count; $y++)
		{
			$i = ($i + 1) % 256;
			$j = ($j + $s[$i]) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
			$res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
		}
		return $res;
	}

	protected function openURL($url)
	{
		$data = false;
		if( function_exists('curl_exec') )
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			curl_close($ch);
		}
		
		if( $data == false && function_exists('file_get_contents') )
		{
			$data = file_get_contents($url);
		}
		
		return $data;
	}
}