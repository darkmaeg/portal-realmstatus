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
  | tera_html
  +--------------------------------------------------------------------------*/
if (!class_exists('tera_html')){
	class tera_html{
		/* The server list */
		private $tera_servers = null;

		/* The DOM object */
		private $dom;

		/**
		* Constructor
		*
		* @param  string  $html  HTML data of the TERA status page
		*/
		public function __construct($html){
			$this->dom = new simple_html_dom();
			$this->dom->load($html);

			// process
			$this->process();
		}

		/**
		* getServerList
		* Gets the server list
		*
		* @return  tera_html_serverlist
		*/
		public function getServerList(){
			return $this->tera_servers;
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
			// get the DOM list for the servers
			$server_list = $this->dom->find("div[id=serverstatus]", 0);
			if ($server_list)
				$this->tera_servers = new tera_html_serverlist($server_list);
		}
	}
}

/*+----------------------------------------------------------------------------
  | tera_html_serverlist
  +--------------------------------------------------------------------------*/
if (!class_exists('tera_html_serverlist')){
	class tera_html_serverlist{
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

			// get an array of all DOM Nodes with <tr>
			$serverNodes = $this->dom->find("tr");
			if (is_array($serverNodes)){
				// skip first server cause this is the table heading
				$i = 0;
				foreach ($serverNodes as $serverNode){
					if ($i++ != 0)
						$servers[] = new tera_html_server($serverNode);
				}
			}
			return $servers;
		}
	}
}

/*+----------------------------------------------------------------------------
  | tera_html_server
  +--------------------------------------------------------------------------*/
if (!class_exists('tera_html_server')){
	class tera_html_server{
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
			// <td><img src="/design/community_site/img/server_on.png" alt="online" title="Online"/></td>
			// <td>Allemantheia</td>
			// <td class="PVE">PVE</td>
			// <td class="average">Medium</td>
			// <td class="lang">en</td>

			switch ($name){
				case 'status':
					$node = $this->dom->find('td', 0);
					if (!$node) return 'unknown';
					$img_node = $node->find('img', 0);
					return ($img_node ? $img_node->attr['alt'] : 'unknown');
				case 'name':
					$node = $this->dom->find('td', 1);
					return ($node ? trim($node->text()) : 'Unknown');
				case 'population':
					$node = $this->dom->find('td', 3);
					return ($node ? $node->text() : 'unknown');
				case 'type':
					$node = $this->dom->find('td', 2);
					return ($node ? $node->text() : 'unknown');
				case 'language': // only available if region is eu
					$node = $this->dom->find('td', 4);
					return ($node ? $node->text() : 'unknown');
				default:
					return null;
			}
		}
	}
}
?>