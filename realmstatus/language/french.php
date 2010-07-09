<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: French	
//Created by EQdkp Plus Translation Tool on  2010-07-09 13:55
//File: module_realmstatus
//Source-Language: english

$alang = array( 
"realmstatus" => "Etat du royaume",
"rs_realm" => "Liste des royaumes (séparés par virgule)",
"rs_realm_help" => "Remplacez les espaces avec _ pour les serveurs en plusieurs mots, come Le_conseil_des_ombres.",
"rs_us" => "Serveur US ?",
"rs_us_help" => "Ce paramètre ne fonctionne que si WoW est le jeu par défaut",
"rs_gd" => "GD Lib trouvé. Voulez vous l'utiliser?",
"rs_gd_help" => "Ce paramètre ne fonctionne que si WoW est le jeu par défaut",
"rs_no_realmname" => "Pas de royaume spécifié",
"rs_realm_not_found" => "Royaume introuvable",
"rs_game_not_supported" => "Ce module ne fonctionne pas pour le jeu indiqué",
"rs_realm_status_error" => "Des erreurs sont survenues pendant la détermination du status du royaume pour %1$s",
 );
$plang = array_merge($plang, $alang);
?>