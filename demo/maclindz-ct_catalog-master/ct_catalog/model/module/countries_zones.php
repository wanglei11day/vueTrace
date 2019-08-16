<?php
/*------------------------------------------------------------------------
# Countries & Zones Manager
# ------------------------------------------------------------------------
# The Krotek
# Copyright (C) 2011-2016 thekrotek.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://thekrotek.com
# Support: support@thekrotek.com
-------------------------------------------------------------------------*/

class ModelModuleCountriesZones extends Model
{
	public function convertNames($names)
	{
		$oldnames = array();
		$newernames = array();
		$newnames = array();

		$this->load->model('module/countries_zones');
		$languages = $this->model_module_countries_zones->getLanguages();					

		if (strpos($names, '::') !== false) {
			$oldnames = explode('::', $names);
							
			foreach ($oldnames as $key => $value) {
				if (strpos($value, '###') !== false) {
					$name = explode('###', $value);			
					$newernames[$name[1]] = $name[0];
				}
			}
		} else {
			$oldnames = array();
		}
				
		$key = 0;
				
		foreach ($languages as $language) {
			if ($newernames) {
				if (!empty($newernames[$language['language_id']])) {
					$newnames[$language['code']] = $newernames[$language['language_id']];
				} else {
					$newnames[$language['code']] = reset($newernames);
				}
			} elseif ($oldnames) {
				if (!empty($oldnames[$key])) {
					$newnames[$language['code']] = $oldnames[$key];
				} else {
					$newnames[$language['code']] = reset($oldnames);
				}
						
				$key ++;
			} else {
				$newnames[$language['code']] = $names;
			}
		}
			
		return $newnames;
	}
			
	public function getItemNames($table, $id)
	{
		$query = $this->db->query("SELECT name FROM `".DB_PREFIX.$table."` WHERE ".$table."_id = '".(int)$id."'");
		
		if (!empty($query->row['name'])) {
			if (json_decode($query->row['name'])) {
				$names = json_decode($query->row['name'], true);
			} else {
				$names = $this->convertNames($query->row['name']);
			}
		} else {
			$names = array("");
		}

		$this->load->model('module/countries_zones');
		$languages = $this->model_module_countries_zones->getLanguages();
				
		foreach ($languages as $language) {
			if (empty($names[$language['code']])) {
				$names[$language['code']] = reset($names);
			}
		}

		return $names;
	}
	
	public function replaceNames($data)
	{
		$names = $this->getItemNames('country', $data['shipping_country_id']);
		$data['shipping_country'] = $names[$this->config->get('config_language')];
				
		$names = $this->getItemNames('zone', $data['shipping_zone_id']);
		$data['shipping_zone'] = $names[$this->config->get('config_language')];
				
		$names = $this->getItemNames('country', $data['payment_country_id']);
		$data['payment_country'] = $names[$this->config->get('config_language')];

		$names = $this->getItemNames('zone', $data['payment_zone_id']);
		$data['payment_zone'] = $names[$this->config->get('config_language')];
				
		return $data;
	}
	
	public function getLanguages()
	{
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX."language` ORDER BY sort_order, name ASC");
		$languages = $query->rows;
		
		foreach ($languages as $key => $language) {
			if (version_compare(VERSION, '2.2', '<')) {
				$languages[$key]['image'] = 'image/flags/'.$language['image'];
			} else {
				$languages[$key]['image'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
			}
		}

		return $languages;
	}
		
	public function getCurrentLanguage()
	{
		$code = "";
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		if (isset($this->session->data['language'])) {
			$code = $this->session->data['language'];
		}
				
		if (isset($this->request->cookie['language']) && !array_key_exists($code, $languages)) {
			$code = $this->request->cookie['language'];
		}
					
		if (!empty($this->request->server['HTTP_ACCEPT_LANGUAGE']) && !array_key_exists($code, $languages)) {
			$browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);
			
			foreach ($browser_languages as $browser_language) {
				if (array_key_exists(strtolower($browser_language), $languages)) {
					$code = strtolower($browser_language);
					break;
				}
			}
		}
		
		if (!array_key_exists($code, $languages)) {
			$code = $this->config->get('config_language');
		}
		
		return $code;
	}		
}

?>