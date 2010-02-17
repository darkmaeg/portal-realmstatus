<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: $
 * -----------------------------------------------------------------------
 * @author      $Author:  $
 * @copyright   (c) 2008 by Aderyn
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev:  $
 *
 * $Id: $
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(

  // Title
  'realmstatus'           => 'Realmstatus',

  //  Settings
  'rs_realm'              => 'List of Realms (comma separated)',
  'rs_realm_help'         => 'Replace whitespace with _ on servers with 2 words. Like die_todeskrallen.',
  'rs_us'                 => 'Is it an US server?',
  'rs_us_help'            => 'This setting has only effects if WoW is set as game.',
  'rs_gd'                 => 'GD Lib found. Do you want to use it? ',
  'rs_gd_help'            => 'This setting has only effects if WoW is set as game.',

  // Portal Modul
  'rs_no_realmname'       => 'No realm specified',
  'rs_realm_not_found'    => 'Realm not found',
  'rs_game_not_supported' => 'Realmstatus is not supported for the current game',
  'rs_realm_status_error' => "Errors occured while determing realmstatus for %1\$s",
));

?>
