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


// get current status from URL
$lotro_url = 'http://www.lotro-europe.com/server.php?search=yes&keyword=';
$url_data = @file_get_contents($lotro_url);

$image_path = $eqdkp_root_path.'portal/realmstatus/LOTRO/images/';

// header for table
$realmstatus .= '<tr><td>
                   <style type="text/css">
                   .server_up{
                      background-image:url(\''.$image_path.'server-on.gif\');
                      width:145px;
                      height:25px;
                      padding-left:55px;
                      padding-top:10px;
                      margin: 0px;
                      font-family: Verdana, Arial, Helvetica, sans-serif;
                      font-size: 11px;
                      color: #fff;
                   }

                   .server_down{
                      background-image:url(\''.$image_path.'server-off.gif\');
                      width:145px;
                      height:25px;
                      padding-left:55px;
                      padding-top:10px;
                      margin: 0px;
                      font-family: Verdana, Arial, Helvetica, sans-serif;
                      font-size: 11px;
                      color: #fff;
                   }

                   .server_middle{
                      background-image:url(\''.$image_path.'server-middle.gif\');
                      width:145px;
                      height:25px;
                      padding-left:55px;
                      padding-top:10px;
                      margin: 0px;
                      font-family: Verdana, Arial, Helvetica, sans-serif;
                      font-size: 11px;
                      color: #fff;
                   }
                   </style>

                   <img src="'.$image_path.'status-head.gif"/>';


if (strlen($url_data) > 0)
{
  foreach ($realmnames as $realmname)
  {
      $realmname = trim($realmname);

      // use regex to find server and get status
      $pattern = '/<div class="([^>]+)">[^<\/]+'.$realmname.'<\/div>/';
      $count = preg_match($pattern, $url_data, $server_array);
      if ($count > 0 && is_array($server_array))
      {
        $realmstatus .= '<div class="'.$server_array[1].'">'.$realmname.'</div>';
      }
      else
      {
        $realmstatus .= '<div class="server_down">'.$realmname.'</div>';
      }
  }
}
else
{
  $realmstatus .= '<div class="server_down">'.$plang['rs_no_realmname'].'</div>';
}

// footer for table
$realmstatus .= '  <img src="'.$image_path.'status_base.gif"/>
                 </td></tr>';

?>
