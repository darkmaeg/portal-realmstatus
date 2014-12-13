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

// Title
$lang['realmstatus']				= 'Realmstatus';
$lang['realmstatus_name']			= 'Realmstatus';
$lang['realmstatus_desc']			= 'Display the current realm status';

//  Settings
$lang['realmstatus_f_realm']		= 'List of servers';
$lang['realmstatus_f_help_realm']	= 'For multiple servers the servers have to be insert comma separated';
$lang['realmstatus_f_us']			= 'Is it an US server?';
$lang['realmstatus_f_help_us']		= 'This setting has only effects if RIFT or WoW is set as game.';
$lang['realmstatus_f_gd']			= 'GD Lib (%s) found. Do you want to use it?';
$lang['realmstatus_f_help_gd']		= 'This setting only effects the game WoW.';

// Portal Modul
$lang['rs_no_realmname']			= 'No realm specified';
$lang['rs_realm_not_found']			= 'Realm not found';
$lang['rs_game_not_supported']		= 'Realmstatus is not supported for the current game';
$lang['rs_unknown']					= 'Unknown';
$lang['rs_realm_status_error']		= "Errors occured while determing realmstatus for %1\$s";
$lang['rs_loading']					= 'Loading Status...';
$lang['rs_loading_error']			= 'Failed to load Status.';

?>
