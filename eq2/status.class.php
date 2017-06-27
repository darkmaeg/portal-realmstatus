<?php
/*	Project:	EQdkp-Plus
 *	Package:	Realm Status Portal Module
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');
  exit;
}

if (!class_exists('mmo_realmstatus'))
{
  include_once(registry::get_const('root_path').'portal/realmstatus/realmstatus.class.php');
}

/*+----------------------------------------------------------------------------
  | eq2_realmstatus
  +--------------------------------------------------------------------------*/
if (!class_exists('eq2_realmstatus'))
{
  class eq2_realmstatus extends mmo_realmstatus
  {
    /**
     * __dependencies
     * Get module dependencies
     */
    public static function __shortcuts()
    {
      $shortcuts = array('user', 'pdc', 'puf' => 'urlfetcher', 'env' => 'environment', 'tpl');
      return array_merge(parent::$shortcuts, $shortcuts);
    }

    /* Game name */
    protected $game_name = 'eq2';

    /* URL to load server status from */
    private $eq2_url = 'http://census.daybreakgames.com/s:eqdkpplus/xml/status/eq2';

    /* cache time in seconds default 10 minutes = 600 seconds */
    private $cachetime = 600;

    /* Array with all servers */
    private $servers = array();

    /* image path */
    private $image_path;


    /**
     * Constructor
     */
    public function __construct($moduleID)
    {
      $this->moduleID = $moduleID;
      
      // call base constructor
      parent::__construct();

      // set image path
      $this->image_path = $this->env->link.'portal/realmstatus/eq2/images/';

      // read in the server status
      $this->loadStatus();
    }

    /**
     * checkServer
     * Check if specified server is up/down/unknown
     *
     * @param  string  $servername  Name of server to check
     *
     * @return string ('up', 'down', 'unknown')
     */
    public function checkServer($servername)
    {
      if (is_array($this->servers))
      {
        // is in list?
        if (isset($this->servers[$servername]))
        {
          // return status
          switch ($this->servers[$servername]['status'])
          {
        	case 'down':     return 'down';
        	case 'locked':	 return 'locked';
	    	case 'up'      : return 'up';
		case 'missing' : return 'missing';
		case 'unknown' : return 'unknown';
		case 'high'    : return 'high';
		case 'medium'  : return 'medium';
		case 'low'     : return 'low';
            default:         return 'up';
          }
        }
      }

      return 'unknown';
    }

    /**
     * getOutput
     * Get the portal output for all servers
     *
     * @param  array  $servers  Array of server names
     *
     * @return string
     */
    protected function getOutput($servers)
    {
      // set output
      $output = '';
	  // loop through the servers
      if (is_array($servers))
      {
        foreach($servers as $servername)
        {
          // get status
	  $servername = trim($servername);
	  $servername = html_entity_decode($servername, ENT_QUOTES);
          $status = $this->checkServer($servername);
		 
          // output
		  $output .= '<div class="tr">';

          // output status
          switch ($status)
          {
	    case 'low':
              $output .= '<div class="td"><img src="'.$this->image_path.'up.png" alt="Online" title="'.$servername.'" /></div><div class="td"><img src="'.$this->image_path.'low.png" alt="Low" title="Low" /></div>';
              break;
	    case 'medium':
              $output .= '<div class="td"><img src="'.$this->image_path.'up.png" alt="Online" title="'.$servername.'" /></div><div class="td"><img src="'.$this->image_path.'med.png" alt="Medium" title="Medium" /></div>';
              break;
	    case 'high':
              $output .= '<div class="td"><img src="'.$this->image_path.'up.png" alt="Online" title="'.$servername.'" /></div><div class="td"><img src="'.$this->image_path.'high.png" alt="High" title="High" /></div>';
              break;
        case 'up':
              $output .= '<div class="td"><img src="'.$this->image_path.'up.png" alt="Online" title="'.$servername.'" /></div>';
              break;
        case 'down':
              $output .= '<div class="td"><img src="'.$this->image_path.'down.png" alt="Down" title="'.$servername.'" /></div><div class="td"><img src="'.$this->image_path.'ssblank.png" alt="None" title="None" /></div>';
              break;
	    case 'locked':
              $output .= '<div class="td"><img src="'.$this->image_path.'locked.png" alt="Locked" title="'.$servername.'" /></div><div class="td"><img src="'.$this->image_path.'ssblank.png" alt="None" title="None" /></div>';
              break;
	    case 'missing':
              $output .= '<div class="td"><img src="'.$this->image_path.'missing.png" alt="Missing" title="'.$servername.'" /></div><div class="td"><img src="'.$this->image_path.'ssblank.png" alt="None" title="None" /></div>';
              break;
	    case 'unknown':
              $output .= '<div class="td"><img src="'.$this->image_path.'unknown.png" alt="Unknown" title="'.$servername.'" /></div><div class="td"><img src="'.$this->image_path.'ssblank.png" alt="None" title="None" /></div>';
              break;  
        default:
              $output .= '<div class="td"><img src="'.$this->image_path.'up.png" alt="'.$this->user->lang('rs_unknown').'" title="'.$servername.' ('.$this->user->lang('rs_unknown').')" /></div>';
              break;
          }

          // output server name
	  if ($servername == 'Fallen Gate') {$servername = 'Fallen Gate (TLE)';}
	  $servername = trim($servername);
	  $output .= '<div class="td">'.$servername.'</div>';
	  if ($servername == 'Fallen Gate (TLE)') {$servername = 'Fallen Gate';}
	  if ($servername == 'Stormhold') {$servername = 'Stormhold (TLE)';}
	  $servername = trim($servername);
	  $output .= '<div class="td">'.$servername.'</div>';
	  if ($servername == 'Stormhold (TLE)') {$servername = 'Stormhold';}
	  // output country flag
          $country_flag = $this->getCountryFlag($servername);
          $output .= '<div class="td">';
          if ($country_flag != '')
	  $output .= '<img src="'.$this->env->link.'images/flags/'.$country_flag.'.svg" alt="'.$country_flag.'" title="'.$this->servers[$servername]['region'].'"/>';
          $output .= '</div>';

          // end row diff
          $output .= '</div>';
        }
      }
	  return $output;
    }

    /**
     * Output CSS
     */
    protected function outputCSS()
    {
	/*
		$style = '.rs_server_up {
				background-image:url(\''.$this->image_path.'server-on.gif\');
				width:145px;
				height:25px;
				padding-left:55px;
				padding-top:10px;
				margin: 0px;
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #fff;
			}';

			// add css
			$this->tpl->add_css($style);
		
		}		
		*/
    }

    /**
     * loadStatus
     * Load status from either the pdc or from website
     */
    private function loadStatus()
    {
      // try to load data from cache
      $this->servers = $this->pdc->get('portal.module.realmstatus.eq2', false, true);
      if ($this->servers === null)
      {
        // none in cache or outdated, load from website
        $this->servers = $this->loadServers();
        // store loaded data within cache
        if (is_array($this->servers))
        {
          $this->pdc->put('portal.module.realmstatus.eq2', $this->servers, $this->cachetime, false, true);
        }
      }
    }

    /**
     * loadShards
     * Load the servers from the daybreak website
     *
     * @return array
     */
    private function loadServers()
    {
      // reset output
      $servers = array();

      // set URL reader options
      $this->puf->checkURL_first = true;

      // load xml
      $xml_string = $this->puf->fetch($this->eq2_url);
      if ($xml_string)
      {
        // parse xml
        $xml = simplexml_load_string($xml_string);
        if ($xml && $xml->game)
        {
          foreach ($xml->game->region as $region)
          {
            foreach ($region->server as $server)
            {
              $attributes = $server->attributes();
              $servers[(string)$attributes->name] = array(
                'region' => (string)$region->attributes()->name,
                'status' => (string)$attributes->status,
              );
            }
          }
        }
      }

      return $servers;
    }

    /**
     * getCountryFlag
     * Get the country flag for shard
     *
     * @param  string  $servername  Name of server to get flag of
     *
     * @return string
     */
    private function getCountryFlag($servername)
    {
      if (is_array($this->servers))
      {
        // is in list?
        if (isset($this->servers[$servername]))
        {
          // return country
          $region = $this->servers[$servername]['region'];
          if (strcmp($region, 'EU English') == 0)  return 'gb';
          if (strcmp($region, 'US English') == 0)  return 'us';
          if (strcmp($region, 'Beta') == 0)        return 'us';
	  if (strcmp($region, 'Public Test') == 0) return 'us';
	}
      }
      return '';
    }
  }
}
?>
