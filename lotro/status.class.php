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
  | lotro_realmstatus
  +--------------------------------------------------------------------------*/
if (!class_exists('lotro_realmstatus')){
	class lotro_realmstatus extends mmo_realmstatus{
		/**
		* __dependencies
		* Get module dependencies
		*/
		public static function __shortcuts(){
			$shortcuts = array('puf' => 'urlfetcher', 'env' => 'environment');
			return array_merge(parent::$shortcuts, $shortcuts);
		}

		/* Game name */
		protected $game_name = 'lotro';

		/* Have a look at
		* http://forums.lotro.com/showthread.php?334025-LOTRO-Server-Status-v2.0
		* for details of this class
		*/

		/* URL to load realmstatus from */
		private $lotro_url = 'http://lux-hdro.de/serverstatus-rss.php?';

		/* cache time in seconds default 10 minutes = 600 seconds */
		private $cachetime = 600;

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
			$this->image_path = $this->env->link.'portal/realmstatus/lotro/images/';
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
			// try to load xml string from cache
			$status = $this->pdc->get('portal.module.realmstatus.lotro.'.$servername, false, true);
			if ($status === null){
				// none in cache or outdated, load from website
				$status = $this->loadStatus($servername);
				if ($status !== false){
					// store loaded data within cache
					$this->pdc->put('portal.module.realmstatus.lotro.'.$servername, $status, $this->cachetime, false, true);
				}else{
					$status = 'unknown';
				}
			}
			return $status;
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
			// header
			$output = '<div class="tr"><img src="'.$this->image_path.'status-head.gif"/></div>';

			// loop through the servers
			if (is_array($servers)){
				foreach($servers as $servername){
					$servername = trim($servername);
					$status = $this->checkServer($servername);
					switch ($status){
						case 'up':
							$output .= '<div class="rs_server_up">'.$servername.'</div>';
						break;
						case 'down':
							$output .= '<div class="rs_server_down">'.$servername.'</div>';
						break;
						default:
							$output .= '<div class="rs_server_down">'.$servername.' ('.$this->user->lang('rs_unknown').')</div>';
						break;
					}
				}
			}

			// footer
			$output .= '<div class="tr"><img src="'.$this->image_path.'status_base.gif"/></div>';
			return $output;
		}

		/**
		* outputCSS
		* Output CSS
		*/
		protected function outputCSS(){
			$style = '.rs_server_up {
				background-image:url(\''.$this->image_path.'server-on.gif\');
				width:145px;
				height:25px;
				padding-left:55px;
				padding-top:10px;
				margin: 0px;
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #fff;
			}

			.rs_server_down {
				background-image:url(\''.$this->image_path.'server-off.gif\');
				width:145px;
				height:25px;
				padding-left:55px;
				padding-top:10px;
				margin: 0px;
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #fff;
			}

			.rs_server_middle {
				background-image:url(\''.$this->image_path.'server-middle.gif\');
				width:145px;
				height:25px;
				padding-left:55px;
				padding-top:10px;
				margin: 0px;
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #fff;
			}';

			// add css
			$this->tpl->add_css($style);
		}

		/**
		* loadStatus
		* Load status from either the pdc or from codemasters website
		*
		* @param  string  $servername  Name of server to check
		*
		* @return string ('up', 'down', 'unknown')
		*/
		private function loadStatus($servername){
			$this->puf->checkURL_first = true;
			$xml_string = $this->puf->fetch($this->lotro_url.urlencode(utf8_strtolower($servername)).'=1');
			if ($xml_string)
				return $this->parseXML($xml_string, $servername);

			return 'unknown';
		}

		/**
		* parseXML
		* Parse the XML realm string
		*
		* @param  string  $xml_string  Content of Status XML
		*
		* @return string ('up', 'down', 'unknown')
		*/
		private function parseXML($xml_string, $servername){
			if (!empty($xml_string)){
				// parse xml
				$xml = simplexml_load_string($xml_string);
				if ($xml !== false && $xml->channel->item){
					foreach($xml->channel->item as $item){
						$strDesc = $item->description;
						if (strpos($strDesc, $servername) === 0){
							$string = substr($strDesc, strlen($servername)+2);
							if (strpos($string, 'offen') === 0) return "up";
							if (strpos($string, 'zu') === 0) return "down";
						}
					}
				}
			}
			return 'unknown';
		}
	}
}
?>