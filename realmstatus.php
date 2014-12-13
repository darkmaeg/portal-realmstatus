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
 
define('EQDKP_INC', true);
$eqdkp_root_path = './../../';
include_once($eqdkp_root_path.'common.php');

// load the portal language
registry::register('portal')->load_lang('realmstatus');

// get game the status is requested for
$game_name = registry::register('input')->get('game', 'unknown');
$game_name = strtolower($game_name);
$module_id = registry::register('input')->get('mid', 0);

// try to get a game status file for the requested game
$status_file = $eqdkp_root_path.'portal/realmstatus/'.$game_name.'/status.class.php';
if (file_exists($status_file)){
	include_once($status_file);

	$class_name = $game_name.'_realmstatus';
	$status = registry::register($class_name, array($module_id));
	if ($status)
		$realmstatus = $status->getJQueryOutput();
	else
		$realmstatus = '<div class="center">'.register('user')->lang('rs_game_not_supported').'</div>';
}else{
	$realmstatus = '<div class="center">'.register('user')->lang('rs_game_not_supported').'</div>';
}

echo $realmstatus;

?>