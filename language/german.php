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
  'realmstatus'           => 'Serverstatus',

  //  Settings
  'rs_realm'              => 'Liste von Servern (Komma getrennt)',
  'rs_realm_help'         => 'Bei WoW Servern die aus mehreren Wörtern bestehen, müssen die Leerzeichen durch einen Unterstrich ersetzt werden. z.b. Die_Todeskrallen',
  'rs_us'                 => 'Handelt es sich um einen US Server?',
  'rs_us_help'            => 'Diese Einstellung hat nur Auswirkungen wenn als Spiel WoW eingestellt ist.',
  'rs_gd'                 => 'GD Lib erkannt. GD Lib Version verwenden?',
  'rs_gd_help'            => 'Diese Einstellung hat nur Auswirkungen wenn als Spiel WoW eingestellt ist.',

  // Portal Modul
  'rs_no_realmname'       => 'Kein Server angegeben',
  'rs_realm_not_found'    => 'Server nicht gefunden',
  'rs_game_not_supported' => 'Der Serverstatus wird für das Spiel nicht unterstützt',
  'rs_realm_status_error' => "Fehler beim Ermitteln des Serverstatus für %1\$s",
));

?>
