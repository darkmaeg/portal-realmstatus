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
  'realmstatus'           => '서버상태',

  //  Settings
  'rs_realm'              => '서버 목록 (쉼표로 구분)',
  'rs_realm_help'         => 'Replace whitespace with _ on servers with 2 words. Like die_todeskrallen.',
  'rs_us'                 => 'US 서버 입니까?',
  'rs_us_help'            => 'This setting has only effects if WoW is set as game.',
  'rs_gd'                 => 'GD Lib 발견. 사용하시겠습니까? ',
  'rs_gd_help'            => 'This setting has only effects if WoW is set as game.',

  // Portal Modul
  'rs_no_realmname'       => '서버가 지정되지 않았습니다.',
  'rs_game_not_supported' => '서버상태가 현재 게임을 지원하지 않습니다.',
  'rs_realm_status_error' => "Errors occured while determing realmstatus for %1\$s",
));

?>
