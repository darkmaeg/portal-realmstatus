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

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

/*+----------------------------------------------------------------------------
  | mmo_realmstatus
  +--------------------------------------------------------------------------*/
if (!class_exists('mmo_realmstatus')){
	abstract class mmo_realmstatus extends gen_class{

		/* List of dependencies */
		public static $shortcuts = array('pex' => 'plus_exchange');

		/* Game name */
		protected $game_name = 'unknown';

		/* List of servers to process */
		private $servers = array();

		protected $moduleID = 0;

		/**
		* Constructor
		*/
		public function __construct(){
			// load server list
			$this->loadServerList();
		}

		/**
		* checkServer
		* Check if specified server is up/down/unknown
		*
		* @param  string  $servername  Name of server to check
		*
		* @return string ('up', 'down', 'unknown')
		*/
		public abstract function checkServer($servername);

		/**
		* getPortalOutput
		* get Portal output
		*
		* @return string
		*/
		public function getPortalOutput(){
			$output = '';

			// no realm specified?
			if (count($this->servers) > 0){
				// output any CSS
				$this->outputCSS();

				// output JS code
				$this->outputJSCode();

				$output = '<div id="realmstatus_output_'.$this->game_name.'">
							<div class="center" style="margin: 4px;">
								<i class="fa fa-refresh fa-spin fa-lg"></i>&nbsp;'.$this->user->lang('rs_loading').'
							</div>
							</div>';
			}else{
				$output = '<div class="center">'.$this->user->lang('rs_no_realmname').'</div>';
			}
			return $output;
		}

		/*
		* getExchangeOutput
		* get Exchange output
		*
		* @return array
		*/
		public function getExchangeOutput(){
			$output = array();

			// no realm specified?
			if (count($this->servers) > 0){
				foreach ($this->servers as $server){
					$output[] = array(
						'name'   => trim($server),
						'status' => $this->checkServer(trim($server)),
					);
				}
			}else{
				return $this->pex->error($this->user->lang('rs_no_realmname'));
			}
			return $output;
		}

		/**
		* getJQueryOutput
		* get async JQuery output
		*
		* @return string
		*/
		public function getJQueryOutput(){
			$output = '';
			
			// no realm specified?
			if (count($this->servers) > 0){
				// wrap within table
				$output .= '<div class="table">';
				$output .= $this->getOutput($this->servers);
				$output .= '</div>';
			}else{
				$output .= '<div class="center">'.$this->user->lang('rs_no_realmname').'</div>';
			}
			return $output;
		}

		/**
		* getOutput
		* Get the portal output for all servers
		*
		* @param  array  $servers  Array of server names
		*
		* @return string
		*/
		protected abstract function getOutput($servers);

		/**
		* outputCSS
		* Output CSS
		*/
		protected abstract function outputCSS();

		/**
		* loadServerList
		* get list of servers to process
		*/
		private function loadServerList(){
			// set empty list of realms
			$this->servers = array();
			// list of realms by portal modul config?
			if ($this->config->get('realm', 'pmod_'.$this->moduleID) && strlen($this->config->get('realm', 'pmod_'.$this->moduleID)) > 0){
				// build array by exploding
				$this->servers = explode(',', $this->config->get('realm', 'pmod_'.$this->moduleID));
			}else if ($this->config->get('servername') && strlen($this->config->get('servername')) > 0){
				// realm name by plus config?
				$this->servers[] = $this->config->get('servername');
			}
		}

		/**
		* outputJSCode
		* output the javascript code for async portal output
		*/
		private function outputJSCode(){
			// build JS for Async load
			$jscode = '$.ajax({
							url: "'.$this->server_path.'portal/realmstatus/realmstatus.php'.$this->SID.'&mid='.$this->moduleID.'",
							data: {
								game: "'.$this->game_name.'"
							},
							success: function(data, textStatus, jqXHR) {
								$(\'#realmstatus_output_'.$this->game_name.'\').html(data);
							},
							error: function(jqXHR, textStatus, errorThrown) {
								var htmlOut = \'<div class="center" style="margin:2px;">'.$this->user->lang('rs_loading_error').'</div>\';
								$(\'#realmstatus_output_'.$this->game_name.'\').html(htmlOut);
							},
							dataType: "html"
						});';

			$this->tpl->add_js($jscode, 'docready');
		}
	}
}
?>
