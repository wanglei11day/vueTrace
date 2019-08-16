<?php
class ControllerToolTool extends Controller {
	
	public function index() {
		$this->load->model('catalog/product');
		$filter_data = array(
		);
		$this->load->model('tool/image');
		$results = $this->model_catalog_product->getAllProducts($filter_data);
		
		foreach($results as $result){
			$this->load->model('catalog/mvd_product');
			
			$product_info = $this->model_catalog_mvd_product->getProduct($result['product_id']);
			$vendor_id= $product_info['vendor'];
			if(isset($vendor_id)){
					$this->load->model('catalog/vendorlogo');
					$vendor_info = $this->model_catalog_vendorlogo->getVendor($vendor_id);
					if(!empty($vendor_info)&&isset($vendor_info['type'])){
						print_r("set procut_id :".$result['product_id'].":".$vendor_info['type']."<br>");
						$this->model_catalog_product->updateTypeByProductId($result['product_id'],$vendor_info['type']);
					}else{
						print_r("<br>product_id:".$result['product_id']."     vendor_id:".$vendor_id." is missing data<br>");
					}
			}
			
		}
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}
	
	public function jisuan(){
		$numbers = array(1,3,5,7,9,11,13,15);
		foreach ($numbers as $item1) {
			foreach ($numbers as $item2) {
				foreach ($numbers as $item3) {
					$total = $item1+$item2+$item3;
					if($total==30){
						echo $item1."+".$item2."+".$item3.'----------------------------------------<BR>';

					}else{
						echo $item1."+".$item2."+".$item3.'='.$total.'<BR>';
					}
				}
			}
		}
	}


	public function tool_serialize()
    {
        $data['column_left'] = $this->load->controller('common/column_leftv2');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        if (isset($this->request->post['str'])) {
            $str = $this->request->post['str'];
            $str = htmlspecialchars_decode($str);
        } else {
            $str = '';
        }

        if (isset($this->request->post['type'])) {
            $tag = $this->request->post['type'];
        } else {
            $tag = '0';
        }
        if($tag)
        {
           // $str = '{"a":"1"}';


            $xarr = json_decode($str,true);
            //exit;
            if($xarr)
            {
                $str = serialize($xarr);
            }
            else
            {
                $str = 'json 格式错误';
            }
        }
        else
        {
            //$str = 'a:1:{s:1:"a";s:1:"1";}';
            $str = unserialize($str);
            $str = json_encode($str);
        }
        $data['str'] = $str;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/tool/serialize.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/tool/serialize.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/tool/serialize.tpl', $data));
        }
    }
	public function test2(){
		print_r("...");
		print_r($this->config->get('config_complete_status'));
	}


	public function export_product_ids(){
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE 1");  
		$pids = $query->rows;
		$str = '';
		foreach ($pids as $item) {
			$str .= ';';
			$str .= $item['product_id'];
		}
		print_r($str);
	}


	public function compare_ids(){
		$id1 = "53;54;55;56;58;59;60;61;63;64;65;66;67;89;96;102;103;104;105;106;107;108;109;110;111;113;115;116;117;118;119;123;127;128;129;130;132;133;134;135;136;183;184;185;186;187;188;189;190;191;194;195;196;197;198;199;200;201;202;203;204;209;210;211;212;213;215;218;219;220;221;222;233;237;239;251;252;253;254;255;256;257;258;263;269;270;273;276;277;278;285;287;288;293;294;295;300;301;304;305;306;307;309;311;316;317;322;323;375;376;377;378;379;380;381;404;405;406;407;408;409;410;412;413;414;415;416;417;418;419;420;423;424;425;426;428;429;430;431;432;433;434;435;438;439;442;443;444;445;446;447;452;456;457;458;459;460;462;469;470;472;475;480;481;483;484;486;487;488;490;491;492;493;494;495;496;498;499;500;501;502;503;504;505;506;507;508;509;510;511;512;513;514;515;516;517;518;519;520;521;522;523;524;525;526;527;528;529;530;531;532;533;534;535;536;537;539;540;541;542;543;544;545;546;547;554;555;556;557;558;559;561;562;563;566;567;568;569;571;572;573;574;575;576;577;578;579;580;581;582;583;584;585;586;587;588;589;590;591;592;593;595;597;598;599;602;604;605;606;608;609;610;611;612;613;614;615;616;617;618;619;620;621;622;623;624;625;626;627;628;629;630;632;633;634;635;636;637;638;639;640;641;642;643;644;645;648;649;650;654;655;658;659;660;661;663;665;666;667;668;669;670;671;672;673;674;675;676;677;680;681;682";

		$id2 = "31;53;54;55;56;58;59;60;61;63;64;65;66;67;77;89;96;102;103;104;105;106;107;108;109;110;111;113;115;116;117;118;119;123;127;128;129;130;132;133;134;135;136;183;184;185;186;187;188;189;190;191;194;195;196;197;198;199;200;201;202;203;204;209;210;211;212;213;214;215;218;219;220;221;222;233;237;239;251;252;253;254;255;256;257;258;263;269;270;273;276;277;278;285;287;288;293;294;295;300;301;304;305;306;307;308;309;310;311;312;313;314;315;316;317;318;319;320;321;322;323;353;354;355;356;357;358;359;360;361;362;363;364;365;366;367;368;369;370;371;372;373;374;375;376;377;378;379;380;381;382;383;384;385;386;387;388;389;390;391;392;393;394;395;396;397;398;399;400;401;402;403;404;405;406;407;408;409;410;412;413;414;415;416;417;418;419;420;423;424;425;426;428;429;430;431;432;433;434;435;438;439;442;443;444;445;446;447;452;456;457;458;459;460;462;463;469;470;472;475;477;478;479;480;481;482;483;484;485;486;487;488;490;491;492;493;494;495;496;497;498;499;500;502;504;505;506;507;508;509;510";

		$id1_arr = explode(";", $id1);
		$id2_arr = explode(";", $id2);
		$miss = array();
		foreach ($id2_arr as $id2) {
			$ismiss = true;
			foreach ($id1_arr as $id1) {
				if($id2==$id1){
					$ismiss = false;
					break;
				}
			}
			if($ismiss){
				$miss[] = $id2;
			}
		}
		$re = implode(';', $miss);
		print_r($re);
	}



	public function option_tool(){
		$this->load->model('account/diy_design');
		 $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_design WHERE 1");  
		 $designs = $query->rows;
		 foreach ($designs as $design) {
		 	$options =  $this->model_account_diy_design->getOldDesignOptions($design['customer_creation_id']);

		 	if(!empty($options)){
		 		$this->db->query("UPDATE " . DB_PREFIX . "customer_design SET `option` = '" .$this->db->escape(json_encode($options))."'  WHERE customer_creation_id='".$design['customer_creation_id']. "'");
		 	}
		 }
		 print_r("done!!!!!!!");
		
	}
	public function quchong(){
		
		/**
 * 遍历所有目录
 
 */
		$wordlist = array();
		$dir = DIR_APPLICATION.'language/chinese/';
		$it = new RecursiveDirectoryIterator ( $dir );
		foreach ( new RecursiveIteratorIterator ( $it ) as $file ) {
			if (strpos ( $file, "svn" )) {
				continue;
			}
			$this->getChineseWord ( $file,$wordlist);
		}
		$this->selectzhfromdb($wordlist);
		$fp=fopen('source.txt','w');
		fwrite($fp,implode('',$wordlist));

		fclose($fp);
		print_r($wordlist);
	}
	
	
	public function selectzhfromdb(&$wordlist){
		$tables = $this->db->query("select table_name from information_schema.tables where table_schema='cooto' and table_type='base table'")->rows;
		foreach($tables as $table){
			$table_name = $table['table_name'];
			$data = $this->db->query("SELECT * FROM $table_name")->rows;
			if(!empty($data)){
				foreach($data as $item){
					foreach ($item as $key=>$value){
						$this->getChineseWordFromStr($value,$wordlist);
					}
				}
			}
		}
	
		
	}


	public function selectdomainfromdb(){
		
		$tables = $this->db->query("select table_name from information_schema.tables where table_schema='silk_shop' and table_type='base table'")->rows;

		foreach($tables as $table){
			$table_name = $table['table_name'];
			$data = $this->db->query("SELECT * FROM $table_name")->rows;
			if(!empty($data)){
				foreach($data as $item){
					foreach ($item as $key=>$value){
						$this->getdomainWordFromStr($value,$table_name,$key,$item);
					}
				}
			}
		}
	
		
	}
	
	public function getdomainWordFromStr(&$content,$table,$key,&$item) {
			$replace = 'http:\/\/test1.coo-too.com';
			if(!empty($content)){
				if(strpos($content, $replace) !== false){
					

					print_r($table.' '. $key.' Exist<BR>');

					//print_r($content);
					$content = str_replace($replace,'',$content);
					if($table == 'oc_category_description'){
						$this->db->query("UPDATE  " . DB_PREFIX . "category_description set $key = '".$content."' WHERE category_id = '".$item['category_id']."' AND language_id = '".$item['language_id']."'");
					}
					

					if($table == 'oc_gallimage_image'){
						$this->db->query("UPDATE  " . DB_PREFIX . "gallimage_image set $key = '".$content."' WHERE gallimage_image_id = '".$item['gallimage_image_id']."'");
					}
					if($table == 'oc_module'){
						print_r("module_id:".$item['module_id']."<BR>");
						//$this->db->query("UPDATE  " . DB_PREFIX . "module set $key = '".$content."' WHERE module_id = '".$item['module_id']."'");
					}
				}
			}
			
			


	}

