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
  | eq_realmstatus
  +--------------------------------------------------------------------------*/
if (!class_exists('eq_realmstatus')){
	class eq_realmstatus extends mmo_realmstatus{
		/**
		* __dependencies
		* Get module dependencies
		*/
		public static function __shortcuts(){
			$shortcuts = array('user', 'pdc', 'puf' => 'urlfetcher', 'env' => 'environment', 'tpl');
			return array_merge(parent::$shortcuts, $shortcuts);
		}

		/* Game name */
		protected $game_name = 'eq';

		/* URL to load server status from */
		private $eq_url = 'http://data.soe.com/xml/status/eq';

		/* cache time in seconds default 10 minutes = 600 seconds */
		private $cachetime = 600;

		/* Array with all servers */
		private $servers = array();

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
			$this->image_path = $this->env->link.'portal/realmstatus/eq/images/';

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
			if (is_array($this->servers)){
				// is in list?
				if (isset($this->servers[$servername])){
					// return status
					switch ($this->servers[$servername]['status']){
						case 'down':	return 'down';
						case 'locked':	return 'up';
						default:		return 'up';
					}
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
							$output .= '<div class="td"><img src="'.$this->image_path.'up.png" alt="Online" title="'.$servername.'" /></div>';
						break;
						case 'down':
							$output .= '<div class="td"><img src="'.$this->image_path.'down.png" alt="Offline" title="'.$servername.'" /></div>';
						break;
						default:
							$output .= '<div class="td"><img src="'.$this->image_path.'down.png" alt="'.$this->user->lang('rs_unknown').'" title="'.$servername.' ('.$this->user->lang('rs_unknown').')" /></div>';
						break;
					}

					// output server name
					$output .= '<div class="td">'.$servername.'</div>';

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
		protected function outputCSS(){}

		/**
		* loadStatus
		* Load status from either the pdc or from website
		*/
		private function loadStatus(){
			// try to load data from cache
			$this->servers = $this->pdc->get('portal.module.realmstatus.eq', false, true);
			if ($this->servers === null){
				// none in cache or outdated, load from website
				$this->servers = $this->loadServers();
				// store loaded data within cache
				if (is_array($this->servers)){
					$this->pdc->put('portal.module.realmstatus.eq', $this->servers, $this->cachetime, false, true);
				}
			}
		}

		/**
		* loadShards
		* Load the servers from the SOE website
		*
		* @return array
		*/
		private function loadServers(){
			// reset output
			$servers = array();

			// set URL reader options
			$this->puf->checkURL_first = true;

			// load xml
			$xml_string = $this->puf->fetch($this->eq_url);
			if ($xml_string){
				// parse xml
				$xml = simplexml_load_string($xml_string);
				if ($xml && $xml->game){
					foreach ($xml->game->region as $region){
						foreach ($region->server as $server){
							$attributes = $server->attributes();
							$servers[(string)$attributes->name] = array(
								'status' => (string)$attributes->status,
							);
						}
					}
				}
			}
			return $servers;
		}
	}
}
?>