<?php
/*	Project:	EQdkp-Plus
 *	Package:	Realm Status Portal Module
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists('mmo_realmstatus')){
	include_once(registry::get_const('root_path').'portal/realmstatus/realmstatus.class.php');
}

/*+----------------------------------------------------------------------------
  | wow_realmstatus
  +--------------------------------------------------------------------------*/
if (!class_exists('wow_realmstatus')){
	class wow_realmstatus extends mmo_realmstatus{

		/* Game name */
		protected $game_name = 'wow';

		/* The style for output */
		private $style;
	
		protected $moduleID = 0;

		/**
		* Constructor
		*/
		public function __construct($moduleID){
			$this->moduleID = $moduleID;

			// call base constructor
			parent::__construct();

			// init armory
			$this->initArmory();

			// init the styles class
			$this->initStyle();
		}

		/**
		* checkServer
		* Check if specified server is up/down/unknown
		*
		* @param  string  $servername  Name of server to check
		*
		* @return string ('up', 'down', 'unknown')
		*/
		public function checkServer($servername){
			$realmdata = $this->getRealmData($servername);

			// get status of realm
			if (is_array($realmdata) && isset($realmdata['status'])){
				switch (intval($realmdata['status'])){
					case 0:	return 'down';		break;
					case 1:	return 'up';		break;
					default:	return 'unknown';	break;
				}
			}
			return 'unknown';
		}

		/**
		* getOutput
		* Get the portal output for all servers
		*
		* @param  array  $servers  Array of server names
		*
		* @return string
		*/
		protected function getOutput($servers){
			// get realms array
			$realms = array();
			foreach ($servers as $realm){
				$realm = trim($realm);
				$realm = html_entity_decode($realm, ENT_QUOTES);
				$realmdata = $this->getRealmData($realm);
				$realms[$realm] = $realmdata;
			}

			// get output from style
			$output = $this->style->output($realms);
			return $output;
		}

		/**
		* outputCSS
		* Output CSS
		*/
		protected function outputCSS(){
			$this->style->outputCssStyle();
		}

		/**
		* getRealmData
		* Get the realm data for the specified realm
		*
		* @param  string  $realmname  Name of the realm
		*
		* @return array(type, queue, status, population, name, slug)
		*/
		private function getRealmData($realmname){
			// convert the realm name to the API specific handling
			$name = trim($realmname);
			$name = strtolower($name);
			$name = str_replace(array('\'', ' '), array('', '-'), $name);

			// get the cached (do not force) realm data for this realm
			$realmdata = $this->game->obj['armory']->realm(array($name), false);

			// the data are returned as array with
			// 'realms' => array(array(type, queue, status, population, name, slug))

			// if array contains more than 1 realm, the realm is unknown and all realms are returned
			// by the API, so ignore them
			if (is_array($realmdata) && isset($realmdata['realms']) && is_array($realmdata['realms']) && count($realmdata['realms']) == 1){
				// extract the realm data for this realm
				return $realmdata['realms'][0];
			}

			// return as unknown
			return array(
				'type'			=> 'error',
				'queue'			=> '',
				'status'		=> -1,
				'population'	=> 'error',
				'name'			=> $realmname,
				'slug'			=> $name,
			);
		}

		/**
		* initArmory
		* Initialize the Armory access
		*/
		private function initArmory(){
			// init the Battle.net armory object
			$serverLoc = $this->config->get('uc_server_loc') ? $this->config->get('uc_server_loc') : 'eu';
			$this->game->new_object('bnet_armory', 'armory', array($serverLoc, $this->config->get('uc_data_lang')));
		}

		/**
		* initStyle
		* Initialize the styles classes
		*/
		private function initStyle(){
			$file_style_normal = $this->root_path.'portal/realmstatus/wow/styles/wowstatus.style_normal.class.php';
			$file_style_gdi    = $this->root_path.'portal/realmstatus/wow/styles/wowstatus.style_gdi.class.php';

			// include the files
			include_once($file_style_normal);
			include_once($file_style_gdi);

			// get class
			if ($this->config->get('gd', 'pmod_'.$this->moduleID))
				$this->style = registry::register('wowstatus_style_gdi');
			else
				$this->style = registry::register('wowstatus_style_normal');
		}
	}
}
?>