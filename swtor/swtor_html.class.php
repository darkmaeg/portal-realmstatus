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

if (!class_exists('simple_html_dom')){
	include_once(registry::get_const('root_path').'portal/realmstatus/includes/simple_html_dom.php');
}


/*+----------------------------------------------------------------------------
  | swtor_html
  +--------------------------------------------------------------------------*/
if (!class_exists('swtor_html')){
	class swtor_html{
		/* The US server list */
		private $swtor_servers_us = null;

		/* The EU server list */
		private $swtor_servers_eu = null;

		/* The AP server list */
		private $swtor_servers_ap = null;

		/* The DOM object */
		private $dom;

		/**
		* Constructor
		*
		* @param  string  $html  HTML data of the SWTOR status page
		*/
		public function __construct($html){
			$this->dom = new simple_html_dom();
			$this->dom->load($html);

			// process
			$this->process();
		}

		/**
		* getServerListUS
		* Gets the US server list
		*
		* @return  swtor_html_serverlist
		*/
		public function getServerListUS(){
			return $this->swtor_servers_us;
		}

		/**
		* getServerListEU
		* Gets the EU server list
		*
		* @return  swtor_html_serverlist
		*/
		public function getServerListEU(){
			return $this->swtor_servers_eu;
		}

		/**
		* getServerListAP
		* Gets the Asia/Pacific server list
		*
		* @return  swtor_html_serverlist
		*/
		public function getServerListAP(){
			return $this->swtor_servers_ap;
		}

		/**
		* clear
		* Clear the memory of the dom object
		*/
		public function clear(){
			$this->dom->clear();
		}

		/**
		* process
		* Process the DOM object and get the server lists
		*/
		private function process(){
			// get the DOM list for the us servers
			$server_list_us = $this->dom->find("div[class=serverList]", 0);
			if ($server_list_us)
				$this->swtor_servers_us = new swtor_html_serverlist($server_list_us);

			// get the DOM list for the eu servers
			$server_list_eu = $this->dom->find("div[class=serverList]", 1);
			if ($server_list_eu)
				$this->swtor_servers_eu = new swtor_html_serverlist($server_list_eu);

			// get the DOM list for the ap servers
			$server_list_ap = $this->dom->find("div[class=serverList]", 2);
			if ($server_list_ap)
				$this->swtor_servers_ap = new swtor_html_serverlist($server_list_ap);
		}
	}
}

/*+----------------------------------------------------------------------------
  | swtor_html_serverlist
  +--------------------------------------------------------------------------*/
if (!class_exists('swtor_html_serverlist')){
	class swtor_html_serverlist{
		/* The DOM node for this server list*/
		private $dom;

		/**
		* Constructor
		*
		* @param  DOMDocument  $dom  The DOM node for this server list
		*/
		public function __construct($dom){
			$this->dom = $dom;
		}

		/**
		* getServers
		* Get a array of all available servers
		*
		* @return  array(swtor_html_server)
		*/
		public function getServers(){
			$servers = array();

			// get an array of all DOM Nodes with <div class="serverBody" data-name="xxx">
			$serverNodes = $this->dom->find("div[data-name]");
			if (is_array($serverNodes)){
				foreach ($serverNodes as $serverNode)
					$servers[] = new swtor_html_server($serverNode);
			}
			return $servers;
		}
	}
}

/*+----------------------------------------------------------------------------
  | swtor_html_server
  +--------------------------------------------------------------------------*/
if (!class_exists('swtor_html_server')){
	class swtor_html_server{
		/* The DOM node for this server*/
		private $dom;

		/**
		* Constructor
		*
		* @param  DOMDocument  $dom  The DOM node for this server list
		*/
		public function __construct($dom){
			$this->dom = $dom;
		}

		/**
		* getter
		* Getter for all "properties"
		*
		* @param  string  $name  Name of the property to get
		*
		* @return  mixed
		*/
		public function __get($name){
			// data-status="UP" data-name="black vulkars" data-population="2" data-type="PVP" data-timezone="West"
			// data-status="UP" data-name="bao-dur"       data-population="1" data-type="PvE" data-language="English"

			switch ($name){
				case 'status':
					return $this->dom->attr['data-status'];
				case 'name':
				$nameNode = $this->dom->find('.name', 0);
					return ($nameNode ? $nameNode->text() : 'Unknown');
				case 'population':
					return $this->dom->attr['data-population'];
				case 'type':
					return $this->dom->attr['data-type'];
				case 'timezone': // only available if region is us
					return (isset($this->dom->attr['data-timezone']) ? $this->dom->attr['data-timezone'] : 'Unknown');
				case 'language': // only available if region is eu
					return (isset($this->dom->attr['data-language']) ? $this->dom->attr['data-language'] : 'Unknown');
				case 'region':
					return (isset($this->dom->attr['data-timezone']) ? 'us' : 'eu');
				default:
				return null;
			}
		}
	}
}
?>
