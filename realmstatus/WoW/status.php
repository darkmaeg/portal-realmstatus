<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date$
 * -----------------------------------------------------------------------
 * @author      $Author$
 * @copyright   (c) 2008 by Aderyn / Ghoschdi
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev$
 *
 * $Id$
 */
  
if ($conf_plus['rs_gd'])
{
	global $pcache;
  $region = ($conf_plus['rs_us']) ? 'us' : 'eu';
  foreach ($realmnames as $realmname)
  {
    $realmname = trim($realmname);
    $url     	  = $eqdkp_root_path.'portal/realmstatus/WoW/wow_ss.php?realm='.$realmname.'&amp;region='.$region ;
    $url2     	  = $pcache->BuildLink().'portal/realmstatus/WoW/wow_ss.php?realm='.$realmname.'&amp;region='.$region ;
    $urls[]		  = $url2;        
    $realmstatus .= '<tr><td align="center">
                      <img src="'.$url.'" alt="WoW-Serverstatus: '.$realmname.'" title="'.$realmname.'"/>
                     </td></tr>';
  }
}
else
{
  $urls = array();
  foreach ($realmnames as $realmname)
  {
    $region       = ($conf_plus['rs_us']) ? '1' : '2';
    $realmname    = trim($realmname);
    $replace      = array(" " => "_", "'" => "");
    $scored_realm = strtolower(strtr($realmname, $replace));
    $url     	  = 'http://www.wowrealmstatus.net/status.php?s='.$scored_realm.'&r='.$region ;
    $urls[]		  = $url;    
    $realmstatus .= '<tr><td align="center">
                      <img src="'.$url.'" alt="WoW-Serverstatus: '.$realmname.'" title="'.$realmname.'"/>
                     </td></tr>';
  }
  #d($xxxx);
}

?>
