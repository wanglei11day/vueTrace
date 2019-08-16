<?php

//  Live Price 2 / Динамическое обновление цены - живая цена 2
//  Support: support@liveicootoo.com / Поддержка: opencart@19th19th.ru

class ModelModuleLivePrice extends Model {

  var $options_selects = array('select','radio','image','block','color');
  var $cache_cart = array();
  var $cache_price = array();

  public function installed() {
    
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'liveprice'");
    
    return $query->num_rows;
  }
  
  public function clear_cart_cache() {
    $this->cache_cart = array();
  }

  private function arrayDeleteEmpty($arr) {
    
    $new_arr = array();
    foreach ($arr as $key => $val) {
      if ($val) {
        $new_arr[$key] = $val;
      }
    }
    
    return $new_arr;
  }

  private function installedProductSizeOption() {
    
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'option_size'");
    
    return $query->num_rows;
    
  }
  
  
  private function ProductHasSizeOption($options, $options_types) {
    
    foreach ($options as $product_option_id => $option_value) {
      
      if (!isset($options_types[$product_option_id])) {
        continue;
      }
      
      /*
      if ( isset($options_types[$product_option_id]['calculate_once']) && $options_types[$product_option_id]['calculate_once'] == 1 ) {
        continue;
      }
      */
      
      if ($options_types[$product_option_id]['type'] == 'size') {
        return true;
      }
      
    }
    
    return false;
    
  }

