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
	header('HTTP/1.0 404 Not Found'); exit;
}


if (!class_exists('mmo_realmstatus')){
	include_once(registry::get_const('root_path').'portal/realmstatus/realmstatus.class.php');
}


/*+----------------------------------------------------------------------------
  | tera_realmstatus
  +--------------------------------------------------------------------------*/
if (!class_exists('tera_realmstatus')){
	class tera_realmstatus extends mmo_realmstatus{
		/**
		* __dependencies
		* Get module dependencies
		*/
		public static function __shortcuts(){
			$shortcuts = array('puf' => 'urlfetcher', 'env' => 'environment');
			return array_merge(parent::$shortcuts, $shortcuts);
		}

		/* Game name */
		protected $game_name = 'tera';

		/* URL to load serverstatus from */
		private $status_url = 'http://en.tera.gameforge.com/community/serverstatus';

		/* cache time in seconds default 30 minutes = 1800 seconds */
		private $cachetime = 1800;

		/* Array with all servers */
		private $server_list = array();

		/* image path */
		private $image_path;

		/**
		* Constructor
		*/
		public function __construct($moduleID){
			$this->moduleID = $moduleID;

			// call base constructor
			parent::__construct();

			// set image path
			$this->image_path = $this->env->link.'portal/realmstatus/tera/images/';

			// read in the server status
			$this->loadStatus();
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
			$servername = trim($servername);
			$servername = html_entity_decode($servername, ENT_QUOTES);
			$serverdata = $this->getServerData($servername);

			switch ($serverdata['status']){
				case 'online':	return 'up';
				case 'offline':	return 'down';
				default:		return 'unknown';
			}
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
			// set output
			$output = '';

			// loop through the servers and collect server data
			$tera_servers = array();
			if (is_array($servers)){
				foreach($servers as $servername){
					// get server data
					$servername = trim($servername);
					$servername = html_entity_decode($servername, ENT_QUOTES);
					$serverdata = $this->getServerData($servername);

					// output
					$output .= '<div class="tr">';

					// output status
					switch ($serverdata['status']){
						case 'status_green':
							$output .= '<div class="td"><img src="'.$this->image_path.'server_on.png" alt="Online" title="'.$servername.'" /></div>';
							$isUnknown = false;
						break;
						case 'status_red':
							$output .= '<div class="td"><img src="'.$this->image_path.'server_off.png" alt="Offline" title="'.$servername.'" /></div>';
							$isUnknown = false;
						break;
						default:
							$output .= '<div class="td"><img src="'.$this->image_path.'server_off.png" alt="Unknown" title="'.$servername.' ('.$this->user->lang('rs_unknown').')" /></div>';
							$isUnknown = true;
						break;
					}

					// output server name
					$output .= '<div class="td">'.$servername.'</div>';

					// output server type
					if ($isUnknown){
						$output .= '<div class="td"></div>';
					}else{
						if ($serverdata['type'] == 'PVP')
							$output .= '<div class="td rs_tera_pvp">PVP</div>';
						else
							$output .= '<div class="td rs_tera_pve">PVE</div>';
					}

					// output country flag
					$country_flag = $this->getCountryFlag($serverdata['language']);
					$output .= '<div class="td">';
					if ($country_flag != '')
						$output .= '<img src="'.$this->env->link.'images/flags/'.$country_flag.'.svg" alt="'.$country_flag.'" title="'.$serverdata['language'].'"/>';
					$output .= '</div>';

					// end row diff
					$output .= '</div>';
				}
			}
			return $output;
		}

		/**
		* outputCSS
		* Output CSS
		*/
		protected function outputCSS(){
			$style = '.rs_tera_pve {
				color: #739EFF;
			}

			.rs_tera_pvp {
				color: #FF7373;
			}';

			// add css
			$this->tpl->add_css($style);
		}

		/**
		* getServerData
		* Gets the data for the specified server
		*
		* @param  string  $servername  Name of the server to get data of
		*
		* @return array(status, population, type, language)
		*/
		private function getServerData($servername){
			$name = trim($servername);

			if (isset($this->server_list[$name]))
				return $this->server_list[$name];

			return array(
				'status'		=> 'unknown',
				'population'	=> -1,
				'type'			=> '',
				'language'		=> 'unknown',
			);
		}

		/**
		* loadStatus
		* Load status from either the pdc or from website
		*/
		private function loadStatus(){
			// try to load data from cache
			$this->server_list = $this->pdc->get('portal.module.realmstatus.tera', false, true);
			if (!$this->server_list){
				// none in cache or outdated, load from website
				$this->server_list = $this->loadServerStatus();
				// store loaded data within cache
				if (is_array($this->server_list)){
					$this->pdc->put('portal.module.realmstatus.tera', $this->server_list, $this->cachetime, false, true);
				}
			}
		}

		/**
		* loadServerStatus
		* Load the status for all TERA servers
		*
		* @return array(status, population, type, language)
		*/
		private function loadServerStatus(){
			// reset output
			$servers = array();

			// set URL reader options
			$this->puf->checkURL_first = true;

			// load html page
			$html = $this->puf->fetch($this->status_url);
			if (!$html || empty($html))
				return $servers;

			// create new tera html class
			require_once($this->root_path.'portal/realmstatus/tera/tera_html.class.php');
			$tera_html = new tera_html($html);

			// get the server lists
			$server_list = $tera_html->getServerList();

			if (!$server_list)
				return $servers;

			// process the server lists
			$tera_servers = $server_list->getServers();
			
			if (is_array($tera_servers)){
				foreach ($tera_servers as $server){					
					$servers[$server->name] = array(
						'status'		=> $server->status,
						'population'	=> intval($server->population),
						'type'			=> $server->type,
						'language'		=> $server->language,
					);
				}
			}

			// cleanup memory
			$tera_html->clear();
			return $servers;
		}

		/**
		* getCountryFlag
		* Gets the country flag image
		*
		* @param  string  $server_language  Language of server
		*
		* @return  string
		*/
		private function getCountryFlag($server_language){
			// return country status
			$language = strtolower($server_language);
			switch ($language){
				case 'de': return 'de';
				case 'en': return 'gb';
				case 'fr': return 'fr';
			}
			return '';
		}
	}
}
?>