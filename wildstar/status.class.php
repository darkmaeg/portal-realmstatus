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
	header('HTTP/1.0 404 Not Found');
	exit;
}

if (!class_exists('mmo_realmstatus')){
	include_once(registry::get_const('root_path').'portal/realmstatus/realmstatus.class.php');
}

/*+----------------------------------------------------------------------------
  | wildstar_realmstatus
  +--------------------------------------------------------------------------*/
if (!class_exists('wildstar_realmstatus')){
	class wildstar_realmstatus extends mmo_realmstatus {
		/**
		* __dependencies
		* Get module dependencies
		*/
		public static function __shortcuts(){
			$shortcuts = array('puf' => 'urlfetcher', 'env' => 'environment');
			return array_merge(parent::$shortcuts, $shortcuts);
		}

		/* Game name */
		protected $game_name = 'wildstar';

		/* URL to load servers */
		private $wildstar_url = 'http://wsstatus.com/embed/json.php';

		/* cache time in seconds default 10 minutes = 600 seconds */
		private $cachetime = 600;

		/* Array with all shards */
		private $servers = array();

		/* image path */
		private $image_path;

		/**
		* Constructor
		*/
		public function __construct(){
			// call base constructor
			parent::__construct();

			// set image path
			$this->image_path = $this->env->link.'portal/realmstatus/wildstar/images/';

			// read in the shard status
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
			if (is_array($this->servers)){
				// is in list?
				if (isset($this->servers[$servername])){
					// return status
					return $this->servers[$servername]["online"] ? 'up' : 'down';
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
			// set output
			$output = '';
			// loop through the servers
			if (is_array($servers)){
				foreach($servers as $servername){
					// get status
					$servername = trim($servername);
					$status = $this->checkServer($servername);

					// output
					$output .= '<div class="tr">';

					// output status
					switch ($status){
						case 'up':
						$output .= '<div class="td"><img src="'.$this->image_path.'server_on.png" alt="Online" title="'.$servername.'" /></div>';
						break;
						case 'down':
						$output .= '<div class="td"><img src="'.$this->image_path.'server_off.png" alt="Offline" title="'.$servername.'" /></div>';
						break;
						default:
						$output .= '<div class="td"><img src="'.$this->image_path.'server_off.png" alt="Offline" title="'.$servername.' ('.$this->user->lang('rs_unknown').')" /></div>';
						break;
					}

					// output server name
					$output .= '<div class="td">'.$servername.'</div>';

					// output server type
					$output .= ($this->isServerPvP($servername)) ? '<div class="td rs_wildstar_pvp">PvP' : '<div class="td rs_wildstar_pve">PvE';

					// append RP flag
					$output .= ($this->isServerRP($servername)) ? ' <div>RP</div>' : '';

					// end diff from server type
					$output .= '</div>';

					// output country flag
					$country_flag = $this->getRegionFlag($servername);
					$output .= '<div class="td">';
					if ($country_flag != '')
						$output .= '<img src="'.$this->env->link.'images/flags/'.$country_flag.'.png" alt="'.$country_flag.'" title="'.$this->servers[$servername]['language'].'"/>';
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
			$style = '.rs_wildstar_pve {
				color: #F2CE85;
			}

			.rs_wildstar_pvp {
				color: #E51717;
			}

			.rs_wildstar_pve > div, .rs_wildstar_pvp > div {
				color:   #8936B3;
				display: inline;
			}';

			// add css
			$this->tpl->add_css($style);
		}

		/**
		* loadStatus
		* Load status from either the pdc or from website
		*/
		private function loadStatus(){

			// try to load data from cache
			$this->servers = $this->pdc->get('portal.module.realmstatus.wildstar', false, true);
			if ($this->servers === null){
				// none in cache or outdated, load from website
				$this->servers = $this->loadServerData();
				// store loaded data within cache
				if (is_array($this->servers)){
					$this->pdc->put('portal.module.realmstatus.wildstar', $this->servers, $this->cachetime, false, true);
				}
			}
		}

		/**
		* load Server Data
		* Load the servers from the wildstar website
		*
		* @return array
		*/
		private function loadServerData(){
			// reset output
			$servers = array();

			// set URL reader options
			$this->puf->checkURL_first = true;

			// load xml
			$json_string = $this->puf->fetch($this->wildstar_url);
			if ($json_string){
				// parse xml
				$json = json_decode($json_string, true);
				if (is_array($json)){
					foreach ($json as $serverdata){
						$servers[$serverdata['Name']] = array(
							'serverid'	=> $serverdata['ServerId'],
							'online'	=> ($serverdata['Status'] == 'online') ? true : false,
							'region'	=> $serverdata['Region'],
							'type'		=> $serverdata['Type'],
							'pvp'		=> ($serverdata['Type'] == 'PvP') ? true : false,
						);
					}
				}
			}
			return $servers;
		}

		/**
		* isServerPvP
		* Get the server type PvE/PvP
		*
		* @param  string  $servername  Name of server to get type of
		*
		* @return boolen
		*/
		private function isServerPvP($servername){
			if (is_array($this->servers)){
				// is in list?
				if (isset($this->servers[$servername])){
					// return pvp status
					return $this->servers[$servername]["pvp"];
				}
			}
			return false;
		}

		/**
		* isServerRP
		* Get the server RP
		*
		* @param  string  $servername  Name of server to get RP of
		*
		* @return boolen
		*/
		private function isServerRP($servername){
			if (is_array($this->servers)){
				// is in list?
				if (isset($this->servers[$servername])){
					// return pvp status
					return $this->servers[$servername]["rp"];
				}
			}
			return false;
		}

		/**
		* getCountryFlag
		* Get the country flag for shard
		*
		* @param  string  $servername  Name of server to get flag of
		*
		* @return string
		*/
		private function getRegionFlag($servername){
			if (is_array($this->servers)){
				// is in list?
				if (isset($this->servers[$servername])){
					// return pvp status
					$language = strtolower($this->servers[$servername]["region"]);
					switch ($language){
						case '1':  return 'us';
						case '2': return 'europeanunion';
					}
				}
			}
			return '';
		}
	}
}
?>