  //$current_quantity < 0 (cart call)
  private function calculateOptionPrice(&$option_price, $product_id, $price, $points, $options, $options_types, $options_values, $get_full_data=false, $recurring_id=0, $quantity=0, $current_quantity=0, &$option_data=array(), &$option_points=0, &$option_weight=0, &$stock=true) {
    
    $price_rewrited = false;
    
    $installedPSO = $this->installedProductSizeOption();
    if ($installedPSO) {
      
      $min_width = 0;
      $max_width = 0;
      $min_height = 0;
      $max_height = 0;
      $cost_per_square = 0;
      $option_size_id = 0;
      $product_option_size_id = 0;
      $product_width = 0;
      $product_height = 0;
      $product_options =  $this->cart->getAllProductOption($product_id);
      if(!empty($product_options)) {
        foreach($product_options as $product_option) {
          if ($product_option['min_width'] != '' && $product_option['max_width'] != '' && $product_option['cost_per_square'] != '') {
            $min_width = $product_option['min_width'];
            $max_width = $product_option['max_width'];
            $min_height = $product_option['min_height'];
            $max_height = $product_option['max_height'];
            $cost_per_square = $product_option['cost_per_square'];
            $product_option_size_id = $product_option['product_option_id'];
            $option_size_id = $product_option['option_id']; 
            break;
          }
        }
      }
      if($cost_per_square > 0 && $product_option_size_id > 0) {
        $product_width = isset($options[$product_option_size_id]['width']) ? $options[$product_option_size_id]['width'] : 0;
        $product_height = isset($options[$product_option_size_id]['height']) ? $options[$product_option_size_id]['height'] : 0; 
      }
      
      $has_size_option = false;
      
    }
    
    
    $option_price = 0;
    
    // надо вынести в функцию
    foreach ($options as $product_option_id => $option_value) {
      
      if (!isset($options_types[$product_option_id])) {
        continue;
      }
      
      $calc_multiplier = 1;
      if ( isset($options_types[$product_option_id]['calculate_once']) && $options_types[$product_option_id]['calculate_once'] == 1 ) {
        //$calc_multiplier = 1 / ($current_quantity<0 ? $quantity : ($current_quantity==0 ? 1 : $current_quantity) );
        $calc_multiplier = 1 / max(abs($current_quantity), 1) ;
      }
      
      $options_array = array();
      if ( in_array($options_types[$product_option_id]['type'], $this->options_selects) ) {
        $options_array = array($option_value);
      } elseif ( $options_types[$product_option_id]['type'] == 'checkbox' && is_array($options_array) ) {
        $options_array = $option_value;
      }
      
      if ( (in_array($options_types[$product_option_id]['type'], $this->options_selects) || $options_types[$product_option_id]['type'] == 'checkbox')
          && isset($options_values[$product_option_id]) ) {
        
        $povs = $options_values[$product_option_id];
        
        
        
        foreach ($options_array as $product_option_value_id) {
          
          if ( isset($povs[$product_option_value_id]) ) {
            
            $pov = $povs[$product_option_value_id];
            
            // Product Size Option
            if ($installedPSO && $this->cart->checkScaleWithSize($product_id, $product_option_id, $option_size_id)) {
              $pov['price'] = $calc_multiplier * $pov['price'] * $product_width * $product_height;
            }
            
            if ($pov['price'] != 0) {
            
              if ($pov['price_prefix'] == '+') {
                
                $option_price += $calc_multiplier * $pov['price'];
                
              } elseif ($pov['price_prefix'] == '-') {
                $option_price -= $calc_multiplier * $pov['price'];
                
              } elseif ($pov['price_prefix'] == '%') {
                
                $current_price = $price+$option_price;
                $option_price = round($current_price*(100+$pov['price'])/100,2)-$price;
                //$current_price = $price; // % works on basic product price
                //$option_price = round($current_price*$pov['price']/100,2);  
                
              } elseif ($pov['price_prefix'] == '*') {
                $current_price = $price+$option_price;
                $option_price = round($current_price*$pov['price'],2)-$price;
                
              } elseif ($pov['price_prefix'] == '/' && $pov['price']!=0) {
                $current_price = $price+$option_price;
                $option_price = round($current_price/$pov['price'],2)-$price;
                
              } elseif ($pov['price_prefix'] == '=') {
                $current_price = $price+$option_price;
                $option_price = $pov['price']-$current_price;
                $price_rewrited = true;
              }
            }
            
            if ($get_full_data) {
            
              if ($pov['points_prefix'] == '=') {
                $current_points = $points+$option_points;
                $option_points = $pov['points']-$current_points;
              } elseif ($pov['points_prefix'] == '+') {
                $option_points += $calc_multiplier * $pov['points'];
              } elseif ($pov['points_prefix'] == '-') {
                $option_points -= $calc_multiplier * $pov['points'];
              }
             
                            
              if ($pov['weight_prefix'] == '+') {
                $option_weight += $calc_multiplier * $pov['weight'];
              } elseif ($pov['weight_prefix'] == '-') {
                $option_weight -= $calc_multiplier * $pov['weight'];
              }
              
              if ($pov['subtract'] && (!$pov['quantity'] || ($pov['quantity'] < $quantity))) {
                $stock = false;
              }
              
              $option_data[] = array(
                'product_option_id'       => $product_option_id,
                'product_option_value_id' => $product_option_value_id,
                'option_id'               => $options_types[$product_option_id]['option_id'],
                'option_value_id'         => $pov['option_value_id'],
                'name'                    => $options_types[$product_option_id]['name'],
                'option_value'            => $pov['name'],
                'value'                   => $pov['name'],
                'type'                    => $options_types[$product_option_id]['type'],
                'quantity'                => $pov['quantity'],
                'subtract'                => $pov['subtract'],
                //'price'                   => $pov['price'],
                'price'                   => ($pov['price_prefix']=='+' || $pov['price_prefix']=='-') ? $calc_multiplier*$pov['price'] : $pov['price'],
                'price_prefix'            => $pov['price_prefix'],
                'points'                  => $pov['points'],
                'points_prefix'           => $pov['points_prefix'],
                'weight'                  => $pov['weight'],
                'weight_prefix'           => $pov['weight_prefix']
              );
            }
          }
        }
      } elseif ( in_array($options_types[$product_option_id]['type'], array('text','textarea','file','date','datetime','time') ) ) {
        
        if ($get_full_data) {
        
          $option_data[] = array(
            'product_option_id'       => $product_option_id,
            'product_option_value_id' => '',
            'option_id'               => $options_types[$product_option_id]['option_id'],
            'option_value_id'         => '',
            'name'                    => $options_types[$product_option_id]['name'],
            'value'                   => $option_value,
            'type'                    => $options_types[$product_option_id]['type'],
            'quantity'                => '',
            'subtract'                => '',
            'price'                   => '',
            'price_prefix'            => '',
            'points'                  => '',
            'points_prefix'           => '',								
            'weight'                  => '',
            'weight_prefix'           => ''
          );
          
        }
       
      // Product Size Option
      } elseif ($options_types[$product_option_id]['type'] == 'size' && $installedPSO) {
        
        $has_size_option = true;
        $width = $option_value['width'];
        $height = $option_value['height'];
        
        $extra_price = $width * $height * $options_types[$product_option_id]['cost_per_square'];
        
        if($extra_price < $options_types[$product_option_id]['min_price'])
          $extra_price = $options_types[$product_option_id]['min_price'];
        
        $option_price += $extra_price;
        
        if ($this->config->has('pso_dimension_order'))
          $pso_dimension_order = $this->config->get('pso_dimension_order');
        else
          $pso_dimension_order = 0; //default: height then width
        
        $option_data[] = array(
          'product_option_id'       => $product_option_id,
          'product_option_value_id' => '',
          'option_id'               => $options_types[$product_option_id]['option_id'],
          'option_value_id'         => '',
          'name'                    => $options_types[$product_option_id]['name'],
          'value'           		  => $pso_dimension_order == 1 ? ($width . 'x' . $height) : ($height . 'x' . $width),
          'type'                    => $options_types[$product_option_id]['type'],
          'quantity'                => '',
          'subtract'                => '',
          'price'                   => $extra_price,
          'price_prefix'            => '',
          'points'                  => '',
          'points_prefix'           => '',								
          'weight'                  => '',
          'weight_prefix'           => ''
        );
        
      }
      
    }
    
    return $price_rewrited;
    
  }

  
  // compatibility
  // Custom Price Product - Customer can enter custom price for products flagged as such
  private function getCustomPrice($product, $options) {
  
    $price = $product['price'];
    if (strtolower($product['sku']) == 'custom' || strtolower($product['location']) == 'custom' || strtolower($product['upc']) == 'custom') {
      if ($options) {
        $pids = array_keys($options);
        
        if ($pids) {
          foreach ($pids as &$pid) {
            $pid = (int)$pid;
          }
          unset($pid);
          
          $query = $this->db->query(" SELECT PO.product_option_id, OD.name
                                      FROM  " . DB_PREFIX . "product_option PO
                                          , " . DB_PREFIX . "option_description OD
                                      WHERE PO.product_option_id IN (" . implode(',',$pids) . ")
                                        AND PO.option_id = OD.option_id
                                        AND OD.language_id = '" . (int)$this->config->get('config_language_id') . "'
                                      ");
          $po_names = array();
          foreach ($query->rows as $row) {
            $po_names[$row['product_option_id']] = $row['name'];
          }
        
          foreach ($options as $product_option_id => $option_value) {
            if ( isset($po_names[$product_option_id]) && strpos($po_names[$product_option_id], '**') !== false ) {
              $price = (float)$option_value;
              break;
            }
          }
        }
      }
    }
    return $price;
  }

  // PARAMS:
  // $product_id,
  // $current_quantity ( use 0 for cart )
  // $options = array( $product_option_id => $product_option_value_id )
  // $recurring_id
  // RESULTS:
  // &$prices=array(), &$product_data=array(), &$option_data=array()
  // if $current_quantity < 0 - cart call with current cart quantity
  public function getProductPrice($product_id, $current_quantity=1, $options=array(), $recurring_id=0, &$prices=array(), &$product_data=array(), &$option_data=array(), $multiplied_price=false, $use_cart_cache=false, $use_price_cache=false ) {
    
    // <0 - cart call
    if ($current_quantity==0) {
      $current_quantity = 1;
    }
    
    $cache_price_key = md5( $product_id.'_'.$current_quantity.'_'.serialize($options).'_'.$recurring_id );
    if ( $use_price_cache && isset($this->cache_price[$cache_price_key]) ) {
      $prices       = $this->cache_price[$cache_price_key]['prices'];
      $product_data = $this->cache_price[$cache_price_key]['product_data'];
      $option_data  = $this->cache_price[$cache_price_key]['option_data'];
    } else {
    
      $lp_settings = $this->config->get('liveprice_settings');
      
      if (isset($lp_settings['discount_quantity']) && $lp_settings['discount_quantity']==2) {
        
        if ( !$this->model_module_related_options ) {
          $this->load->model('module/related_options');
        }
        //$this->load->model('module/related_options');
        $ro_installed = $this->model_module_related_options->installed();
        if ($ro_installed) {
          if ( method_exists( $this->model_module_related_options, 'get_related_options_set_by_poids' ) ) { // related options
            $ro_price_data = $this->model_module_related_options->get_related_options_set_by_poids($product_id, $options, true);
          } elseif ( method_exists( $this->model_module_related_options, 'get_related_options_sets_by_poids' ) ) { // Related Options PRO
            $ro_price_data = $this->model_module_related_options->get_related_options_sets_by_poids($product_id, $options, true);
            //$ro_price_data = $this->model_module_related_options->get_related_options_sets_by_poids($product_id, $options);
          }
        }
      }
      
      $options = $this->arrayDeleteEmpty($options);
      
      $product_data = array();
      $option_data = array();
      $prices = array(  // without taxes
                        'product_price' => 0            // product price
                      , 'price_old' => 0                // product price with discount, but without special
                      , 'price_old_opt' => 0            // product price with discount, but without special, and with options
                      , 'special' => 0                  // product special price
                      , 'special_opt' => 0              // product special price with options
                      , 'price' => 0                    // product price with discount and special (special ignore discount)
                      , 'price_opt' => 0                // product price with discount and special (special ignore discount) with options
                      , 'option_price' => 0             // option price modificator
                      , 'option_price_special' => 0     // option price modificator for specials
                      //, 'discounts' => array()
                      
                        // with taxes and formatted
                      , 'f_product_price' => 0            // product price
                      , 'f_price_old' => 0                // product price with discount, but without special
                      , 'f_price_old_opt' => 0                // product price with discount, but without special
                      , 'f_special' => 0                  // product special price
                      , 'f_special_opt' => 0                  // product special price
                      , 'f_price' => 0                    // product price with discount and special (special ignore discount)
                      , 'f_price_opt' => 0                    // product price with discount and special (special ignore discount)
                      , 'f_option_price' => 0             // option price modificator
                      //, 'f_discounts' => array()
                      
                      
                      // without taxes and formatted
                      , 'f_product_price_notax' => 0            // product price
                      , 'f_price_old_notax' => 0                // product price with discount, but without special
                      , 'f_price_old_opt_notax' => 0                // product price with discount, but without special
                      , 'f_special_notax' => 0                  // product special price
                      , 'f_special_opt_notax' => 0                  // product special price
                      , 'f_price_notax' => 0                    // product price with discount and special (special ignore discount)
                      , 'f_price_opt_notax' => 0                    // product price with discount and special (special ignore discount)
                      , 'f_option_price_notax' => 0             // option price modificator
                      //, 'f_discounts_notax' => array()
                      
                      , 'config_tax' => $this->config->get('config_tax')
                      , 'points' => 0
                      
                      );
      
      
      $quantity = MAX($current_quantity,0); // for cart call, total quantity (for disconts), doesn't include current cart row quantity (to not include it twice)
      
      if ( $current_quantity<0 || !isset($lp_settings['ignore_cart']) || !$lp_settings['ignore_cart'] ) {
        
        if ( !$this->cache_cart || !$use_cart_cache ) {
          
          if ( VERSION >= '2.1.0.0' ) {
            
            $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
            foreach ($cart_query->rows as $cart_product) {
              $cart_product['cart_quantity'] = $cart_product['quantity'];
              $cart_product['option'] = (array)json_decode($cart_product['option']);
              $this->cache_cart[] = $cart_product;
            }
            
          } else { // 2.0.X 
            foreach ($this->session->data['cart'] as $key => $cart_quantity) {
              $cart_product = unserialize(base64_decode($key));
              $cart_product['cart_quantity'] = $cart_quantity;
              $this->cache_cart[] = $cart_product;
            }
          }
        }
        
        //foreach ($this->session->data['cart'] as $key => $cart_quantity) {
        //    $cart_product = unserialize(base64_decode($key));
        foreach ($this->cache_cart as &$cart_product) {
          
          $cart_product_id = $cart_product['product_id'];
          $cart_quantity = $cart_product['cart_quantity'];
          
          if ($cart_product_id == $product_id) {
    
            // Options
            if (!empty($cart_product['option'])) {
              $cart_options = $cart_product['option'];
            } else {
              $cart_options = array();
            }
            
            // Profile
            if (!empty($product['recurring_id'])) {
              $recurring_id = $product['recurring_id'];
            } else {
              $recurring_id = 0;
            }
          
            if ( isset($lp_settings['discount_quantity']) && $lp_settings['discount_quantity']==1 ) { // by options
              
              if ($options == $cart_options) {
                $quantity = $quantity + $cart_quantity;
              }
              
            } elseif ( isset($lp_settings['discount_quantity']) && $lp_settings['discount_quantity']==2 ) { // by related options combination
              
              if ( isset($ro_price_data) && $ro_price_data ) {
                
                if ( method_exists( $this->model_module_related_options, 'get_related_options_set_by_poids' ) ) { // related options
                  if ($use_cart_cache && isset($cart_product['ro_price_data_cart'])) {
                    $ro_price_data_cart = $cart_product['ro_price_data_cart'];
                  } else {
                    $ro_price_data_cart = $this->model_module_related_options->get_related_options_set_by_poids($product_id, $cart_options, true);
                    $cart_product['ro_price_data_cart'] = $ro_price_data_cart;
                  }
                  //$ro_price_data_cart = $this->model_module_related_options->get_related_options_set_by_poids($product_id, $cart_options, true);
                  
                  if ( $ro_price_data_cart && $ro_price_data_cart['relatedoptions_id'] == $ro_price_data['relatedoptions_id'] ) {
                    $quantity = $quantity + $cart_quantity;
                  }
                  
                } elseif ( method_exists( $this->model_module_related_options, 'get_related_options_sets_by_poids' ) ) { // Related Options PRO
                  
                  if ($use_cart_cache && isset($cart_product['ro_price_data_cart'])) {
                    $ro_price_data_cart = $cart_product['ro_price_data_cart'];
                  } else {
                    $ro_price_data_cart = $this->model_module_related_options->get_related_options_sets_by_poids($product_id, $cart_options, true);
                    //$ro_price_data_cart = $this->model_module_related_options->get_related_options_sets_by_poids($product_id, $cart_options);
                    $cart_product['ro_price_data_cart'] = $ro_price_data_cart;
                  }
                  
                  //$ro_price_data_cart = $this->model_module_related_options->get_related_options_sets_by_poids($product_id, $cart_options);
                  
                  if ( $ro_price_data_cart ==  $ro_price_data) {
                    $quantity = $quantity + $cart_quantity;
                  }
                  
                }
                
              } elseif ($options == $cart_options) {
                $quantity = $quantity + $cart_quantity;
              }
              
            } else { // by product
              $quantity = $quantity + $cart_quantity;
            }
          } 
        }
        unset($cart_product);
      }
      
      // $quantity  - full quantity for discount ($current_quantity + cart quantity (sometimes, depends on settings) )
      // $current_quantity  - quantity for current product calc
      
      $quantity = max($quantity, 1);
      $real_quantity = max(1, abs($current_quantity));
      
      
      $stock = true;
  
      $product_query = $this->db->query(" SELECT *
                                          FROM " . DB_PREFIX . "product p
                                            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
                                          WHERE p.product_id = '" . (int)$product_id . "'
                                            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                                            AND p.date_available <= NOW()
                                            AND p.status = '1'");
      
      //print 'pid='.$product_id.'<br>/n';
      
      if ($product_query->num_rows) {
        
        $product_query->row['price'] = $this->getCustomPrice($product_query->row, $options);
        
        //print 'pid='.$product_id.'<br>/n';
        
        //print_r($options);
        
        $option_price = 0;
        $option_points = 0;
        $option_weight = 0;
        
        // << соберем все данным по опциями в массивы для повторного использования
        
        $options_types = array();
        $options_values = array();
        
        $product_option_ids = array();
        $product_option_value_ids = array();
        foreach ($options as $product_option_id => $option_value) {
          if (!in_array($product_option_id, $product_option_ids)) $product_option_ids[] = (int)$product_option_id;
        }
        
        if ( count($product_option_ids) != 0 ) {
          
          $options_query = $this->db->query(" SELECT po.*, od.name, o.* 
                                              FROM " . DB_PREFIX . "product_option po
                                                LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id)
                                                LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id)
                                              WHERE po.product_option_id IN (" . implode(",", $product_option_ids) . ")
                                                AND po.product_id = '" . (int)$product_id . "'
                                                AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
          foreach ($options_query->rows as $row) {
            $options_types[$row['product_option_id']] = $row;
          }
        
          foreach ($options as $product_option_id => $option_value) {
            
            if (!isset($options_types[$product_option_id])) continue;
            
            if ( in_array($options_types[$product_option_id]['type'], $this->options_selects) ) {
              if (!in_array((int)$option_value, $product_option_value_ids)) {
                $product_option_value_ids[] = (int)$option_value;
              }
            } elseif ($options_types[$product_option_id]['type'] == 'checkbox' && is_array($option_value)) {
              foreach ($option_value as $product_option_value_id) {
                if (!in_array((int)$product_option_value_id, $product_option_value_ids)) {
                  $product_option_value_ids[] = (int)$product_option_value_id;
                }
              }
            }
          }
          
          if ( count($product_option_ids) != 0 && count($product_option_value_ids) != 0 ) { // в $product_option_ids могут быть опции не подходящих типов
             $option_value_query = $this->db->query("SELECT  pov.*, ovd.name
                                                    FROM " . DB_PREFIX . "product_option_value pov
                                                      LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id)
                                                      LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id)
                                                    WHERE pov.product_option_value_id IN (" . implode(",", $product_option_value_ids) . ")
                                                      AND pov.product_option_id IN (" . implode(",", $product_option_ids) . ")
                                                      AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
            /*
            $option_value_query = $this->db->query("SELECT  pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.product_option_id
                                                          , pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, pov.product_option_value_id
                                                    FROM " . DB_PREFIX . "product_option_value pov
                                                      LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id)
                                                      LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id)
                                                    WHERE pov.product_option_value_id IN (" . implode(",", $product_option_value_ids) . ")
                                                      AND pov.product_option_id IN (" . implode(",", $product_option_ids) . ")
                                                      AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
            */
            foreach ($option_value_query->rows as $row) {
              if (!isset($options_values[$row['product_option_id']])) {
                $options_values[$row['product_option_id']] = array();
              }
              $options_values[$row['product_option_id']][$row['product_option_value_id']] = $row;
            }
          }
          
          // Product Size Option
          if ($this->installedProductSizeOption()) {
            
            if ( $this->ProductHasSizeOption($options, $options_types) && $this->config->has('pso_exclude_product_price_in_total') && $this->config->get('pso_exclude_product_price_in_total') ) {
              $product_query->row['price'] = 0;
            }
            /*
            $pso_exclude_product_price_in_total = 0; //default: false
            if ($has_size_option && $this->config->has('pso_exclude_product_price_in_total'))
            { 
              $pso_exclude_product_price_in_total = $this->config->get('pso_exclude_product_price_in_total');
            }
            */
          }
          
        }
        // >> соберем все данным по опциями в массивы для повторного использования
        
        $price_rewrited = $this->calculateOptionPrice($option_price, (int)$product_id, $product_query->row['price'], $product_query->row['points'], $options, $options_types, $options_values, true, $recurring_id, $quantity, $current_quantity, $option_data, $option_points, $option_weight, $stock);
        
        
        
        $prices['option_price'] = $option_price;
        
        //if ($this->customer->isLogged()) {
        //  $customer_group_id = $this->customer->getCustomerGroupId();
        //} else {
          $customer_group_id = $this->config->get('config_customer_group_id');
        //}
        
        $price = $product_query->row['price'];
        
        $prices['product_price'] = $price;
        
        // Product Discounts
        $discount_quantity = $quantity;
        /*
        foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
          $product_2 = explode(':', $key_2);
          
          if ($product_2[0] == $product_id) {
            $discount_quantity += $quantity_2;
          }
        }
        */
        
        $product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount
                                                    WHERE product_id = '" . (int)$product_id . "'
                                                      AND customer_group_id = '" . (int)$customer_group_id . "'
                                                      AND quantity <= '" . (int)$discount_quantity . "'
                                                      AND quantity > 0
                                                      AND ((date_start = '0000-00-00' OR date_start < NOW())
                                                      AND (date_end = '0000-00-00' OR date_end > NOW()))
                                                    ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
        
        if ($product_discount_query->num_rows) {
          $price = $product_discount_query->row['price'];
          
          // new options price prefixes can give another option_price value for discount price, so - recalc 
          $price_rewrited = $this->calculateOptionPrice($option_price, (int)$product_id, $price, 0, $options, $options_types, $options_values, false, 0, $quantity, $current_quantity);
          $prices['option_price'] = $option_price;
        }
        
        // Product Specials
        $product_special_query = $this->db->query(" SELECT price FROM " . DB_PREFIX . "product_special
                                                    WHERE product_id = '" . (int)$product_id . "'
                                                      AND customer_group_id = '" . (int)$customer_group_id . "'
                                                      AND ((date_start = '0000-00-00' OR date_start < NOW())
                                                      AND (date_end = '0000-00-00' OR date_end > NOW()))
                                                    ORDER BY priority ASC, price ASC LIMIT 1");
      
        $price_old = $price;
        $prices['price_old'] = $price_old;
      
        if ($product_special_query->num_rows) {
          
          $price = $product_special_query->row['price'];
          
          // new options price prefixes can give another option_price value for special price, so - recalc
          $option_price_special = 0;
          $price_rewrited = $this->calculateOptionPrice($option_price_special, (int)$product_id, $price, 0, $options, $options_types, $options_values, false, 0, $quantity, $current_quantity);
          $option_price = $option_price_special;
          $prices['option_price'] = $option_price;
          $prices['option_price_special'] = $option_price_special;
          
          $prices['special'] = $product_special_query->row['price'];
        }
        $prices['price'] = $price;
        
        // Reward Points
        $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward
                                                  WHERE product_id = '" . (int)$product_id . "'
                                                    AND customer_group_id = '" . (int)$customer_group_id . "'");
        
        if ($product_reward_query->num_rows) {	
          $reward = $product_reward_query->row['points'];
        } else {
          $reward = 0;
        }
        
        // Downloads		
        $download_data = array();     		
        
        $download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d
                                              LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id)
                                              LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id)
                                            WHERE p2d.product_id = '" . (int)$product_id . "'
                                              AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
      
        foreach ($download_query->rows as $download) {
          $download_data[] = array(
            'download_id' => $download['download_id'],
            'name'        => $download['name'],
            'filename'    => $download['filename'],
            'mask'        => $download['mask'] //,
            //'remaining'   => $download['remaining']
          );
        }
        
        // Stock
        if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
          $stock = false;
        }
        
        $recurring_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int)$product_query->row['product_id'] . " JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`recurring_id` = `p`.`recurring_id` AND `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'));
  
