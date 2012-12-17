<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-02-20 16:52:50 +0100 (Sa, 20. Feb 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   (c) 2008 by Aderyn / Ghoschdi
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 7307 $
 *
 * $Id: status.php 7307 2010-02-20 15:52:50Z osr-corgan $
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
