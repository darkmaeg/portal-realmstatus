<?php
/******************************
 * EQDKP PLUS
 * Who is online
 * (c) 2008 by Aderyn
 * ------------------
 * $Id: $
 ******************************/

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  // Title
  'realmstatus'                => 'Serverstatus',
  
  //  Settings
  'rs_realm'                   => 'Liste von Servern (Komma getrennt)',
  
  // Portal Modul
  'rs_no_realmname'            => 'Kein Server angegeben',
));

?>
