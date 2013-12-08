<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2012-11-17 11:37:37 +0100 (Sa, 17. Nov 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: shoorty $
 * @copyright   2008-2011 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 12468 $
 *
 * $Id: english.php 12468 2012-11-17 10:37:37Z shoorty $
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
$lang['realmstatus_f_gd']			= 'GD Lib found. Do you want to use it?';
$lang['realmstatus_f_help_gd']		= 'This setting has only effects if WoW is set as game.';

// Portal Modul
$lang['rs_no_realmname']			= 'No realm specified';
$lang['rs_realm_not_found']			= 'Realm not found';
$lang['rs_game_not_supported']		= 'Realmstatus is not supported for the current game';
$lang['rs_unknown']					= 'Unknown';
$lang['rs_realm_status_error']		= "Errors occured while determing realmstatus for %1\$s";
$lang['rs_loading']					= 'Loading Status...';
$lang['rs_loading_error']			= 'Failed to load Status.';

?>
