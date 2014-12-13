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
  | exchange_realmstatus
  +--------------------------------------------------------------------------*/
if (!class_exists('exchange_realmstatus'))
{
	class exchange_realmstatus extends gen_class{
		/* List of dependencies */
		public static $shortcuts = array('pex' => 'plus_exchange');

		/* Additional options */
		public $options = array();

		/**
		* get_realmstatus
		* GET Request for realmstatus entries
		*
		* @param   array   $params   Parameters array
		* @param   string  $body     XML body of request
		*
		* @returns array
		*/
		public function get_realmstatus($params, $body){
			// set default response
			$response = array('realms' => array());

			// try to load the status file for this game
			$game_name = strtolower($this->game->get_game());
			$status_file = $this->root_path.'portal/realmstatus/'.$game_name.'/status.class.php';
			if (file_exists($status_file)){
				include_once($status_file);

				$class_name = $game_name.'_realmstatus';
				$status = registry::register($class_name);
				if ($status)
					$response['realms'] = $status->getExchangeOutput();
				else
					return $this->pex->error($this->user->lang('rs_game_not_supported'));
			}else{
				return $this->pex->error($this->user->lang('rs_game_not_supported'));
			}
			return $response;
		}
	}
}
?>