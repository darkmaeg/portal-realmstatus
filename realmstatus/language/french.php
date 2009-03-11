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
  'realmstatus'           => 'Etat du royaume',

  //  Settings
  'rs_realm'              => 'Liste des royaumes (séparés par virgule)',
  'rs_us'                 => 'Serveur US ?',
  'rs_gd'                 => 'Lib GD trouvée. Voulez-vous l\'utliser ?',

  // Portal Modul
  'rs_no_realmname'       => 'Pas de royaume spécifié',
  'rs_game_not_supported' => 'Ce module ne fonctionne pas pour le jeu indiqué',

  // Help
  'rs_realm_help'         => 'Remplacez les espaces avec _ pour les serveurs en plusieurs mots, come Le_conseil_des_ombres.',
));

?>
