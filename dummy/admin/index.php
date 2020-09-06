<?

/* BESCHREIBUNG:
   Ermittelt den Absoluten Pfad und ruft die benötigte Funktion auf
*/

//Error Reporting konfigurieren
error_reporting (E_ALL ^ E_NOTICE);

//Home Pfad
define("ABS_PATH",preg_replace("=^(.*)(/admin)([/]?)$=siU","\\1",dirname(str_replace('//','/', str_replace('\\','/', (php_sapi_name()=='cgi'||php_sapi_name()=='isapi' ||php_sapi_name()=='cgi-fcgi')&&($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED'])? ($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED']):($_SERVER['ORIG_SCRIPT_FILENAME']?$_SERVER['ORIG_SCRIPT_FILENAME']:$_SERVER['SCRIPT_FILENAME']))))));
define("REL_PATH",preg_replace("=^(.*)(/admin)([/]?)$=siU","\\1",dirname($_SERVER['PHP_SELF'])));

//Lib Pfad
if ( @is_dir(ABS_PATH.'/src/libs') ) {
	define("LIB_PATH",ABS_PATH.'/src/libs');
} else die('ERROR: Beeco Libs not found!');

//Pfad zum Kern (Base)
if ( @is_dir(ABS_PATH.'/src/libs/base') ) {
	define("BASE_PATH",ABS_PATH.'/src/libs/base');
} else die('ERROR: Beeco Base not found!');

//Benutzer Verzeichnis
if ( @is_dir(ABS_PATH.'/user') ) {
	define("USER_PATH",ABS_PATH.'/user');
} else die('ERROR: Beeco User-Folder not found!');

//Relativer Pfad zum User Verzeichnis
if ( @is_dir(ABS_PATH.'/user') ) {
	#define("REL_USER_PATH",'./user');
	define("REL_USER_PATH",REL_PATH.'/user');
} else die('ERROR: Beeco User-Folder not found!');

//Relativer Pfad zum Base Verzeichnis
if ( @is_dir(BASE_PATH) ) {
	define("REL_BASE_PATH",'./src/libs/base');
} else die('ERROR: Beeco User-Folder not found!');

//Startfunktion einbinden
require(BASE_PATH.'/admin/index.php');
init(); # Grundaktionen für den Start

?>