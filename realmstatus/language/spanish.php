<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: Spanish	
//Created by EQdkp Plus Translation Tool on  2010-07-09 15:10
//File: module_realmstatus
//Source-Language: english

$alang = array( 
"realmstatus" => "Estado del Reino",
"rs_realm" => "Listado de Reinos (separados por coma)",
"rs_realm_help" => "Reemplazar espacio en blanco por _ en servidores con dos palabras. Por ejemplo colinas_pardas.",
"rs_us" => "¿Es un servidor de Estados Unidos?",
"rs_us_help" => "Esta opción sólo tiene efecto si WoW está configurado como juego.",
"rs_gd" => "GD Lib encontrada. ¿Quieres usarla?",
"rs_gd_help" => "Esta opción sólo tiene efecto si WoW está configurado como juego.",
"rs_no_realmname" => "Ningún reino especificado.",
"rs_realm_not_found" => "Reino no encontrado",
"rs_game_not_supported" => "Estado del reino no funciona con el juego seleccionado",
"rs_realm_status_error" => "Ocurrieron errores al determinar el estado del reino para %1$s",
 );
$plang = array_merge($plang, $alang);
?>