/**
*遍历获取文件夹下所有文件中的中文字符
*@author:firmy
*/
 
	/**
	 * 获取文件中的中文字符
	 * 
	 * @param unknown_type $file            
	 */
	public function getChineseWord($file,&$wordList) {
		
		if($file->isFile()){
			$x = file_get_contents ( $file );
			print_r( $file->getFileName()."<BR>");
			if (preg_match_all ( "/([\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}]*)/u", $x, $match )) {
				foreach ( $match [0] as $k => $v ) {
					if (! empty ( $v )) {
						$arr = str_split($v,3);
						
						foreach($arr as $item){
							$wordList [$item] = $item; // 去重
						}
					}
				}
			}
		}
	}
	

	


	public function getChineseWordFromStr($content,&$wordList) {

			if (preg_match_all ( "/([\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}]*)/u", $content, $match )) {
				foreach ( $match [0] as $k => $v ) {
					if (! empty ( $v )) {
						$arr = str_split($v,3);
						
						foreach($arr as $item){
							$wordList [$item] = $item; // 去重
						}
					}
				}
			}
	}


	public function copy_images(){
	
		$tables = $this->db->query("select table_name from information_schema.tables where table_schema='glive' and table_type='base table'")->rows;
		$imagebakdir = DIR_IMAGE.'bak/';
		$this->createdir($imagebakdir);
		foreach($tables as $table){
			$table_name = $table['table_name'];
			$data = $this->db->query("SELECT * FROM $table_name")->rows;
			print_r("===============$table_name==========<br>");
			if($table_name=="oc_modification"||$table_name=="oc_module"||$table_name=="oc_btslider_slide")
				continue;
			if(!empty($data)){
				foreach($data as $item){
					foreach ($item as $key=>$image){
						
						if (strpos($image, 'catalog/') !== false&&strpos($key, 'ion') === false) {
							$image_path = DIR_IMAGE.$image;
							//preg_match_all('/(C:\/wamp).*(jpg|png)/i',$image_path,$arraymatch);
							print_r($key.":".$image_path);
							$image_bak_path =$imagebakdir.$image;
							print_r("<br>");
							
							$image_bak_dir = dirname($image_bak_path);
							$this->createdir($image_bak_dir);
							copy($image_path,$image_bak_path);
						}
						
						
					}
				}
			}
			
		
		}
	
		
	}
	
	
	private function createdir($path){
		if (is_dir($path)){  
			return true;
		}else{
			//第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
			$res=mkdir(iconv("UTF-8", "GBK", $path),0777,true); 
			if ($res){
				return true;
			}else{
				return false;
			}
		}
	}
	public function add_market_aftersale() {
		$this->load->model('catalog/product');
		$sample_vendor_id = 56;
		
		$sampledata = $this->getVendorDescriptions($sample_vendor_id);
	
		$results = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE type = 'market'")->rows;
		foreach($results as $result){
			
			
				$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_description WHERE vendor_id = '" . (int)$result['vendor_id'] . "'");

				foreach ($sampledata as $language_id => $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_description SET vendor_id = '" . (int)$result['vendor_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', aftersale = '" . $this->db->escape($value['aftersale']). "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
				}
			
		}
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}
	
	public function add_live_aftersale() {
		$this->load->model('catalog/product');
		$sample_vendor_id = 81;
		$sampledata = $this->getVendorDescriptions($sample_vendor_id);
		$results = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE type = 'live'")->rows;
		foreach($results as $result){
			
			
				$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_description WHERE vendor_id = '" . (int)$result['vendor_id'] . "'");

				foreach ($sampledata as $language_id => $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_description SET vendor_id = '" . (int)$result['vendor_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', aftersale = '" . $this->db->escape($value['aftersale']). "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
				}
			
		}
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}
	
	
	public function add_sale_aftersale() {
		$this->load->model('catalog/product');
		$sample_vendor_id = 53;
		$sampledata = $this->getVendorDescriptions($sample_vendor_id);
		
		$results = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE type = 'sale'")->rows;
		foreach($results as $result){
			
				
				$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_description WHERE vendor_id = '" . (int)$result['vendor_id'] . "'");

				foreach ($sampledata as $language_id => $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_description SET vendor_id = '" . (int)$result['vendor_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', aftersale = '" . $this->db->escape($value['aftersale']). "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
				}
			
		}
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}
	
	public function getVendorDescriptions($vendor_id) {
		$vendor_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor_description WHERE vendor_id = '" . (int)$vendor_id . "'");

		foreach ($query->rows as $result) {
			
			$vendor_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'aftersale'     => $result['aftersale'],
				'tag'              => $result['tag']
			);
		}

		return $vendor_description_data;
	}
	
	
	public function add_live_shipping_sample() {
		$this->load->model('catalog/product');
		$sample_vendor_id = 74;
		$sampledata = $this->getVendorDescriptions($sample_vendor_id);
		
		$sample_geo_zones = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone WHERE vendor_id = '".$sample_vendor_id."'")->rows;
		
		$vendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE type = 'live'")->rows;
		foreach($vendors as $vendor){
			$vendor_id  = $vendor['vendor_id'];
			if($vendor_id==$sample_vendor_id)
				continue;
			foreach($sample_geo_zones as $sample_geo_zone){
				
				//涓哄晢瀹舵彃鍏ユ牱渚嬪垎缁勫悕瀛�
				$this->db->query("INSERT INTO " . DB_PREFIX . "geo_zone SET vendor_id = '" . (int)$vendor_id   . "', name = '" . $sample_geo_zone['name']. "', description = '" . $this->db->escape(	$sample_geo_zone['description']) . "', date_added = NOW(), date_modified = NOW()");
				$new_geo_zone_id = $this->db->getLastId();
				
				
				//鎻掑叆蹇�掕垂鐢�
				$sample_courier_shipping = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier_shipping WHERE vendor_id = '".$sample_vendor_id."' AND geo_zone_id = '".$sample_geo_zone['geo_zone_id']."'")->row;
				

				
				$this->db->query("INSERT INTO " . DB_PREFIX . "courier_shipping SET vendor_id = '" . (int)$vendor_id . "', geo_zone_id = '" . $new_geo_zone_id. "', priority = '" . $sample_courier_shipping['priority']. "', free_shipping_fee = '" . $sample_courier_shipping['free_shipping_fee']. "', shipping_rate = '" . $this->db->escape(	$sample_courier_shipping['shipping_rate']) . "', courier_id = '" . $this->db->escape(	$sample_courier_shipping['courier_id']). "'");
				
				
				//閫夋嫨鏍蜂緥鍒嗙粍
				$zone_to_geo_zones = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '".$sample_geo_zone['geo_zone_id']."'")->rows;
				foreach($zone_to_geo_zones as $zone_to_geo_zone){
					$this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '" . (int)$zone_to_geo_zone['country_id'] . "', geo_zone_id = '" . $new_geo_zone_id. "', zone_id = '" . $zone_to_geo_zone['zone_id']. "', area_id = '" . $this->db->escape(	$zone_to_geo_zone['area_id']) . "', date_added = NOW(), date_modified = NOW()");
				}
			}
			
		}
		print_r("job done!!!!!!!!");
	}
		
		
	public function add_sale_shipping_sample() {
		$this->load->model('catalog/product');
		$sample_vendor_id = 37;
		$sampledata = $this->getVendorDescriptions($sample_vendor_id);
		
		$sample_geo_zones = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone WHERE vendor_id = '".$sample_vendor_id."'")->rows;
		
		$vendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE type = 'sale'")->rows;
		foreach($vendors as $vendor){
			$vendor_id  = $vendor['vendor_id'];
			if($vendor_id==$sample_vendor_id)
				continue;
			foreach($sample_geo_zones as $sample_geo_zone){
				
				//涓哄晢瀹舵彃鍏ユ牱渚嬪垎缁勫悕瀛�
				$this->db->query("INSERT INTO " . DB_PREFIX . "geo_zone SET vendor_id = '" . (int)$vendor_id   . "', name = '" . $sample_geo_zone['name']. "', description = '" . $this->db->escape(	$sample_geo_zone['description']) . "', date_added = NOW(), date_modified = NOW()");
				$new_geo_zone_id = $this->db->getLastId();
				
				
				//鎻掑叆蹇�掕垂鐢�
				$sample_courier_shipping = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier_shipping WHERE vendor_id = '".$sample_vendor_id."' AND geo_zone_id = '".$sample_geo_zone['geo_zone_id']."'")->row;
				
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "courier_shipping SET vendor_id = '" . (int)$vendor_id . "', geo_zone_id = '" . $new_geo_zone_id. "', priority = '" . $sample_courier_shipping['priority']. "', free_shipping_fee = '" . $sample_courier_shipping['free_shipping_fee']. "', shipping_rate = '" . $this->db->escape(	$sample_courier_shipping['shipping_rate']) . "', courier_id = '" . $this->db->escape(	$sample_courier_shipping['courier_id']). "'");
				
				
				//閫夋嫨鏍蜂緥鍒嗙粍
				$zone_to_geo_zones = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '".$sample_geo_zone['geo_zone_id']."'")->rows;
				foreach($zone_to_geo_zones as $zone_to_geo_zone){
					$this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '" . (int)$zone_to_geo_zone['country_id'] . "', geo_zone_id = '" . $new_geo_zone_id. "', zone_id = '" . $zone_to_geo_zone['zone_id']. "', area_id = '" . $this->db->escape(	$zone_to_geo_zone['area_id']) . "', date_added = NOW(), date_modified = NOW()");
				}
			}
			
		}
		print_r("job done!!!!!!!!");
	}
		
		
	public function add_market_shipping_sample() {
		$this->load->model('catalog/product');
		$sample_vendor_id = 57;
		$sampledata = $this->getVendorDescriptions($sample_vendor_id);
		
		$sample_geo_zones = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone WHERE vendor_id = '".$sample_vendor_id."'")->rows;
		
		$vendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE type = 'market'")->rows;
		foreach($vendors as $vendor){
			$vendor_id  = $vendor['vendor_id'];
			if($vendor_id==$sample_vendor_id)
				continue;
			foreach($sample_geo_zones as $sample_geo_zone){
				
				//涓哄晢瀹舵彃鍏ユ牱渚嬪垎缁勫悕瀛�
				$this->db->query("INSERT INTO " . DB_PREFIX . "geo_zone SET vendor_id = '" . (int)$vendor_id   . "', name = '" . $sample_geo_zone['name']. "', description = '" . $this->db->escape(	$sample_geo_zone['description']) . "', date_added = NOW(), date_modified = NOW()");
				$new_geo_zone_id = $this->db->getLastId();
				
				
				//鎻掑叆蹇�掕垂鐢�
				$sample_courier_shipping = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier_shipping WHERE vendor_id = '".$sample_vendor_id."' AND geo_zone_id = '".$sample_geo_zone['geo_zone_id']."'")->row;
				
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "courier_shipping SET vendor_id = '" . (int)$vendor_id . "', geo_zone_id = '" . $new_geo_zone_id. "', priority = '" . $sample_courier_shipping['priority']. "', free_shipping_fee = '" . $sample_courier_shipping['free_shipping_fee']. "', shipping_rate = '" . $this->db->escape(	$sample_courier_shipping['shipping_rate']) . "', courier_id = '" . $this->db->escape(	$sample_courier_shipping['courier_id']). "'");
				
				
				//閫夋嫨鏍蜂緥鍒嗙粍
				$zone_to_geo_zones = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '".$sample_geo_zone['geo_zone_id']."'")->rows;
				foreach($zone_to_geo_zones as $zone_to_geo_zone){
					$this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '" . (int)$zone_to_geo_zone['country_id'] . "', geo_zone_id = '" . $new_geo_zone_id. "', zone_id = '" . $zone_to_geo_zone['zone_id']. "', area_id = '" . $this->db->escape(	$zone_to_geo_zone['area_id']) . "', date_added = NOW(), date_modified = NOW()");
				}
			}
			
		}
		
		
		
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}
	
	
	public function translate(){
		$ch = curl_init();
		$text = '浣犲ソ';
		$this->load->model('catalog/product');
		$filter_data = array(
		);
		$this->load->model('tool/image');
		$results = $this->model_catalog_product->getAllProducts($filter_data);
		
		foreach($results as $result){
			
		}
		$url = 'http://apis.baidu.com/apistore/tranlateservice/translate?query='.$text.'&from=zh&to=en';
		$header = array(
			'apikey: 2bff861fcf00b07b71366c96b3b6b036',
		);
		// 娣诲姞apikey鍒癶eader
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 鎵цHTTP璇锋眰
		curl_setopt($ch , CURLOPT_URL , $url);
		$res = curl_exec($ch);
		print_r($res);
		var_dump(json_decode($res));

	}		
	
	
	
	public function sale_off() {
		$this->load->model('catalog/product');
		$sample_vendor_id = 56;
		
		$sampledata = $this->getVendorDescriptions($sample_vendor_id);
	
		$results  = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE meta_title  LIKE '%off%'")->rows;
		print_r(count($results));
		foreach($results as $result){
				//print_r("UPDATE " . DB_PREFIX . "category_description SET meta_title='".$result['meta_title']."' WHERE category_id = '".$result['category_id']."' AND language_id = '1'<br>");
				$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_title='".$result['meta_title']."' WHERE category_id = '".$result['category_id']."' AND language_id = '1'");
		}
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}
	
	
	public function get_sale_vendor() {
		$type = $this->request->get['type'];
		$vendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE  type = '$type'")->rows;
		
		foreach($vendors as $vendor){
			$vendor_id = $vendor['vendor_id'];
			print_r("<br>SELECT * FROM " . DB_PREFIX . "courier_shipping WHERE vendor_id = $vendor_id<br>");
			$shippings = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier_shipping WHERE vendor_id = $vendor_id")->rows;
			print_r($shippings);
		}
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}
	
	
	public function update_courior() {
		$type = $this->request->get['type'];
		$vendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE  type = '$type'")->rows;
		
		foreach($vendors as $vendor){
			$vendor_id = $vendor['vendor_id'];
			print_r("<br>'UPDATE ' . DB_PREFIX . 'courier_shipping set courier_id = '11' WHERE vendor_id = '$vendor_id'<br>");
			$this->db->query("UPDATE " . DB_PREFIX . "courier_shipping set courier_id = '11' WHERE vendor_id = $vendor_id");
			//$shippings = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier_shipping WHERE vendor_id = $vendor_id")->rows;
			//print_r($shippings);
		}
		print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	}
	
	
	public function multi_vendor_name(){
			$vendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor")->rows;
			foreach($vendors as $vendor){
				$vendor_name = $vendor['vendor_name'];
				print_r("UPDATE  " . DB_PREFIX . "vendor_description set vendor_name = '".$vendor_name."' WHERE vendor_id = '".$vendor['vendor_id']."'<BR>");
				$this->db->query("UPDATE  " . DB_PREFIX . "vendor_description set vendor_name = '".$this->db->escape($vendor_name)."' WHERE vendor_id = '".$vendor['vendor_id']."'");
			}
	}
	
	
	public function delete_category(){
			$categories = $this->db->query("SELECT * FROM " . DB_PREFIX . "category")->rows;
			foreach($categories as $category){
				$this->delete_no_parent_category($category['category_id']);
			}
	}
	public function delete_no_parent_category($category_id) {
		$parent = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '".$category['parent_id'])->row;
		
		if(empty($parent)){
			print_r("DELETE  FROM " . DB_PREFIX . "category WHERE category_id = '".$category['category_id']."<br>");
			//$this->db->query("DELETE  FROM " . DB_PREFIX . "category WHERE category_id = '".$category['category_id'])
		}else{
			if($parent['category_id']==0){
				return;
			}
			$this->delete_no_parent_category($category_id);
		}
		
		
			
			
			//$shippings = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier_shipping WHERE vendor_id = $vendor_id")->rows;
			//print_r($shippings);
	}
		//print_r("job done!!!!!!!!");
		//print_r(count($results));
		//print_r($results);
	public function delete_repeat_vendor_images(){
			$images = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor_image")->rows;
			foreach($images as $image){
				//print_r("SELECT * FROM " . DB_PREFIX . "vendor_image WHERE image ='".$image['image']."' AND vendor_id='".$image['vendor_id']."'<br>");
				$allimages =  $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor_image WHERE image ='".$image['image']."' AND vendor_id='".$image['vendor_id']."'")->rows;
				if(!empty($allimages)&&count($allimages)>1 ){
					unset($allimages[0]);
					foreach($allimages as $item){
						print_r($item);
						print_r("<br>");
						$this->db->query("DELETE  FROM " . DB_PREFIX . "vendor_image WHERE vendor_image_id = '".$item['vendor_image_id']."'")->rows;
					}
				}
			}
			print_r("finished");
	}
	
	
	
	public function insert_customers(){
		set_time_limit(0);
			ignore_user_abort(true);
	$contents =file('data.txt') ;
	
	$this->load->model('account/customer');
	foreach($contents as $index =>$item){
	    if($index<2){
	        continue;
        }

	    $pass_index = $index-1;
	    $user_arr = explode(',',$item);

	    $fisrt_name = $user_arr['2'];
	    $email = $user_arr['3'];
        $amount = $user_arr['5'];

        $pass = 'TPY_'.$pass_index;

        if($index<100){
            $pass = 'TPY_0'.$pass_index;
        }
        if($index<10){
            $pass = 'TPY_00'.$pass_index;
        }
		if(!empty($email)){
			$customer =  $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email ='".$email."'")->row;
			if(empty($customer)){
				
				$user_data = array(
					'firstname'  =>$fisrt_name,
					'lastname'  =>'',
					'telephone'  =>'',
					'password'	 =>$pass,
					'email'		 =>$email,
					'address_1'=>'',
					'address_2'=>'',
					'city'=>'',
					'postcode'=>'',
					'country_id'=>'',
					'company'=>'',
					'approved'=>1,
					'fax'=>'',
                    'customer_group_id'=>4,
					'zone_id'=>''
				);

				$customer_id = $this->model_account_customer->addCustomer2($user_data);
                $this->model_account_customer->addTransaction($customer_id,'太平洋',$amount);
				print_r("email : $email password: $pass <BR>");
			}else{
			    print_r("user exist:".$customer['customer_id']. ':'.$email."<BR>");
				//$customer_id = $customer['customer_id'];
			}


		}
		
	}
	print_r("job done!");
	}
	
	
	public function insert_reviews(){
	$json_reviews =file_get_contents('review.json') ;
	$json_data = json_decode($json_reviews,true);
	$email_suffixs = array('@emirates.net.ae','@alfahimgroup.ae','@yahoo.com','@absconsulting.com','@eim.ae','@dmecables.com','@amfreights.com','@ae.abb.com','@gb.abb.com','@aalborg-industries.ae','@gmail.com','@hotmail.com');
	$this->load->model('account/customer');
	foreach($json_data as $item){
		
		$customer =  $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE firstname ='".$item['user_name']."'")->row;
		if(empty($customer)){
			$email_suffix = $email_suffixs[mt_rand(0,count($email_suffixs))];
			$user_data = array(
				'firstname'  =>$item['user_name'],
				'password'	 =>$this->create_password(),
				'email'		 =>$this->trimall($item['user_name']).$email_suffix
			);
			$customer_id = $this->model_account_customer->addCustomer($user_data);
			print_r("fist");
		}else{
			$customer_id = $customer['customer_id'];
		}
		if(!empty($item['product_id'])){
		}
		
		$customer_id=0;
		
		$begintime = '2015-05-02 14:21:12';
		$endtime = '2015-10-30 23:11:00';
		$randomtime = $this->randomDate($begintime,$endtime);
		if(!empty($item['vendor_id'])){
			$reviews =  $this->db->query("SELECT * FROM " . DB_PREFIX . "review WHERE text ='".$this->db->escape($item['comment'])."' AND customer_id='".$customer_id."' AND vendor_id='".(int)$item['vendor_id']."'")->rows;
			if(empty($reviews)){
				$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $item['user_name'] . "', customer_id = '" . $customer_id . "', vendor_id = '" . (int)$item['vendor_id'] . "', product_id = '" . (int)$item['product_id'] . "', text = '" . $this->db->escape($item['comment']) . "', rating = '" . (int)$item['rating'] . "', date_added = '".$randomtime."'");
			}else{
				print_r("username:".$item['user_name'].  "'s review Exist!.<br>");
			}
		}
	}
	print_r("job done!");
	}
	
	
	/**
 *   生成某个范围内的随机时间
 * @param <type> $begintime  起始时间 格式为 Y-m-d H:i:s
 * @param <type> $endtime    结束时间 格式为 Y-m-d H:i:s  
 */
	public function randomDate($begintime, $endtime="") {
		$begin = strtotime($begintime);
		$end = $endtime == "" ? mktime() : strtotime($endtime);
		$timestamp = rand($begin, $end);
		return date("Y-m-d H:i:s", $timestamp);
	}

	public function trimall($str)//删除空格
	{
		$qian=array(" ","#039;","&","　","\t","\n","\r");$hou=array("","","","","","","");
		return str_replace($qian,$hou,$str);    
	}
	
	
	public function create_password($pw_length = 8)
	{
		$randpwd = '';
		for ($i = 0; $i < $pw_length; $i++) 
		{
			$randpwd .= chr(mt_rand(97, 122));
		}
		
		return $randpwd;
	}

	
	public function test(){
		echo  file_get_contents('https://www.zomato.com/dubai/moshi-momo-sushi-barsha-1');exit;
	}
	
	
	/*
	"name":"Nayaab Haandi Dubai","telephone":"04 4535440 ","commercial ":"Satwa","address":"         Nayaab Haandi Satwa, Dubai, UAE address     ","timetable":[{"w":"Sun","d":"12 Noon to 4 PM, 7 PM to 12 Midnight"},{"w":"Mon","d":"12 Noon to 4 PM, 7 PM to 12 Midnight"},{"w":"Tue","d":"12 Noon to 4 PM, 7 PM to 12 Midnight"},{"w":"Wed","d":"12 Noon to 4 PM, 7 PM to 12 Midnight"},{"w":"Thu","d":"12 Noon to 4 PM, 7 PM to 12 Midnight"},{"w":"Fri","d":"12:30 PM to 4 PM, 7 PM to 12 Midnight"},{"w":"Sat","d":"12 Noon to 4 PM, 7 PM to 12 Midnight"}],"tag_list":["Home Delivery","Dine-In Available","Wheelchair Accessible","Outdoor Seating","Valet Parking Available","Private Dining Area Available"],"cost":" AED 110 for two people (approx.) ","image_url":"https:\/\/b.zmtcdn.com\/data\/res_imagery\/209786_RESTAURANT_fafdbdf1277917f197c57c3b143471f2_c.jpg
	*/
	
	
	public function auto_vendor(){
			set_time_limit(0);
			ignore_user_abort(true);
			$this->load->model('catalog/vendorlogo');
					
			$uri = "http://www.greadeal.com/crawler_tool/index.php/vendor/get";
			$jsonvendors = get_by_curl($uri);
			$vendors = json_decode($jsonvendors,true);
			print_r(count($vendors));
			print_r("<br>");
			$opendaytags= array(
				'Sun'=>0,
				'Mon'=>1,
				'Tue'=>2,
				'Wed'=>3,
				'Thu'=>4,
				'Fri'=>5,
				'Sat'=>6,
				'1 AM' =>'01:00:00',
				'2 AM' =>'02:00:00',
				'3 AM' =>'03:00:00',
				'4 AM' =>'04:00:00',
				'5 AM' =>'05:00:00',
				'6 AM' =>'06:00:00',
				'7 AM' =>'07:00:00',
				'8 AM' =>'08:00:00',
				'9 AM' =>'09:00:00',
				'10 AM' =>'10:00:00',
				'11 AM' =>'11:00:00',
				'1:30 AM' =>'01:30:00',
				'2:30 AM' =>'02:30:00',
				'3:30 AM' =>'03:30:00',
				'4:30 AM' =>'04:30:00',
				'5:30 AM' =>'05:30:00',
				'6:30 AM' =>'06:30:00',
				'7:30 AM' =>'07:30:00',
				'8:30 AM' =>'08:30:00',
				'9:30 AM' =>'09:30:00',
				'10:30 AM' =>'10:30:00',
				'11:30 AM' =>'11:30:00',
				
				'1:15 AM' =>'01:00:00',
				'2:15 AM' =>'02:00:00',
				'3:15 AM' =>'03:00:00',
				'4:15 AM' =>'04:00:00',
				'5:15 AM' =>'05:00:00',
				'6:15 AM' =>'06:00:00',
				'7:15 AM' =>'07:00:00',
				'8:15 AM' =>'08:00:00',
				'9:15 AM' =>'09:00:00',
				'10:15 AM' =>'10:00:00',
				'11:15 AM' =>'11:00:00',
				'1:45 AM' =>'01:30:00',
				'2:45 AM' =>'02:30:00',
				'3:45 AM' =>'03:30:00',
				'4:45 AM' =>'04:30:00',
				'5:45 AM' =>'05:30:00',
				'6:45 AM' =>'06:30:00',
				'7:45 AM' =>'07:30:00',
				'8:45 AM' =>'08:30:00',
				'9:45 AM' =>'09:30:00',
				'10:45 AM' =>'10:30:00',
				'11:45 AM' =>'11:30:00',
				'12 Noon'=>'12:00:00',
				'1 PM' =>'13:00:00',
				'2 PM' =>'14:00:00',
				'3 PM' =>'00:00:00',
				'4 PM' =>'16:00:00',
				'5 PM' =>'17:00:00',
				'6 PM' =>'18:00:00',
				'7 PM' =>'19:00:00',
				'8 PM' =>'20:00:00',
				'9 PM' =>'21:00:00',
				'10 PM' =>'22:00:00',
				'11 PM' =>'23:00:00',
				'12:30 AM'=>'12:30:00',
				'12:30 PM'=>'12:30:00',
				'1:30 PM' =>'13:30:00',
				'2:30 PM' =>'14:30:00',
				'3:30 PM' =>'15:30:00',
				'4:30 PM' =>'16:30:00',
				'5:30 PM' =>'17:30:00',
				'6:30 PM' =>'18:30:00',
				'7:30 PM' =>'19:30:00',
				'8:30 PM' =>'20:30:00',
				'9:30 PM' =>'21:30:00',
				'10:30 PM' =>'22:30:00',
				'11:30 PM' =>'23:30:00',
				
				
				'12:15 AM'=>'12:00:00',
				'12:15 PM'=>'12:00:00',
				'1:15 PM' =>'13:00:00',
				'2:15 PM' =>'14:00:00',
				'3:15 PM' =>'15:00:00',
				'4:15 PM' =>'16:00:00',
				'5:15 PM' =>'17:00:00',
				'6:15 PM' =>'18:00:00',
				'7:15 PM' =>'19:00:00',
				'8:15 PM' =>'20:00:00',
				'9:15 PM' =>'21:00:00',
				'10:15 PM' =>'22:00:00',
				'11:15 PM' =>'23:00:00',
				
				'12:45 AM'=>'12:30:00',
				'12:45 PM'=>'12:30:00',
				'1:45 PM' =>'13:30:00',
				'2:45 PM' =>'14:30:00',
				'3:45 PM' =>'15:30:00',
				'4:45 PM' =>'16:30:00',
				'5:45 PM' =>'17:30:00',
				'6:45 PM' =>'18:30:00',
				'7:45 PM' =>'19:30:00',
				'8:45 PM' =>'20:30:00',
				'9:45 PM' =>'21:30:00',
				'10:45 PM' =>'22:30:00',
				'11:45 PM' =>'23:30:00',
				'12 Midnight'=>'24:00:00'
			);
			
			$email_suffixs = array('@emirates.net.ae','@alfahimgroup.ae','@yahoo.com','@absconsulting.com','@eim.ae','@dmecables.com','@amfreights.com','@ae.abb.com','@gb.abb.com','@aalborg-industries.ae','@gmail.com','@hotmail.com');
			$email_suffix = $email_suffixs[mt_rand(0,count($email_suffixs))];
			foreach($vendors as $vendor){
				$data = array();
				
				//获取基本信息
				$baseinfo = json_decode($vendor['baseinfo_json'],true);
				
				$baseinfo['name'] = str_replace('Dubai','',$baseinfo['name']);
				
				$baseinfo['name'] = trim($baseinfo['name']);
				$dir = $this->trimall($baseinfo['name']);
				
				
				
				$data['vendor_name'] =$baseinfo['name'];
				$vendor_info = $this->model_catalog_vendorlogo->getDisableVendorByName($baseinfo['name']);
				
				if(!empty($vendor_info)&&(strpos($baseinfo['name'], '&')!==FALSE||strpos($baseinfo['name'], '#039;')!==FALSE)){
					print_r("beging insert vendor:".$baseinfo['name']."<BR>");
					//continue;
					$baseinfo['telephone'] = str_replace(' ','',$baseinfo['telephone']);
					$baseinfo['telephone'] = str_replace('-','',$baseinfo['telephone']);
					$data['telephone'] = $baseinfo['telephone'];
					$data['address'] = $baseinfo['address'];
					$openday = array();
					foreach ($baseinfo['timetable'] as $tt){
						$d = $tt['d'];
						$dd = explode('to',$d);
						
						$ds = trim($dd[0]);
						$de = trim($dd[count($dd)-1]);
						if(empty($opendaytags[$ds])||empty($opendaytags[$de])){
							continue;
						}
						if($ds == '24 Hours'){
							$openday[$opendaytags[$tt['w']]] = array(
								'start' =>$opendaytags['1 AM'],
								'end' =>$opendaytags['12 Midnight'],
							);
						}else{
							$openday[$opendaytags[$tt['w']]] = array(
								'start' =>$opendaytags[$ds],
								'end' =>$opendaytags[$de],
							);
						}
						
					}
					$data['openday'] = $openday;
					$vendor_description[1] = array(
						'tags_json' => implode(',',$baseinfo['tag_list']),
						'description' => $vendor['url'],
						'vendor_name' => $baseinfo['name']
					);
					$vendor_description[3] = array(
						'tags_json' => implode(',',$baseinfo['tag_list']),
						'description' => $vendor['url'],
						'vendor_name' => $baseinfo['name']
					);
					
					$data['vendor_description'] = $vendor_description;
					
					$imagename = md5($baseinfo['name'].$baseinfo['image_url']);
					$dir = $this->trimall($baseinfo['name']);
					$suffix = ".jpg";
					if(strpos($baseinfo['image_url'], "data:image/pngbase64")!==FALSE){
						$suffix = ".png";
					}
					
					if(strpos($baseinfo['image_url'], "data:image/jpegbase64")!==FALSE){
						//print_r($baseinfo['image_url']."<BR>".strpos($baseinfo['image_url'], "data:image/jpegbase64"));
						$suffix = ".jpeg";
					}
					
					/*vendor SET longitude = '" . $this->db->escape($data['longitude']) . "', latitude = '" . $this->db->escape($data['latitude'])  . "', telephone = '" . $this->db->escape($data['telephone']) . "', delivery_time = '" . $this->db->escape($data['delivery_time']) . "', email = '" . $this->db->escape($data['email']). "', takeout = '" . (int)$data['takeout'] . "', delivery_time_start = '" . $this->db->escape($data['delivery_time_start']) . "', delivery_time_end = '" . $this->db->escape($data['delivery_time_end']) . "', delivery_fee = '" . $this->db->escape($data['delivery_fee']) . "', min_order_fee = '" . $this->db->escape($data['min_order_fee']) . "', fee_per_person = '" . $this->db->escape($data['fee_per_person']) . "',";	*/
					
					
					$data['vendor_image'] = "catalog/$dir/$imagename$suffix";
					$data['longitude'] = '';
					$data['latitude'] = '';
					$data['delivery_time'] = '';
					$data['takeout'] = '';
					$data['delivery_time_start'] = '';
					$data['delivery_time_end'] = '';
					$data['delivery_fee'] = '';
					$data['min_order_fee'] = '';
					$data['fee_per_person'] = '';
					$data['country_id'] = 221;
					$addressarr = explode(',',$baseinfo['address']);
					$zone_name = trim($addressarr[1]);
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE name LIKE '%" . $zone_name . "%' AND status = '1'");

					$zonedata =  $query->row;
					
					$data['zone_id'] = $zonedata['zone_id'];
					
					
					/*$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username1']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password']))))  . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['vendor_image']) . "', folder = '" . (isset($this->request->post['generate_path']) ? $this->db->escape($data['username1']) : '') . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");*/
					
					$begintime = '2015-08-02 14:21:12';
					$endtime = '2015-10-30 23:11:00';
					$randomtime1 = $this->randomDate($begintime,$endtime);
					$begintime = '2016-11-02 14:21:12';
					$endtime = '2017-10-30 23:11:00';
					$randomtime2 = $this->randomDate($begintime,$endtime);
					$data['user_group_id'] = 50;
					$data['username1'] = $baseinfo['telephone'];
					$data['password'] = '123456';
					$data['email'] =$baseinfo['telephone'].$email_suffix;
					$data['image'] = $data['vendor_image'];
					$data['vendor_category'] = '0';
					$data['user_date_start'] = $randomtime1;
					$data['user_date_end'] = $randomtime2;
					$data['status'] = 0;
					
					
					$vendor_id = $this->model_catalog_vendorlogo->addVendor($data);
					print_r("insert vendor:".$baseinfo['name']." success:vendor_id=====$vendor_id <BR>");
		
					$imagedata = array();
					//获取菜单图片
					$menuimages = json_decode($vendor['menulist_json'],true);
					$sort_order = 0;
					$menudir = $dir.'/'.'menu';
					$imagedata['vendor'] = $vendor_id;
					$imagedata['type'] ='menu';
					foreach($menuimages as $menuimage){
						$menu_image_name = md5($menuimage).'.'.end(explode('.', $menuimage));
						$imagedata['vendor_image'][] = array(
							'image'=>"catalog/$menudir/$menu_image_name",
							'sort_order'=>$sort_order,
							'status' =>1
							
						);
						$sort_order++;
						
					}
					$this->model_catalog_vendorlogo->addVendorImages($imagedata);
					
					
					print_r("insert vendor menu image:success:vendor_id=====$vendor_id <BR>");
					$imagedata = array();
					$imagedata['vendor'] = $vendor_id;
					$imagedata['type'] ='common';
					//获取商家图片
					$commonimages = json_decode($vendor['imagelist_json'],true);
					$sort_order = 0;
					$commondir = $dir.'/'.'common';
					$vendor_image = '';
					$record = true;
					
					foreach($commonimages as $commonimage){
						$common_image_name = md5($commonimage).'.'.end(explode('.', $commonimage));
						
						$imagedata['vendor_image'][] = array(
							'image'=>"catalog/$commondir/$common_image_name",
							'sort_order'=>$sort_order,
							'status' =>1
							
						);
						if($record){
							$vendor_image = "catalog/$commondir/$common_image_name";
						}else{
							$record = false;
						}
						$sort_order++;
						
					}
					$this->model_catalog_vendorlogo->addVendorImages($imagedata);
					$this->model_catalog_vendorlogo->updateVendorImage($vendor_id,$vendor_image);
					$this->download_vendor_image($vendor);
						print_r("insert vendor common image:success:vendor_id=====$vendor_id <BR>");
					
					
					
					
				}else{
					//print_r("vendor:".$baseinfo['name']." exist<BR>");
				}
				
				}
				
			print_r("finished");
	}
	
	
	
	
	public function addMenu($vendor){
		$menu=$vendor['menu'];
		

	}
	
	
	
	public function testyh(){
		$data = "Papa Murphy's Pizza";
		$re = str_replace("'","''",$data);
		print($re);
	}
	
	
	
	
	
	public function fix_vendor(){
			set_time_limit(0);
			ignore_user_abort(true);
			$this->load->model('catalog/vendorlogo');
					
			$uri = "http://www.greadeal.com/crawler_tool/index.php/vendor/get";
			$jsonvendors = get_by_curl($uri);
			$vendors = json_decode($jsonvendors,true);
			print_r(count($vendors));
			print_r("<br>");
			$opendaytags= array(
				'Sun'=>0,
				'Mon'=>1,
				'Tue'=>2,
				'Wed'=>3,
				'Thu'=>4,
				'Fri'=>5,
				'Sat'=>6,
				'1 AM' =>'01:00:00',
				'2 AM' =>'02:00:00',
				'3 AM' =>'03:00:00',
				'4 AM' =>'04:00:00',
				'5 AM' =>'05:00:00',
				'6 AM' =>'06:00:00',
				'7 AM' =>'07:00:00',
				'8 AM' =>'08:00:00',
				'9 AM' =>'09:00:00',
				'10 AM' =>'10:00:00',
				'11 AM' =>'11:00:00',
				'1:30 AM' =>'01:30:00',
				'2:30 AM' =>'02:30:00',
				'3:30 AM' =>'03:30:00',
				'4:30 AM' =>'04:30:00',
				'5:30 AM' =>'05:30:00',
				'6:30 AM' =>'06:30:00',
				'7:30 AM' =>'07:30:00',
				'8:30 AM' =>'08:30:00',
				'9:30 AM' =>'09:30:00',
				'10:30 AM' =>'10:30:00',
				'11:30 AM' =>'11:30:00',
				
				'1:15 AM' =>'01:00:00',
				'2:15 AM' =>'02:00:00',
				'3:15 AM' =>'03:00:00',
				'4:15 AM' =>'04:00:00',
				'5:15 AM' =>'05:00:00',
				'6:15 AM' =>'06:00:00',
				'7:15 AM' =>'07:00:00',
				'8:15 AM' =>'08:00:00',
				'9:15 AM' =>'09:00:00',
				'10:15 AM' =>'10:00:00',
				'11:15 AM' =>'11:00:00',
				'1:45 AM' =>'01:30:00',
				'2:45 AM' =>'02:30:00',
				'3:45 AM' =>'03:30:00',
				'4:45 AM' =>'04:30:00',
				'5:45 AM' =>'05:30:00',
				'6:45 AM' =>'06:30:00',
				'7:45 AM' =>'07:30:00',
				'8:45 AM' =>'08:30:00',
				'9:45 AM' =>'09:30:00',
				'10:45 AM' =>'10:30:00',
				'11:45 AM' =>'11:30:00',
				'12 Noon'=>'12:00:00',
				'1 PM' =>'13:00:00',
				'2 PM' =>'14:00:00',
				'3 PM' =>'00:00:00',
				'4 PM' =>'16:00:00',
				'5 PM' =>'17:00:00',
				'6 PM' =>'18:00:00',
				'7 PM' =>'19:00:00',
				'8 PM' =>'20:00:00',
				'9 PM' =>'21:00:00',
				'10 PM' =>'22:00:00',
				'11 PM' =>'23:00:00',
				'12:30 AM'=>'12:30:00',
				'12:30 PM'=>'12:30:00',
				'1:30 PM' =>'13:30:00',
				'2:30 PM' =>'14:30:00',
				'3:30 PM' =>'15:30:00',
				'4:30 PM' =>'16:30:00',
				'5:30 PM' =>'17:30:00',
				'6:30 PM' =>'18:30:00',
				'7:30 PM' =>'19:30:00',
				'8:30 PM' =>'20:30:00',
				'9:30 PM' =>'21:30:00',
				'10:30 PM' =>'22:30:00',
				'11:30 PM' =>'23:30:00',
				
				
				'12:15 AM'=>'12:00:00',
				'12:15 PM'=>'12:00:00',
				'1:15 PM' =>'13:00:00',
				'2:15 PM' =>'14:00:00',
				'3:15 PM' =>'15:00:00',
				'4:15 PM' =>'16:00:00',
				'5:15 PM' =>'17:00:00',
				'6:15 PM' =>'18:00:00',
				'7:15 PM' =>'19:00:00',
				'8:15 PM' =>'20:00:00',
				'9:15 PM' =>'21:00:00',
				'10:15 PM' =>'22:00:00',
				'11:15 PM' =>'23:00:00',
				
				'12:45 AM'=>'12:30:00',
				'12:45 PM'=>'12:30:00',
				'1:45 PM' =>'13:30:00',
				'2:45 PM' =>'14:30:00',
				'3:45 PM' =>'15:30:00',
				'4:45 PM' =>'16:30:00',
				'5:45 PM' =>'17:30:00',
				'6:45 PM' =>'18:30:00',
				'7:45 PM' =>'19:30:00',
				'8:45 PM' =>'20:30:00',
				'9:45 PM' =>'21:30:00',
				'10:45 PM' =>'22:30:00',
				'11:45 PM' =>'23:30:00',
				'12 Midnight'=>'24:00:00'
			);
			
			$email_suffixs = array('@emirates.net.ae','@alfahimgroup.ae','@yahoo.com','@absconsulting.com','@eim.ae','@dmecables.com','@amfreights.com','@ae.abb.com','@gb.abb.com','@aalborg-industries.ae','@gmail.com','@hotmail.com');
			$email_suffix = $email_suffixs[mt_rand(0,count($email_suffixs))];
			foreach($vendors as $vendor){
				$data = array();
				
				//获取基本信息
				$baseinfo = json_decode($vendor['baseinfo_json'],true);
				$baseinfo['name'] = str_replace(array('Dubai','Abu Dhabi','Al Ain'),array('','',''),$baseinfo['name']);
				$baseinfo['name'] = trim($baseinfo['name']);
				$dir = $this->trimall($baseinfo['name']);
				
				
				
				$data['vendor_name'] =$baseinfo['name'];
				
				$search_name = str_replace("#039;","''",$data['vendor_name']);
				print_r("vendorName:".$data['vendor_name']."::SELECT * FROM `oc_vendor_description` WHERE `vendor_name` LIKE '%$search_name%' <BR>");
				$query = $this->db->query("SELECT * FROM `oc_vendor_description` WHERE `vendor_name` LIKE '%$search_name%'");
				
				
	
				$vendor_info = $query->row;
				
				
				//$vendor_info = $this->model_catalog_vendorlogo->getAllVendorByName($baseinfo['name']);
				
				if(strpos($baseinfo['name'], '#039;')!==FALSE){
					print_r("beging insert vendor:".$baseinfo['name']."<BR>");
					
					$baseinfo['telephone'] = str_replace(' ','',$baseinfo['telephone']);
					$baseinfo['telephone'] = str_replace('-','',$baseinfo['telephone']);
					$data['telephone'] = $baseinfo['telephone'];
					$data['address'] = $baseinfo['address'];
					$openday = array();
					foreach ($baseinfo['timetable'] as $tt){
						$d = $tt['d'];
						$dd = explode('to',$d);
						
						$ds = trim($dd[0]);
						$de = trim($dd[count($dd)-1]);
						if(empty($opendaytags[$ds])||empty($opendaytags[$de])){
							continue;
						}
						if($ds == '24 Hours'){
							$openday[$opendaytags[$tt['w']]] = array(
								'start' =>$opendaytags['1 AM'],
								'end' =>$opendaytags['12 Midnight'],
							);
						}else{
							$openday[$opendaytags[$tt['w']]] = array(
								'start' =>$opendaytags[$ds],
								'end' =>$opendaytags[$de],
							);
						}
						
					}
					$data['openday'] = $openday;
					$vendor_description[1] = array(
						'tags_json' => implode(',',$baseinfo['tag_list']),
						'description' => $vendor['url'],
						'vendor_name' => $baseinfo['name']
					);
					$vendor_description[3] = array(
						'tags_json' => implode(',',$baseinfo['tag_list']),
						'description' => $vendor['url'],
						'vendor_name' => $baseinfo['name']
					);
					
					$data['vendor_description'] = $vendor_description;
					
					$imagename = md5($baseinfo['name'].$baseinfo['image_url']);
					$dir = $this->trimall($baseinfo['name']);
					$suffix = ".jpg";
					if(strpos($baseinfo['image_url'], "data:image/pngbase64")!==FALSE){
						$suffix = ".png";
					}
					
					if(strpos($baseinfo['image_url'], "data:image/jpegbase64")!==FALSE){
						//print_r($baseinfo['image_url']."<BR>".strpos($baseinfo['image_url'], "data:image/jpegbase64"));
						$suffix = ".jpeg";
					}
					
					/*vendor SET longitude = '" . $this->db->escape($data['longitude']) . "', latitude = '" . $this->db->escape($data['latitude'])  . "', telephone = '" . $this->db->escape($data['telephone']) . "', delivery_time = '" . $this->db->escape($data['delivery_time']) . "', email = '" . $this->db->escape($data['email']). "', takeout = '" . (int)$data['takeout'] . "', delivery_time_start = '" . $this->db->escape($data['delivery_time_start']) . "', delivery_time_end = '" . $this->db->escape($data['delivery_time_end']) . "', delivery_fee = '" . $this->db->escape($data['delivery_fee']) . "', min_order_fee = '" . $this->db->escape($data['min_order_fee']) . "', fee_per_person = '" . $this->db->escape($data['fee_per_person']) . "',";	*/
					
					
					$data['vendor_image'] = "catalog/$dir/$imagename$suffix";
					$data['longitude'] = '';
					$data['latitude'] = '';
					$data['delivery_time'] = '';
					$data['takeout'] = '';
					$data['delivery_time_start'] = '';
					$data['delivery_time_end'] = '';
					$data['delivery_fee'] = '';
					$data['min_order_fee'] = '';
					$data['fee_per_person'] = '';
					$data['country_id'] = 221;
					$addressarr = explode(',',$baseinfo['address']);
					$zone_name = trim($addressarr[1]);
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE name LIKE '%" . $zone_name . "%' AND status = '1'");

					$zonedata =  $query->row;
					
					$data['zone_id'] = $zonedata['zone_id'];
					
					
					/*$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username1']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password']))))  . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['vendor_image']) . "', folder = '" . (isset($this->request->post['generate_path']) ? $this->db->escape($data['username1']) : '') . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");*/
					
					$begintime = '2015-08-02 14:21:12';
					$endtime = '2015-10-30 23:11:00';
					$randomtime1 = $this->randomDate($begintime,$endtime);
					$begintime = '2016-11-02 14:21:12';
					$endtime = '2017-10-30 23:11:00';
					$randomtime2 = $this->randomDate($begintime,$endtime);
					$data['user_group_id'] = 50;
					$data['username1'] = $baseinfo['telephone'];
					$data['password'] = '123456';
					$data['email'] =$baseinfo['telephone'].$email_suffix;
					$data['image'] = $data['vendor_image'];
					$data['vendor_category'] = '0';
					$data['user_date_start'] = $randomtime1;
					$data['user_date_end'] = $randomtime2;
					$data['status'] = 0;
					
					
					if(empty($vendor_info)){
						print_r("vendor  empty:".$baseinfo['name']."<BR>");
						//continue;
						//$vendor_id = $this->model_catalog_vendorlogo->addVendor($data);
						}
					else{
						$vendor_id = $vendor_info['vendor_id'];
						print_r("vendor $vendor_id  exist :".$baseinfo['name']."<BR>");
						//continue;
					}
					//continue;
					if($vendor_id!=712){
						continue;
					}
					
					$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_image WHERE vendor_id = '" . (int)$vendor_id . "'");
					print_r("insert vendor:".$baseinfo['name']." success:vendor_id=====$vendor_id <BR>");
				//	continue;
					$imagedata = array();
					//获取菜单图片
					$menuimages = json_decode($vendor['menulist_json'],true);
					$sort_order = 0;
					$menudir = $dir.'/'.'menu';
					$imagedata['vendor'] = $vendor_id;
					$imagedata['type'] ='menu';
					foreach($menuimages as $menuimage){
						$menu_image_name = md5($menuimage).'.'.end(explode('.', $menuimage));
						$imagedata['vendor_image'][] = array(
							'image'=>"catalog/$menudir/$menu_image_name",
							'sort_order'=>$sort_order,
							'status' =>1
							
						);
						$sort_order++;
						
					}
					$this->model_catalog_vendorlogo->addVendorImages($imagedata);
					
					
					print_r("insert vendor menu image:success:vendor_id=====$vendor_id <BR>");
					$imagedata = array();
					$imagedata['vendor'] = $vendor_id;
					$imagedata['type'] ='common';
					//获取商家图片
					$commonimages = json_decode($vendor['imagelist_json'],true);
					$sort_order = 0;
					$commondir = $dir.'/'.'common';
					$vendor_image = '';
					$record = true;
					
					foreach($commonimages as $commonimage){
						$common_image_name = md5($commonimage).'.'.end(explode('.', $commonimage));
						
						$imagedata['vendor_image'][] = array(
							'image'=>"catalog/$commondir/$common_image_name",
							'sort_order'=>$sort_order,
							'status' =>1
							
						);
						if($record){
							$vendor_image = "catalog/$commondir/$common_image_name";
						}else{
							$record = false;
						}
						$sort_order++;
						
					}
					$this->model_catalog_vendorlogo->addVendorImages($imagedata);
					$this->model_catalog_vendorlogo->updateVendorImage($vendor_id,$vendor_image);
					$this->download_vendor_image($vendor);
						print_r("insert vendor common image:success:vendor_id=====$vendor_id <BR>");
					
					
					
					
				}else{
					//print_r("vendor:".$baseinfo['name']." exist<BR>");
				}
				
				}
				
			print_r("finished");
	}
	
	
	
	
	
	public function download_vendor_image($vendor){
	
		//获取基本信息
		$baseinfo = json_decode($vendor['baseinfo_json'],true);
		$baseinfo['name'] = str_replace(array('Dubai','Abu Dhabi','Al Ain'),array('','',''),$baseinfo['name']);
		$baseinfo['name'] = trim($baseinfo['name']);
		$imagename = md5($baseinfo['name'].$baseinfo['image_url']);
		$dir = $this->trimall($baseinfo['name']);
		$suffix = ".jpg";
		$idata = '';
		if(strpos($baseinfo['image_url'], "data:image/pngbase64")!==FALSE){
			$idata = "data:image/pngbase64";
			$base64image = true;
			$suffix = ".png";
		}
		
		if(strpos($baseinfo['image_url'], "data:image/jpegbase64")!==FALSE){
			$idata = "data:image/jpegbase64";
			$base64image = true;
			$suffix = ".jpeg";
		}
		if(strpos($baseinfo['image_url'], "data:image/jpgbase64")!==FALSE){
			$base64image = true;
			$idata = "data:image/jpgbase64";
			$suffix = ".jpg";
		}
		$filename = "catalog/$dir/$imagename$suffix";
		if($base64image){
			print_r($baseinfo['image_url']."<br><br><br>");
			$dataarr = explode(",",$baseinfo['image_url']);
			$data = $dataarr[1];
			$imageData = base64_decode($data);
			$source = imagecreatefromstring($imageData);
			$imageSave = imagejpeg($source,DIR_IMAGE.$filename);
			imagedestroy($source);
		}else{
			$this->download($vendor['id'],$filename,$baseinfo['image_url']);
		}
		print_r("download image :".$baseinfo['image_url']."<br>");
		
		//获取菜单图片	
		
		$menuimages = json_decode($vendor['menulist_json'],true);
		$sort_order = 0;
		$menudir = $dir.'/'.'menu';
		foreach($menuimages as $menuimage){
			$menu_image_name = md5($menuimage).'.'.end(explode('.', $menuimage));
			$this->download($vendor['id'],"catalog/$menudir/$menu_image_name",$menuimage);
			print_r("download image :".$menuimage."<br>");
		}
		
		//获取商家图片
		$commonimages = json_decode($vendor['imagelist_json'],true);
		$sort_order = 0;
		$commondir = $dir.'/'.'common';
		foreach($commonimages as $commonimage){
			$menu_image_name = md5($commonimage).'.'.end(explode('.', $commonimage));
			$this->download($vendor['id'],"catalog/$commondir/$menu_image_name",$commonimage);
			print_r("download image :".$commonimage."<br>");
		}
		print_r("download vendor image finished.<BR>");
	}
	
	
	public function download_image(){
		set_time_limit(0);
		ignore_user_abort(true);
		$uri = "http://localhost/crawler_tool/index.php/vendor/get";
			$jsonvendors = get_by_curl($uri);
			$vendors = json_decode($jsonvendors,true);
			foreach($vendors as $vendor){
				//获取基本信息
				$baseinfo = json_decode($vendor['baseinfo_json'],true);
				$baseinfo['name'] = str_replace(array('Dubai','Abu Dhabi'),array('',''),$baseinfo['name']);
				$baseinfo['name'] = trim($baseinfo['name']);
				$imagename = md5($baseinfo['name'].$baseinfo['image_url']);
				$dir = $this->trimall($baseinfo['name']);
				$suffix = ".jpg";
				$base64image = false;
				print_r($baseinfo['image_url']);
				if(strpos($baseinfo['image_url'], "data:image/pngbase64")!==FALSE){
					$base64image = true;
					$suffix = ".png";
				}
				
				if(strpos($baseinfo['image_url'], "data:image/jpegbase64")!==FALSE){
					//print_r($baseinfo['image_url']."<BR>".strpos($baseinfo['image_url'], "data:image/jpegbase64"));
					$base64image = true;
					$suffix = ".jpeg";
				}
				
				if(strpos($baseinfo['image_url'], "data:image/jpgbase64")!==FALSE){
					$base64image = true;
					$suffix = ".jpg";
				}
				$filename = "catalog/$dir/$imagename$suffix";
				if($base64image){
					$data = str_replace('data:image/png;base64,', '', $data);
					$data = str_replace(' ', '+', $data);
					$data = base64_decode($data);
					file_put_contents($filename, $data);
					print_r($data);
					print_r("<br>");
					print_r($filename);
					return;
				}else{
					$this->download($vendor['id'],$filename,$baseinfo['image_url']);
				}
				print_r("download image :".$baseinfo['image_url']."<br>");
				
				//获取菜单图片	
				
				$menuimages = json_decode($vendor['menulist_json'],true);
				$sort_order = 0;
				$menudir = $dir.'/'.'menu';
				foreach($menuimages as $menuimage){
					$menu_image_name = md5($menuimage).'.'.end(explode('.', $menuimage));
					$this->download($vendor['id'],"catalog/$menudir/$menu_image_name",$menuimage);
					print_r("download image :".$menuimage."<br>");
				}
				
				//获取商家图片
				$commonimages = json_decode($vendor['imagelist_json'],true);
				$sort_order = 0;
				$commondir = $dir.'/'.'common';
				foreach($commonimages as $commonimage){
					$menu_image_name = md5($commonimage).'.'.end(explode('.', $commonimage));
					$this->download($vendor['id'],"catalog/$commondir/$menu_image_name",$commonimage);
					print_r("download image :".$commonimage."<br>");
				}
				
			}
			
			print_r("finished");
	}
	
    

   


	private function download($id,$filename,$filelink){
		$filepath = DIR_IMAGE.$filename;
		if(file_exists($filepath)){
			print_r($filepath.":exist!!!!!!");
			return;
		}
			
		$dir = dirname($filepath);
		$this->createdir($dir);
		$cnt=0;   
		$opts = array(   
		  'http'=>array(   
			'method'=>"GET",   
			'timeout'=>30,//单位秒  
		   )   
		); 
		while($cnt<3){
			$file = file_get_contents($filelink, false, stream_context_create($opts));
			if($file===FALSE){
					$logger = new Log('downloadimage.log');
					$logger->write("$id :$filelink download failed:\r\n");
			}else{
				file_put_contents($filepath,$file);
				break;
			}
			$cnt++;
		}  
		
	}
	
	
	
	public function filter_vendor(){
			$vendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor_description")->rows;
			foreach($vendors as $vendor){
				$name = $vendor['vendor_name'];
				foreach($vendors as $item){
					if(($name==$item['vendor_name'])&&($vendor['vendor_id']!=$item['vendor_id'])){
						print_r("vendor:".$vendor['vendor_id']."======".$item['vendor_id']."----------$name<br>");
					}
				}
			}
			print_r("finished");
	}
	
	
	public function delete_disable_vendor(){
			print_r("SELECT * FROM " . DB_PREFIX . "vendor v LEFT JOIN " . DB_PREFIX . "user u ON (v.user_id = u.user_id) WHERE u.status = '0'  AND v.email like '%email_suffix%'");
			$vendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor v LEFT JOIN " . DB_PREFIX . "user u ON (v.user_id = u.user_id) WHERE u.status = '0'  AND v.email like '%email_suffix%'")->rows;
			print_r(count($vendors));
			foreach($vendors as $vendor){
				$vendor_id = $vendor['vendor_id'];
				$user_id = $vendor['user_id'];
				
				print_r("DELETE  FROM " . DB_PREFIX . "vendor WHERE vendor_id = '".$vendor_id."'<BR>" );
				print_r("DELETE  FROM " . DB_PREFIX . "user WHERE user_id = '".$user_id."'<BR>" );
				print_r("DELETE  FROM " . DB_PREFIX . "vendor_image WHERE vendor_id = '".$vendor_id."'<BR>" );
				$this->db->query("DELETE  FROM " . DB_PREFIX . "vendor WHERE vendor_id = '".$vendor_id."'" );
				$this->db->query("DELETE  FROM " . DB_PREFIX . "user WHERE user_id = '".$user_id."'" );
				$this->db->query("DELETE  FROM " . DB_PREFIX . "vendor_image WHERE vendor_id = '".$vendor_id."'" );
			}
			print_r("finished");
	}
	
	
		
	
}