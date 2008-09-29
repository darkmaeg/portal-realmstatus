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


// You have to define the Module Information
$portal_module['realmstatus'] = array(                     // the same name as the folder!
      'name'          => 'Realmstatus Module',             // The name to show
      'path'          => 'realmstatus',                    // Folder name again
      'version'       => '1.0.0',                          // Version
      'author'        => 'Aderyn',                         // Author
      'contact'       => 'Aderyn@gmx.net',                 // email adress
      'description'   => 'Show Realmstatus',               // Detailed Description
      'positions'     => array('left1', 'left2', 'right'), // Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
      'signedin'      => '0',                              // 0 = all users, 1 = signed in only
      'install'       => array('autoenable'        => '0',
                               'defaultposition'   => 'right',
                               'defaultnumber'     => '5',
                         ),
      );

/* Define the Settings if needed
name:       The name of the Database field & Input name
language:   The name of the language string in the language file
property:   What type of field? (text,checkbox,dropdown)
size:       Size of the field if required (optional)
options:    If dropdown: array('value'=>'Name')

There could be unlimited amount of settings
Settings page is created dynamically
*/
$portal_settings['realmstatus'] = array(
  'pk_realmstatus_realm'  => array(
        'name'      => 'rs_realm',
        'language'  => 'rs_realm',
        'property'  => 'text',
        'size'      => '40',
  ),
  'pk_realmstatus_us'     => array(
        'name'      => 'rs_us',
        'language'  => 'rs_us',
        'property'  => 'checkbox',
  ),
);

if(extension_loaded('gd') && function_exists('gd_info'))
{
  $portal_settings['realmstatus']['pk_realmstatus_gd'] = array(
        'name'      => 'rs_gd',
        'language'  => 'rs_gd',
        'property'  => 'checkbox',
        'text'      => 'GD LIB Version',
  );
}

// The output function
if(!function_exists(realmstatus_module))
{
  function realmstatus_module()
  {
    global $plang, $conf_plus, $eqdkp_root_path, $eqdkp;

    $realmnames = array();
    // list of realms by portal modul config?
    if (isset($conf_plus['rs_realm']) && strlen($conf_plus['rs_realm']))
    {
      // build array by exploding
      $realmnames = explode(',', $conf_plus['rs_realm']);
    }
    // realm name by plus config?
    else if (isset($conf_plus['pk_servername']) && strlen($conf_plus['pk_servername']))
    {
      $realmnames[] = $conf_plus['pk_servername'];
    }

    // output realms
    $realmstatus = '';
    if (count($realmnames) > 0 && strlen($realmnames[0]))
    {
      $status_file = $eqdkp_root_path.'portal/realmstatus/'.$eqdkp->config['default_game'].'/status.php';

      if (file_exists($status_file))
      {
        $realmstatus .= '<table width="100%" border="0" cellspacing="1" cellpadding="2" class="noborder">';
        include($status_file);
        $realmstatus .= '</table>';
      }
      else
      {
        $realmstatus .= '<div align="center">'.$plang['rs_game_not_supported'].'</div>';
      }
    }
    else
    {
      $realmstatus .= '<div align="center">'.$plang['rs_no_realmname'].'</div>';
    }

    // return the output for module manager
    return $realmstatus;
  }
}
?>
