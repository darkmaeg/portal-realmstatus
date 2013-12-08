<?php
 /*
 * Project:   EQdkp-Plus
 * License:   Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:   2008
 * Date:    $Date: 2012-02-19 13:19:34 +0100 (So, 19. Feb 2012) $
 * -----------------------------------------------------------------------
 * @author    $Author: Aderyn $
 * @copyright 2008-2011 Aderyn
 * @link    http://eqdkp-plus.com
 * @package   eqdkp-plus
 * @version   $Rev: 11694 $
 *
 * $Id: realmstatus_portal.class.php 11694 2012-02-19 12:19:34Z Aderyn $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

/*+----------------------------------------------------------------------------
  | realmstatus_portal
  +--------------------------------------------------------------------------*/
class realmstatus_portal extends portal_generic
{

  protected static $path = 'realmstatus';
  protected static $data = array(
    'name'			=> 'Realmstatus Module',
    'version'		=> '1.1.4',
    'author'		=> 'Aderyn',
	'icon'			=> 'fa-desktop',
    'contact'		=> 'Aderyn@gmx.net',
    'description' 	=> 'Show Realmstatus',
	'exchangeMod'	=> array('realmstatus'),
	'lang_prefix'	=> 'realmstatus_'
  );
  protected static $positions = array('left1', 'left2', 'right');
  protected $settings = array(
    'realm'  => array(
      'type'		=> 'text',
      'size'		=> '40',
    ),
    'us' => array(
      'type'		=> 'checkbox',
    ),
  );
  protected static $install  = array(
    'autoenable'      => '0',
    'defaultposition' => 'right',
    'defaultnumber'   => '5',
  );

  /**
   * Constructor
   */
  public function __construct($position='')
  {
    parent::__construct($position);

    // check ig gd lib is available, if so, make option to use available
    if (extension_loaded('gd') && function_exists('gd_info')){
      $this->settings['gd'] = array(
        'type' 		=> 'checkbox',
        'text'		=> 'GD LIB Version',
      );
    }
  }

  /**
   * output
   * Returns the portal output
   *
   * @return string
   */
  public function output()
  {
    // empty output as default
    $realmstatus = '';

    // try to load the status file for this game
    $game_name = strtolower($this->game->get_game());
    $status_file = $this->root_path.'portal/realmstatus/'.$game_name.'/status.class.php';
    if (file_exists($status_file))
    {
      include_once($status_file);

      $class_name = $game_name.'_realmstatus';
      $status = registry::register($class_name);
      if ($status)
        $realmstatus .= $status->getPortalOutput();
      else
        $realmstatus .= '<div class="center">'.$this->user->lang('rs_game_not_supported').'</div>';
    }
    else
    {
      $realmstatus .= '<div class="center">'.$this->user->lang('rs_game_not_supported').'</div>';
    }

    // return the output for module manager
    return $realmstatus;
  }

  /**
   * reset
   * Reset the portal module
   */
  public function reset()
  {
    // clear cache
    $this->pdc->del_prefix('portal.module.realmstatus');
  }

}
?>
