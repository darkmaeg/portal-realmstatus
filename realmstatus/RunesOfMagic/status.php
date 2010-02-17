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
 * @copyright   (c) 2008 by Aderyn
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev$
 *
 * $Id$
 */


// basic URL for the status request
$rom_base_status_url = 'http://status.blackout-gaming.net/status.php?';
// list of all realms supported with each IP/Port address
$rom_realm_list = array(
  // EU
  'Macantacht'      => array('region' => 'EU', 'ip' => '77.95.25.163', 'port' => '16502', 'type' => 'PvE'),
  'Siochain'        => array('region' => 'EU', 'ip' => '77.95.25.163', 'port' => '16402', 'type' => 'PvE'),
  'Smacht'          => array('region' => 'EU', 'ip' => '77.95.25.164', 'port' => '16402', 'type' => 'PvP'),
  // DE
  'Aontacht'        => array('region' => 'DE', 'ip' => '77.95.25.166', 'port' => '16502', 'type' => 'PvE'),
  'Laoch'           => array('region' => 'DE', 'ip' => '77.95.25.166', 'port' => '16402', 'type' => 'PvE'),
  'Muinin'          => array('region' => 'DE', 'ip' => '77.95.25.167', 'port' => '16502', 'type' => 'PvE'),
  'Cogadh'          => array('region' => 'DE', 'ip' => '77.95.25.167', 'port' => '16402', 'type' => 'PvP'),
  'Tuath'           => array('region' => 'DE', 'ip' => '77.95.25.164', 'port' => '16502', 'type' => 'PvE'),
  'Riocht'          => array('region' => 'DE', 'ip' => '77.95.25.168', 'port' => '16402', 'type' => 'PvE'),
  // US
  'Artemis'         => array('region' => 'US', 'ip' => '64.127.104.211', 'port' => '16402', 'type' => 'PvE'),
  'Govinda'         => array('region' => 'US', 'ip' => '64.127.104.211', 'port' => '16502', 'type' => 'PvE'),
  'Osha'            => array('region' => 'US', 'ip' => '64.127.104.212', 'port' => '16402', 'type' => 'PvE'),
  'Grimdal'         => array('region' => 'US', 'ip' => '64.127.104.212', 'port' => '16502', 'type' => 'PvP'),
  'Grimdal (Krynn)' => array('region' => 'US', 'ip' => '64.127.104.212', 'port' => '16502', 'type' => 'PvP'),
);
// list of login servers
$rom_login_list = array(
  'DE' => array('ip' => '77.95.25.162',   'port' => '21002'),
  'EU' => array('ip' => '77.95.25.162',   'port' => '21002'),
  'US' => array('ip' => '64.127.104.210', 'port' => '21002'),
);

$image_path = $eqdkp_root_path.'portal/realmstatus/RunesOfMagic/images/';

// set style
$realmstatus .= '<style type="text/css">
                 .rom_realm_status {
                   width: 20px;
                   padding: 0px 1px;
                 }

                 .rom_realm_name {
                   text-align: left;
                   padding: 0px 1px;
                 }

                 .rom_realm_region {
                   width: 20px;
                   padding: 0px 1px 0px 0px;
                 }

                 .rom_realm_type {
                   width: 25px;
                   text-align: left;
                 }
                 </style>';

// loop through all realms and output status
foreach ($realmnames as $realmname)
{
    $realmname = trim($realmname);

    // try to find realm in array
    $realm = false;
    foreach ($rom_realm_list as $rom_realmname => $rom_realm)
    {
      if (strcasecmp($realmname, $rom_realmname) == 0)
      {
        $realm = $rom_realm;
        break;
      }
    }

    if ($realm !== false)
    {
      // build realm url(s)
      $rom_status_url_realm = $rom_base_status_url.'dns='.$realm['ip'].'&port='.$realm['port'].'&style=t1';
      $rom_status_url_login = $rom_base_status_url.'dns='.$rom_login_list[$realm['region']]['ip'].'&port='.$rom_login_list[$realm['region']]['port'].'&style=t1';

      // get url content for realm and login
      $url_data_realm = $urlreader->GetURL($rom_status_url_realm);
      $url_data_login = $urlreader->GetURL($rom_status_url_login);
      if ($url_data_realm && $url_data_login)
      {
        // prepare table header
        $realmstatus .= '<tr>';

        // set image for online/offline (both, login + realm servers have to be online for "online" status)
        if (strstr($url_data_realm, 'online') !== false && strstr($url_data_login, 'online') !== false)
        {
          $realmstatus .= '<td class="rom_realm_status"><img src="'.$image_path.'online.png" title="Online"/></td>';
        }
        else
        {
          $realmstatus .= '<td class="rom_realm_status"><img src="'.$image_path.'offline.png" title="Offline"/></td>';
        }

        // append realm name
        $realmstatus .= '<td class="rom_realm_name">'.$realmname.'</td>';

        // append region
        $realmstatus .= '<td class="rom_realm_region"><img src="'.$image_path.$realm['region'].'.gif" alt="'.$realm['region'].'" title="'.$realm['region'].'"/></td>';

        // append type
        $realmstatus .= '<td class="rom_realm_type">'.$realm['type'].'</td>';

        // end table row
        $realmstatus .= '</tr>';

      }
      else
      {
        $realmstatus .= '<tr><td><div>'.sprintf($plang['rs_realm_status_error'], $realmname).'</div></td></tr>';
      }
    }
    else
    {
      $realmstatus .= '<tr><td><div>'.$plang['rs_no_realmname'].'</div></td></tr>';
    }
}

?>