        if ($recurring_query->num_rows) {
          $recurring = array(
            'recurring_id'    => $recurring_id,
            'name'            => $recurring_query->row['name'],
            'frequency'       => $recurring_query->row['frequency'],
            'price'           => $recurring_query->row['price'],
            'cycle'           => $recurring_query->row['cycle'],
            'duration'        => $recurring_query->row['duration'],
            'trial'           => $recurring_query->row['trial_status'],
            'trial_frequency' => $recurring_query->row['trial_frequency'],
            'trial_price'     => $recurring_query->row['trial_price'],
            'trial_cycle'     => $recurring_query->row['trial_cycle'],
            'trial_duration'  => $recurring_query->row['trial_duration']
          );
        } else {
          $recurring = false;
        }
        
        // некоторая избыточность в подготовке данных
        $product_data = array(
          //'key'             => $key,
          'product_id'      => $product_query->row['product_id'],
          'name'            => $product_query->row['name'],
          'model'           => $product_query->row['model'],
          'shipping'        => $product_query->row['shipping'],
          'image'           => $product_query->row['image'],
          'option'          => $option_data,
          'download'        => $download_data,
          'quantity'        => $quantity,
          'minimum'         => $product_query->row['minimum'],
          'subtract'        => $product_query->row['subtract'],
          'stock'           => $stock,
          'price'           => ($price + $option_price),
          'total'           => ($price + $option_price) * $real_quantity,
          //'total'           => ($price + $option_price) * $quantity,
          'reward'          => $reward * $quantity,
          'points'          => ($product_query->row['points'] || $option_points!=0 ? ($product_query->row['points'] + $option_points) * $quantity : 0),
          //'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $quantity : 0),
          'tax_class_id'    => $product_query->row['tax_class_id'],
          'weight'          => ($product_query->row['weight'] + $option_weight) * $quantity,
          'weight_class_id' => $product_query->row['weight_class_id'],
          'length'          => $product_query->row['length'],
          'width'           => $product_query->row['width'],
          'height'          => $product_query->row['height'],
          'length_class_id' => $product_query->row['length_class_id'],
          'recurring'       => $recurring
        );
        
  
        //$prices['points']                 = $product_query->row['points'];
        
        $prices['price_old_opt']          = $prices['price_old'] + $prices['option_price'];
        $prices['special_opt']            = $prices['special'] + $prices['option_price_special'];
        // special options modificator if there's special, standard price modificator if there's no special
        $prices['price_opt']              = $prices['price'] + $option_price;
        //$prices['price_opt']              = $prices['price'] + $prices['option_price'];
        
        $this->cache_price[$cache_price_key] = array('prices'=>$prices, 'product_data'=>$product_data, 'option_data'=>$option_data);
      }
      
      
      
      $price_multiplier = 1;
      if ($multiplied_price && isset($lp_settings['multiplied_price']) && $lp_settings['multiplied_price']) {
        $price_multiplier = MAX(1, $current_quantity);
        //мультипликатор только для форматных цен и points
        /*
        $prices['product_price']        = $prices['product_price']*$current_quantity;
        $prices['price_old']            = $prices['price_old']*$current_quantity;
        $prices['price_old_opt']        = $prices['price_old_opt']*$current_quantity;
        $prices['special']              = $prices['special']*$current_quantity;
        $prices['special_opt']          = $prices['special_opt']*$current_quantity;
        $prices['price']                = $prices['price']*$current_quantity;
        $prices['price_opt']            = $prices['price_opt']*$current_quantity;
        $prices['option_price']         = $prices['option_price']*$current_quantity;
        $prices['option_price_special'] = $prices['option_price_special']*$current_quantity;
        */
      }
      
      $prices['points']                 = ($product_query->row['points'] || $option_points!=0 ? ($product_query->row['points'] + $option_points) * $price_multiplier : 0) ;
      //$prices['points']                 = ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $price_multiplier : 0);
      
      $prices['f_product_price_notax']  = $this->currency->format($prices['product_price']);
      $prices['f_price_old_notax']      = $this->currency->format($prices['price_old']);
      $prices['f_price_old_opt_notax']  = $this->currency->format($prices['price_old_opt']);
      $prices['f_special_notax']        = $this->currency->format($prices['special']);
      $prices['f_special_opt_notax']    = $this->currency->format($prices['special_opt']);
      //$prices['f_price_notax']          = $this->currency->format($prices['price']);
      //$prices['f_price_opt_notax']      = $this->currency->format($prices['price_opt']);
      $prices['f_option_price_notax']   = $this->currency->format($prices['option_price']);
      
      $prices['f_price_notax']          = $this->currency->format($price_multiplier*$prices['price']);
      $prices['f_price_opt_notax']      = $this->currency->format($price_multiplier*$prices['price_opt']);
      $prices['f_product_price']        = $this->currency->format($price_multiplier*$this->tax->calculate($prices['product_price'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));
      $prices['f_price_old']            = $this->currency->format($price_multiplier*$this->tax->calculate($prices['price_old'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));
      $prices['f_price_old_opt']        = $this->currency->format($price_multiplier*$this->tax->calculate($prices['price_old_opt'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));
      $prices['f_special']              = $this->currency->format($price_multiplier*$this->tax->calculate($prices['special'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));
      $prices['f_special_opt']          = $this->currency->format($price_multiplier*$this->tax->calculate($prices['special_opt'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));
      $prices['f_price']                = $this->currency->format($price_multiplier*$this->tax->calculate($prices['price'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));
      $prices['f_price_opt']            = $this->currency->format($price_multiplier*$this->tax->calculate($prices['price_opt'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));
      $prices['f_option_price']         = $this->currency->format($price_multiplier*$this->tax->calculate($prices['option_price'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));
      
      // required for html generation, placed here for better related options compatibility
      if ( !$this->model_catalog_product ) {
        $this->load->model('catalog/product');
      }
      //$this->load->model('catalog/product');
      $discounts = $this->model_catalog_product->getProductDiscounts($product_id);
      $prices['discounts'] = array(); 
      foreach ($discounts as $discount) {
        $option_price_discount = 0;
        $price_rewrited = $this->calculateOptionPrice($option_price_discount, (int)$product_id, $discount['price'], 0, $options, $options_types, $options_values, false, 0, $discount['quantity'], $discount['quantity']);
        $prices['discounts'][] = array(
          'quantity' => $discount['quantity'],
          'price'    => $this->currency->format($this->tax->calculate($discount['price']+$option_price_discount, $product_data['tax_class_id'], $this->config->get('config_tax')))
        );
      }
      
      
      return $price + $option_price;
    
    }

    return 0;
    
  }
  
  public function getProductPriceWithHtml($product_id, $current_quantity=0, $options=array(), &$prices=array(), &$product_data=array(), &$option_data=array(), $multiplied_price=false ) {
    
    $this->getProductPrice($product_id, $current_quantity, $options, 0, $prices, $product_data, $option_data, $multiplied_price );
    
    $simple_prices = array(   'price'       =>  $prices['f_price_old_opt']
                            , 'special'     =>  ($prices['special']?$prices['f_special_opt']:'')
                            , 'points'      =>  $prices['points']
                            , 'tax'         =>  ($prices['config_tax']?$prices['f_price_opt_notax']:$prices['config_tax'])
                            , 'discounts'   =>  $prices['discounts']
                            , 'points'      =>  $prices['points']
                            , 'reward'      =>  $product_data['reward']
                            , 'minimum'     =>  $product_data['minimum']
                            
                            , 'price_val'   =>  $prices['price_old_opt']
                            , 'special_val' =>  $prices['special']
                            
                            , 'product_id'  =>  $product_id
                            );
    
    $prices['htmls'] = $this->getPriceHtmls($simple_prices);
    $prices['ct'] = $this->config->get('config_template');
    
  }
  
  function getPriceHtmls($prices) {
    
    $this->language->load('product/product');
    $text_price         = $this->language->get('text_price');
    $text_tax           = $this->language->get('text_tax');
    $text_discount      = $this->language->get('text_discount');
    $text_points        = $this->language->get('text_points');
    $text_reward        = $this->language->get('text_reward');
    $text_stock         = $this->language->get('text_stock');
    $text_minimum       = sprintf($this->language->get('text_minimum'), $prices['minimum']);
    $text_manufacturer  = $this->language->get('text_manufacturer');
    
    $price = $prices['price'];
    $special = $prices['special'];
    $tax = $prices['tax'];
    $points = $prices['points'];
    $discounts = $prices['discounts'];
    $minimum = $prices['minimum'];
    
    $html = "";
    $html_d = "";
    $html1 = "";
    $html2 = "";
    
    if ($this->config->get('config_template') == 'monster') {
      
      
      $html .= '<p class="newprice">';
      if (!$special) {
        $html .= '<span class="price-new">'.$price.' TTC<br /></span>';
      } else {
        $html .= '<span class="price-old">'.$price.'</span>';
        $html .= '<span class="price-new">'.$special.'</span>';
      }
      if ($tax) {
        $html .= '<span class="price-tax">'.$tax.' HT</span>';
      }
      if ($points) { 
        $html .= '<span>'.$text_points.' '.$points.'</span>';
      }
      if ($discounts) {
        $html .= '<div class="discounts">';
        $html .= '</ul>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
      }
    
    } elseif ($this->config->get('config_template') == 'smarti' || $this->config->get('config_template') == 'beamstore') {
      
      if (!$special) {
        $html .= '<span class="price-new"><span itemprop="price">'.$price.'</span></span>';
      } else {
        $html .= '<span class="price-new"><span itemprop="price">'.$special.'</span></span> <span class="price-old">'.$price.'</span>';
      }
      $html .= '<br />';
      if ($tax) {
        $html .= '<span class="price-tax">'.$text_tax.' '.$tax.'</span><br />';
      }
      if ($points) {
        $html .= '<span class="reward"><small>'.$text_points.' '.$points.'</small></span><br />';
      }
      if ($discounts) {
        $html .= '<br />';
        $html .= '<div class="discount">';
        foreach ($discounts as $discount) {
          $html .= $discount['quantity'].$text_discount.$discount['price'].'<br />';
        }
        $html .= '</div>';
      }
      
    } elseif ($this->config->get('config_template') == 'pav_digitalstore') {  
      
        $html .= '<ul class="list-unstyled">';
        if (!$special) {
          $html .= '  <li class="price-gruop">';
          $html .= '      <span class="price-new"> '.$price.' </span>';
          $html .= '  </li>';
        } else {

          $html .= '  <li> <span class="price-old"> '.$special.' </span> <span style="text-decoration: line-through;">'. $price.'</span> </li>';
        }
        if ($tax) {
          $html .= '  <li class="price-tax">'.$text_tax.' '.$tax.'</li>';
        }

        if ($discounts) {
          $html .= '  <li>';
          $html .= '  </li>';
          $html .= '  <div class="discount">';
          foreach ($discounts as $discount) {
            $html .= '    <li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
          }
        }
        $html .= '</ul>';
      
      
    } elseif ($this->config->get('config_template') == 'pav_styleshop') {
      
      $html .= '<div class="price-gruop">';
      $html .= '<span class="text-price">'.$price.'</span>';
      if (!$special) {
        $html .= $price;
      } else {
        $html .= '<span class="price-old">'.$price.'</span>';
        $html .= '<span class="price-new">'.$special.'</span>';
      }
      $html .= '</div>';
      $html .= '<div class="other-price">';
      if ($tax) {
        $html .= '<span class="price-tax">'.$text_tax.' '.$tax.'</span><br/>';
      }
      if ($points) {
        $html .= '<span class="reward"><small>'.$text_points.' '.$points.'</small></span>';
      }
      $html .= '</div>';
      if ($discounts) {
        $html .= '<div class="discount">';
        $html .= '<ul>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].''.$text_discount.''.$discount['price'].'</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
      }
      
    } elseif ($this->config->get('config_template') == 'pav_dress_store') {
      
      if (!$special) {
        $html .= '<li class="price-gruop">';
        $html .= '<span class="text-price"> '.$price.' </span>';
        $html .= '</li>';
      } else {

        $html .= '<li> <span class="price-old">'.$price.'</span><span class="price-new"> '.$special.' </span></li>';
      }
      if ($tax) {
        $html .= '<li class="price-tax">'.$text_tax.' '.$tax.'</li>';
      }

      if ($discounts) {
        $html .= '<li>';
        $html .= '</li>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
      }
    
    } elseif ($this->config->get('config_template') == 'pav_fashion') {
      
      if ( !$this->model_catalog_product ) {
        $this->load->model('catalog/product');
      }
      //$this->load->model('catalog/product');
      $product_info = $this->model_catalog_product->getProduct($prices['product_id']);
      $manufacturer = $product_info['manufacturer'];
      $manufacturers = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
      $reward = $product_info['reward'];
      
      if ($product_info['quantity'] <= 0) {
				$stock = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$stock = $product_info['quantity'];
			} else {
				$stock = $this->language->get('text_instock');
			}
      
      
      $html1 .= '<ul class="list-unstyled">';
      if (!$special) {
                            
        $html1 .= '<li class="price-gruop">';
        $html1 .= '<span class="text-price"> '.$price.' </span>';
        $html1 .= '</li>';
      } else {
  
        $html1 .= '<li> <span class="text-price"> '.$special.' </span> <span style="text-decoration: line-through;">'. $price.'</span> </li>';
      }
          /*
          <!--<?php if ($tax) { ?>
              <li class="other-price"><?php echo $text_tax; ?> <?php echo $tax; ?></li>
          <?php } ?>-->
          */
  
      if ($discounts) {
        $html1 .= '<li>';
        $html1 .= '</li>';
        foreach ($discounts as $discount) {
          $html1 .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
      }
      $html1 .= '</ul>';
      
      
      
      $html2 .= '<ul class="list-unstyled description">';
      if ($manufacturer) {
        $html2 .= '<li><b>'.$text_manufacturer.'</b> <a href="'.$manufacturers.'">'.$manufacturer.'</a></li>';
      }
      /*
      <!--<li><b><?php echo $text_model; ?></b> <?php echo $model; ?></li>-->
      */
      $html2 .= '<br>';
      if ($reward) {
        $html2 .= '<li><b>'.$text_reward.'</b> '.$reward.'</li>';
      }
      if ($points) {
        $html2 .= '<li><b>'.$text_points.'</b> '.$points.'</li>';
      }
      $html2 .= '<li><b class="availability">'.$text_stock.'</b> '.$stock.'</li>';
	    $html2 .= '</ul>';
      
    
    } elseif ($this->config->get('config_template') == 'theme516') { // Car Audio Video Equipment template
      
      $html .= '<span class="price-new">'.$special.'</span>';
      if (!$special) {
        $html .= '<span class="price-new">'.$price.'</span>';
      } else {
        $html .= '<span class="price-old">'.$price.'</span>';
      }
      if ($tax) {
        $html .= '<span class="tax">'.$text_tax.' '.$tax.'</span>';
      } 
      $html .= '<div class="reward-block">';
      if ($points) {
        $html .= '<span	class="reward">'.$text_points.' '.$points.'</span>';
      }
      if ($discounts) {
        foreach ($discounts as $discount) {
          $html .= '<span>'.$discount['quantity'].''.$text_discount.''.$discount['price'].'</span>';
        }
      }
      $html .= '</div>';
      
    } elseif ($this->config->get('config_template') == 'theme531') { // Stationery
      
      $html .= '<span class="price-new">'.$special.'</span>';
      if (!$special) {
        $html .= '<span class="price-new">'.$price.'</span>';
      } else {
        $html .= '<span class="price-old">'.$price.'</span>';
      }
      if ($tax) {
        $html .= '<span class="tax">'.$text_tax.' '.$tax.'</span>';
      } 
      $html .= '<div class="reward-block">';
      if ($points) {
        $html .= '<span	class="reward">'.$text_points.' '.$points.'</span>';
      }
      if ($discounts) {
        foreach ($discounts as $discount) {
          $html .= '<span>'.$discount['quantity'].''.$text_discount.''.$discount['price'].'</span>';
        }
      }
      $html .= '</div>';  
      
    } elseif ($this->config->get('config_template') == 'cosyone') { 
      
      $cosyone_product_yousave = $this->config->get('cosyone_product_yousave');
      
      $html1 .='<span class="txt_price">'.$text_price.'</span>';
      if (!$special) {
        $html1 .=' <span itemprop="price">'.$price.'</span>';
      } else {
        if (!$cosyone_product_yousave) {
          $html1 .=' <span class="price-old">'.$price.'</span> <span class="price-new" itemprop="price">'.$special.'</span>';
        }
      }
      $html1 .='<br />';
      if ($tax) {
        $html1 .='<span class="price-tax">'.$text_tax.' '.$tax.'</span><br />';
      }
      
      if ($minimum > 1) {
        $html2 .='<div class="minimum">'.$text_minimum.'</div>';
      }
      if ($price) {
        if ($points) {
          $html2 .='<div class="reward">'.$text_points.' '.$points.'</div>';
        }
        if ($discounts) {
          $html2 .='<div class="discount">';
          foreach ($discounts as $discount) {
            $html2 .='<span>'.$discount['quantity'].$text_discount.$discount['price'].'</span>';
          }
          $html2 .='</div>';
        }
      }
      
    } elseif ($this->config->get('config_template') == 'OPC080183') { // theme Optimal by TemplateMela
      
      //$this->load->model('catalog/product');
      //$product_info = $this->model_catalog_product->getProduct($prices['product_id']);
      
      //$manufacturer = $product_info['manufacturer'];
			//$manufacturers = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
      
      //$html1 .= '<ul class="list-unstyled prod-price">';
      if (!$special) {
        $html1 .= '<li>';
        $html1 .= '<h3>'.$price.'</h3>';
        $html1 .= '</li>';
      } else {
        $html1 .= '<li><span style="text-decoration: line-through;">'.$price.'</span>';
        $html1 .= '<h3>'.$special.'</h3>';
			  $html1 .= '</li>';
      }
      //if ($manufacturer) {
      //  $html1 .= '<li>'.$text_manufacturer.' <a href="'.$manufacturers.'">'.$manufacturer.'</a></li>';
      //}
      //$html1 .= '<li>'.$text_model.' '.$model.'</li>';
      //if ($reward) {
      //  $html1 .= '<li>'.$text_reward.' '.$reward.'</li>';
      //}
      //$html1 .= '<li>'.$text_stock.' '.$stock.'</li>';
      //$html1 .= '</ul>';
      if ($price) {
        $html2 .= '<ul class="list-unstyled">';
        if ($tax) {
          $html2 .= '<li>'.$text_tax.' '.$tax.'</li>';
        }
        if ($points) {
          $html2 .= '<li>'.$text_points.' '.$points.'</li>';
        }
        if ($discounts) {
          foreach ($discounts as $discount) {
            $html2 .= '<li>'.$discount['quantity'].''.$text_discount.''.$discount['price'].'</li>';
          }
        }
        $html2 .= '</ul>';
      }
      
    } elseif ($this->config->get('config_template') == 'sellegance') { 
      
      if (!$special) {
				$html .= '<span class="price-normal">'.$price.'</span>';
			} else {
				$html .= '<span class="price-old">'.$price.'</span> <span class="price-new">'.$special.'</span>';
			}

			if ($tax) {
				$html .= '<div class="price-tax">'.$text_tax.' '.$tax.'</div>';
			}

			if ($points) {
				$html .= '<div class="reward"><small>'.$text_points.' '.$points.'</small></div>';
			}

			if ($discounts) {
				$html .= '<div class="discount">';
				$html .= '<ul>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].''.$text_discount.''.$discount['price'].'</li>';
        }
				$html .= '</ul>';
				$html .= '</div>';
			}
			$html .= '<div class="stock">%stock%</div>';
			$html .= '</div>';
      
    } elseif ($this->config->get('config_template') == 'glade') {
      
      if (!$special) {
        $html .= '<span class="price-normal">'.$price.'</span>';
      } else { 
        $html .= '<span class="price-old">'.$price.'</span> <span class="price-new">'.$special.'</span>';
      } 

      if ($tax) { 
        $html .= '<div class="price-tax">'.$text_tax.' '.$tax.'</div>';
      } 

      if ($points) { 
        $html .= '<div class="reward"><small>'.$text_points.' '.$points.'</small></div>';
      } 

      if ($discounts) { 
      $html .= '<div class="discount">';
        $html .= '<ul>';
        foreach ($discounts as $discount) { 
          $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        } 
        $html .= '</ul>';
        $html .= '</div>';
      }
      
    } elseif ($this->config->get('config_template') == 'lamby') {
      
      if (!$special) {
        $html .= '<span class="price-new price">'.$price.'</span>';
      } else {
        $html .= '<span class="price-old price">'.$price.'</span>';
        $html .= '<span class="price-new price">'.$special.'</span>';
      }
      
      if ($points) {
        $html .= '<li>'.$text_points.' '.$points.'</li>';
      }
      
      if ($discounts) {
        $html .= '<li>';
        $html .= '<hr>';
        $html .= '</li>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].''.$text_discount.''.$discount['price'].'</li>';
        }
      }
    
    } elseif ($this->config->get('config_template') == 'journal2') {
      
      $html .= '<meta itemprop="priceCurrency" content="'.$this->journal2->settings->get('product_price_currency').'" />';
      if ($this->journal2->settings->get('product_in_stock') === 'yes') {
        $html .= '<link itemprop="availability"  href="http://schema.org/InStock" />';
      }
      if (!$special) {
        $html .= '<li class="product-price" itemprop="price">'.$price.'</li>';
      } else {
        $html .= '<li class="price-old">'.$price.'</li>';
        $html .= '<li class="price-new" itemprop="price">'.$special.'</li>';
      }
      if ($tax) {
        $html .= '<li class="price-tax">'.$text_tax.' '.$tax.'</li>';
      }
      if ($points) {
        $html .= '<li class="reward"><small>'.$text_points.' '.$points.'</small></li>';
      }
      if ($discounts) {
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].''.$text_discount.''.$discount['price'].'</li>';
        }
      }
     
    } elseif ($this->config->get('config_template') == 'lexus_superstore_first' || $this->config->get('config_template') == 'lexus_superstore') {
      
      $html .= '<ul class="list-unstyled">';
      if (!$special) {
        $html .= '<li class="price-gruop">';
        $html .= '<span class="text-price"> '.$price.' </span>';
        $html .= '</li>';
      } else {
        $html .= '<li> <span class="text-price"> '.$special.' </span> <span style="text-decoration: line-through;">'.$price.'</span> </li>';
      }
      if ($tax) {
        $html .= '<li class="other-price">'.$text_tax.' '.$tax.'</li>';
      }

      if ($discounts) {
        $html .= '<li>';
        $html .= '</li>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
      }
      $html .= '</ul>';
      
    } elseif ($this->config->get('config_template') == 'optimum' ) {
      
      $html .= '<ul class="list-unstyled">';
      if (!$special) {
        $html .= '<li class="uk-margin-top">';
        $html .= '<h2><span class="ot-product-price">'.$price.'</span></h2>';
        $html .= '</li>';
      } else {
        $html .= '<li><span style="text-decoration: line-through;">'.$price.'</span></li>';
        $html .= '<li>';
        $html .= '<h2>'.$special.'</h2>';
        $html .= '</li>';
      }
      if ($tax) {
        $html .= '<li>'.$text_tax.' '.$tax.'</li>';
      }
      if ($points) {
        $html .= '<li>'.$text_points.' '.$points.'</li>';
      }
      if ($discounts) {
        $html .= '<li>';
        $html .= '<hr>';
        $html .= '</li>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
      }
      $html .= '</ul>';
      
    } elseif ($this->config->get('config_template') == 'fortuna' ) {
      
      $html .= '<div class="price">';

        if (!$special) {
          $html .= '<span class="price-normal">'.$price.'</span>';
        } else {
          $html .= '<span class="price-old">'.$price.'</span> <span class="price-new">'.$special.'</span>';
        }

        if ($tax) {
          $html .= '<div class="price-tax">'.$text_tax.' '.$tax.'</div>';
        }

        if ($points) {
          $html .= '<div class="reward"><small>'.$text_points.' '.$points.'</small></div>';
        }

        if ($discounts) {
        $html .= '<div class="discount">';
          $html .= '<ul>';
          foreach ($discounts as $discount) {
            $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
          }
          $html .= '</ul>';
        $html .= '</div>';
        }

      $html .= '</div>';
       
    } elseif ($this->config->get('config_template') == 'bt_comohos' ) { // Comohos theme
      
      $html .= '<div class="price_info">';
			if (!$special) {
			  $html .= '<span class="price">'.$price.'</span>';
			} else {
			  $html .= '<span class="price-old">'.$price.'</span>';
			  $html .= '<span class="price-new">'.$special.'</span>';
			}
			$html .= '<br>';
			if ($tax) {
			  $html .= '<span class="price-tax">'.$text_tax.' '.$tax.'</span>';
			}
			$html .= '<br>';
			if ($points) {
        $html .= '<span class="reward"><small>'.$text_points.' '.$points.'</small></span>';
			}
			if ($discounts) {
        $html .= '<div class="discount">';
			  foreach ($discounts as $discount) {
          $html .= $discount['quantity'].$text_discount.$discount['price'];
          $html .= '<br>';
        }
        $html .= '</div>';
			}
		  $html .= '</div>';
       
       
    } elseif ($this->config->get('config_template') == 'rgen-opencart') {
      
      // for product_buyinginfo1.tpl
        
        $html1 .= '<div class="price">';
        /* Price */
        if (!$special) {
          $html1 .= '<span class="price-new">'.$price.'</span>';
        } else {
          $html1 .= '<span class="price-old">'.$price.'</span>';
          $html1 .= '<span class="price-new price-spl">'.$special.'</span>';
        }
        
        /* TAX */
        if ($tax) {
          $html1 .= '<span class="price-tax">'.$text_tax.' '.$tax.'</span>';
        }
        $html1 .= '</div>';
        
        /* Points */
        if ($points) {
          $html1 .= '<span class="reward">'.$text_points.' '.$points.'</span>';
        }
    
        
        /* Discount */
        if ($discounts) {
          $html1 .= '<ul class="discount ul-reset">';
          foreach ($discounts as $discount) {
            $html1 .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
          }
          $html1 .= '</ul>';
        }

        
      // for product_buyinginfo.tpl
      
        $html .= '<div class="price vm">';
        $html .= '<b class="vm-item">'; //may be nonstandard for theme
        /* Price */
        if (!$special) {
          $html .= '<span class="price-new">'.$price.'</span>';
        } else {
          $html .= '<span class="price-old">'.$price.'</span>';
          $html .= '<span class="price-new price-spl">'.$special.'</span>';
        }
        /* TAX */
        if ($tax) {
          $html .= '<span class="price-tax">'.$text_tax.' '.$tax.'</span>';
        }	
        $html .= '</b>';
        $html .= '</div>';
         
        /* Points */
        if ($points) {
          $html .= '<span class="reward">'.$text_points.' '.$points.'</span>';
        }
  
        /* Discount */
        if ($discounts) {
          $html .= '<ul class="discount ul-reset">';
          foreach ($discounts as $discount) {
            $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
          }
          $html .= '</ul>';
        }
      
      
    } elseif ($this->config->get('config_template') == 'ntl' || $this->config->get('config_template') == 'bt_claudine' ) { // Claudine - bt_claudine
      
      $html .= '<div class="price_info">';
			if (!$special) {
				$html .= '<span>'.$price.'</span>';
			} else {
				$html .= '<span class="price-new">'.$special.'</span>';
				$html .= '<span class="price-old">'.$price.'</span>';
			}
			if ($tax) {
				$html .= '<span class="price-tax">'.$text_tax.' '.$tax.'</span>';
			}
			if ($points) {
        $html .= '<br/><br/><p>'.$text_points.' '.$points.'</p>';
			}
			if ($discounts) {
				foreach ($discounts as $discount) {
          $html .= '<p>'.$discount['quantity'].$text_discount.$discount['price'].'</p>';
				}
			}
			$html .= '</div>';
      
    } elseif ($this->config->get('config_template') == 'allure' ) {
      
      if (!$special) {
        $html .= '<li class="price">';
			  $html .= '<span itemprop="currency" class="hide">'.$this->config->get('config_currency').'</span>';
        $html .= '<span class="currency">';
  		
				preg_match('@[^\d\.\,]+@', $price, $matches);
				$html .= $matches[0];
			  $html .= '</span>';
        $html .= '<h2 itemprop="price">&nbsp;';
  			
				preg_match('@[\d\.\,]+@', $price, $matches);
				$html .= $matches[0];
			  $html .= '</h2>';
        $html .= '</li>';
      } else {
        $html .= '<li><span style="text-decoration: line-through;">'.$price.'</span></li>';
        $html .= '<li>';
        $html .= '<h2>'.$special.'</h2>';
        $html .= '</li>';
      }
      if ($tax) {
        $html .= '<li>'.$text_tax.' '.$tax.'</li>';
      }
      if ($points) {
        $html .= '<li>'.$text_points.' '.$points.'</li>';
      }
      if ($discounts) {
        $html .= '<li>';
        $html .= '<hr>';
        $html .= '</li>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
      }
      
    } elseif ($this->config->get('config_template') == 'OPC080185_3' ) { // Glorious
      
      $html .= '<ul class="list-unstyled">';
      if (!$special) {
        $html .= '<li>';
        $html .= '<h3 class="product-price">'.$price.'</h3>';
        $html .= '</li>';
      } else {
        $html .= '<li>';
        $html .= '<span class="old-price" style="text-decoration: line-through;">'.$price.'</span>';
        $html .= '<h3 class="special-price">'.$special.'</h3> ';
        $html .= '</li>';
      }
      if ($tax) {
        $html .= '<li>'.$text_tax.'<span class="price-tax">'.$tax.'</span></li>';
      }
      if ($points) {
        $html .= '<li>'.$text_points.' '.$points.'</li>';
      }
      if ($discounts) {
        $html .= '<li>';
        $html .= '</li>';
        foreach ($discounts as $discount) {
          $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
      }
      $html .= '</ul>';
      
    } elseif ($this->config->get('config_template') == 'kingstorepro') {
      
      $html .= '<div class="price">';
      if (!$special) {
        $html .= '<span class="price-new"><span itemprop="price">'.$price.'</span></span>';
      } else {
        $html .= '<span class="price-new price-sale"><span itemprop="price">'.$special.'</span></span> <span class="price-old">'.$price.'</span>';
      }
      $html .= '<br />';
      if ($tax) {
        $html .= '<span class="price-tax">'.$text_tax.' '.$tax.'</span><br />';
      }
      if ($points) {
        $html .= '<span class="reward"><small>'.$text_points.' '.$points.'</small></span><br />';
      }
      if ($discounts) {
        $html .= '<br />';
        $html .= '<div class="discount">';
        foreach ($discounts as $discount) {
          $html .= $discount['quantity'].$text_discount.$discount['price'].'<br />';
        }
        $html .= '</div>';
      }
      $html .= '</div>';
      
      
    } elseif ($this->config->get('config_template') == 'mobile') {   // mob!le
      
      if ( !$this->model_catalog_product ) {
        $this->load->model('catalog/product');
      }
      //$this->load->model('catalog/product');
      $product_info = $this->model_catalog_product->getProduct($prices['product_id']);
      
      if ($product_info['quantity'] <= 0) {
				$stock = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$stock = $product_info['quantity'];
			} else {
				$stock = $this->language->get('text_instock');
			}
      
      $_price = trim(preg_replace("/([^0-9,\\.])/i", "", $price), ' .,');
		  
		  $_currency = trim(preg_replace("/([0-9])/i", "", $price), ' .,');

      //if ($price && (int)$_price > 0) {
        $html .= '<ul class="list-unstyled" itemprop="offerDetails" itemscope="" itemtype="http://data-vocabulary.org/Offer">';
        if (!$special) {
          $html .= '<li>'.$text_stock.' '.$stock.'</li>';
          $html .= '<li>';
  			  $html .= '<span itemprop="currency" class="hide">'.$this->config->get('config_currency').'</span>';
          if (isset($_currency) && isset($_price) && $_currency && $_price) {
            $html .= '<span class="currency">';
            $html .= $_currency;
            $html .= ' </span>';
            $html .= '<h2 class="price" itemprop="price" price="'.$_price.'" currency='.$this->config->get('config_currency').'> ';
  					$html .= $_price;
            $html .= '</h2>';
          } else {
            $html .= '<h2 class="price" itemprop="price">';
            $html .= $price;
            $html .= '</h2>';
          }
          $html .= '</li>';
        } else {
          $html .= '<li>';
          $html .= '<h2 class="price strike">'.$price.'</h2> <h2 class="price" itemprop="price" price="'.$special.'">'.$special.'</h2>';
          $html .= '</li>';
        }
        if ($tax) {
          $html .= '<li class="ex-tax-price">'.$text_tax.' '.$tax.'</li>';
        }
        if ($points) {
          $html .= '<li>'.$text_points.' '.$points.'</li>';
        }
        if ($discounts) {
          $html .= '<li>';
          $html .= '<hr>';
          $html .= '</li>';
          foreach ($discounts as $discount) {
            $html .= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
          }
        }
        $html .= '</ul>';
      //}
      
      
    } elseif ($this->config->get('config_template') == 'theme511') {  // PrintStore 
      
      
      $html .= '<span	class="price-new">'.$special.'</span>';
			if (!$special) {
				$html .= '<span	class="price-new">'.$price.'</span>';
			} else {
				$html .= '<span	class="price-old">'.$price.'</span></li>';
			}
			if ($tax) {
				$html .= '<span class="tax">'.$text_tax.' '.$tax.'</span>';
			}
			$html .= '<div class="reward-block">';
			if ($points) {
        $html .= '<span	class="reward">'.$text_points.' '.$points.'</span>';
			}
			if ($discounts) {
        foreach ($discounts as $discount) {
          $html .= '<span>'.$discount['quantity'].$text_discount.$discount['price'].'</span>';
				}
			}
			$html .= '</div>';
      
    } elseif ($this->config->get('config_template') == 'theme546') {  // AudioGear
      
      $html .= '<span class="price-new">';
      $html .= $special;
      $html .= '</span>';
      if (!$special) {
        $html .= '<span class="price-new">';
        $html .= $price;
        $html .= '</span>';
      } else {
        $html .= '<span class="price-old">';
        $html .= $price;
        $html .= '</span>';
      }
      if ($tax) {
        $html .= '<span class="tax">';
        $html .= $text_tax;
        $html .= $tax;
        $html .= '</span>';
      }
      $html .= '<div class="reward-block">';
      if ($points) {
        $html .= '<span	class="reward">';
        $html .= $text_points;
        $html .= $points;
        $html .= '</span>';
      }
      if ($discounts) {
        foreach ($discounts as $discount) {
          $html .= '<span>';
              $html .= $discount['quantity'];
              $html .= $text_discount;
              $html .= $discount['price'];
          $html .= '</span>';
        }
      }
      $html .= '</div>';
      
    } elseif ($this->config->get('config_template') == 'coloring') {
      
      if (!$special) {
				$html.= '<h2>';
				$html.= $price;
				if ($tax) {
					$html.= '<span class="tax">'.$text_tax.' '.$tax.'</span>';
				}
				if ($points) {
					$html.= '<span class="points">'.$text_points.' <strong>'.$points.'</strong></span>';
				}
				$html.= '</h2>';
			} else {
				$html.= '<h2>';
				$html.= '<span class="price-old">&nbsp;'.$price.'&nbsp;</span>';
				$html.= $special;
				if ($tax) {
					$html.= '<span class="tax">'.$text_tax.' '.$tax.'</span>';
				}
				if ($points) {
					$html.= '<span class="points">'.$text_points.' <strong>'.$points.'</strong></span>';
				}
				$html.= '</h2>';
			}

			if ($discounts) {
        $html.= '<div class="alert alert-info">';
				foreach ($discounts as $discount) {
          $html.= '<div><strong>'.$discount['quantity'].'</strong>'.$text_discount.'<strong>'.$discount['price'].'</strong></div>';
        }
				$html.= '</div>';
			}
      
    } elseif ($this->config->get('config_template') == 'tt_petsyshop') {
      
      if (!$special) {
        $html.= $price;
      } else {
        $html.= '<span style="text-decoration: line-through;">'.$price.'</span>';
        $html.= $special;
      }
			$html.= '<span class="price-tax">';
      if ($tax) {
        $html.= $text_tax.' '.$tax;
      }
			$html.= '</span>';
      if ($points) {
        $html.= $text_points.' '.$points;
      }
      if ($discounts) {
        foreach ($discounts as $discount) {
          $html.= $discount['quantity'];
          $html.= $text_discount;
          $html.= $discount['price'];
        }
      }
      
      
    // OPC080178 constitute theme
    } elseif ($this->config->get('config_template') == 'OPC080178') {
      
      if (!$special) {
        $html.= '<li>';
        $html.= '<h2><span id="price_old">'.$price.'</span></h2>';
        $html.= '</li>';
      } else {
        $html.= '<li><span id="price_old" style="text-decoration: line-through;">'.$price.'</span></li>';
        $html.= '<li>';
        $html.= '<h2><span id="price_special">'.$special.'</span></h2>';
        $html.= '</li>';
      }
      if (false) {
        $html.= '<li>'.$text_tax.' <span id="price_tax">'.$tax.'</span></li>';
      }
      if ($points) {
        $html.= '<li>'.$text_points.' '.$points.'</li>';
      }
      if ($discounts) {
        $html.= '<li>';
        $html.= '<hr>';
        $html.= '</li>';
        foreach ($discounts as $discount) {
          $html.= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
      }
      
    } elseif ($this->config->get('config_template') == 'theme519') { // Beauty
      
      $html.= '<span	class="price-new">'.$special.'</span>';
      if (!$special) {
        $html.= '<span	class="price-new">'.$price.'</span>';
      } else {
        $html.= '<span	class="price-old">'.$price.'</span></li>';
      }
      if ($tax) {
        $html.= '<span class="tax">'.$text_tax.' '.$tax.'</span>';
      }
      $html.= '<div class="reward-block">';
      if ($points) {
        $html.= '<span	class="reward">'.$text_points.' '.$points.'</span>';
      }
      if ($discounts) {
        foreach ($discounts as $discount) {
          $html.= '<span>'.$discount['quantity'].$text_discount.$discount['price'].'</span>';
        }
      }
      $html.= '</div>';
      
    } elseif ($this->config->get('config_template') == 'megashop') {
    
      if (!$special) {
        $html.= '<span class="price-new"><span itemprop="price"><span id="priceUpdate">'.$price.'</span></span></span>';
      } else {
        $html.= '<span class="price-new price-sale"><span itemprop="price"><span id="priceUpdate">'.$special.'</span></span></span> <span class="price-old">'.$price.'</span>';
      }
      $html.= '<br />';
      if ($tax) {
        $html.= '<span class="price-tax">'.$text_tax.' '.$tax.'</span><br />';
      }
      if ($points) {
        $html.= '<span class="reward"><small>'.$text_points.' '.$points.'</small></span><br />';
      }
      if ($discounts) {
        $html.= '<br />';
        $html.= '<div class="discount">';
        foreach ($discounts as $discount) {
          $html.= $discount['quantity'].$text_discount.$discount['price'].'<br />';
        }
        $html.= '</div>';
      }
    
    } elseif ($this->config->get('config_template') == 'bigshop') {  
      
      if ( !$this->model_catalog_product ) {
        $this->load->model('catalog/product');
      }
      //$this->load->model('catalog/product');
      $product_info = $this->model_catalog_product->getProduct($prices['product_id']);
      if ($product_info['quantity'] <= 0) {

				$stock = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$stock = $product_info['quantity'];
			} else {
				$stock = $this->language->get('text_instock');
			}
      
      if (!$special) {
        $html.= '<li class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span class="real" itemprop="price">'.$price.'</span><span itemprop="availability" content="'.$stock.'"></span></li>';
        $html.= '<li></li>';
      } else {
        $html.= '<li class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span class="price-old">'.$price.'</span> <span class="real" itemprop="price">'.$special.'<span itemprop="availability" content="'.$stock.'"></span></span></li>';
        $html.= '<li></li>';
      }
      if ($tax) {
        $html.= '<li>'.$text_tax.' '.$tax.'</li>';
      }
      if ($points) {
        $html.= '<li>'.$text_points.' '.$points.'</li>';
      }
      if ($discounts) {
        $html.= '<li></li>';
        foreach ($discounts as $discount) {
          $html.= '<li>'.$discount['quantity'].$text_discount.$discount['price'].'</li>';
        }
      }
      
    } else {
    
      $html =   "<ul class=\"list-unstyled\">";
      if (!$special) {
        $html .= "<li>";
        $html .= "<h2>".$price."</h2>";
        $html .= "</li>";
      } else {
        $html .= "<li><span style=\"text-decoration: line-through;\">".$price."</span></li>";
        $html .= "<li>";
        $html .= "<h2>".$special."</h2>";
        $html .= "</li>";
      }
      if ($tax) {
        $html .= "<li>".$text_tax." ".$tax."</li>";
      }
      if ($points) {
        $html .= "<li>".$text_points." ".$points."</li>";
      }
      if ($discounts) {
        $html .= "<li>";
        $html .= "<hr>";
        $html .= "</li>";
        foreach ($discounts as $discount) {
          $html .= "<li>".$discount['quantity'].$text_discount.$discount['price']."</li>";
        }
      }
      $html .= "</ul>";
    
    }
    
 
    
    
    return array('html'=>$html, 'html_d'=>$html_d, 'html1'=>$html1, 'html2'=>$html2);
  }


}

?>