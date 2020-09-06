<?

/*
   BESCHREIBUNG:
   
   Baut das Installationsformular in das Grdunddesign des Admin
   Bereiches ein.
   
*/

function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	/*--- Grundfunktionen --- start --- */

	//Benötigte Dateien einbinden
	require(BASE_PATH."/includes/actions/variables.actions.php");  # Register Globals AUS
	require(BASE_PATH."/includes/functions/global.functions.php"); # Allgemeine Funktionen
	require(BASE_PATH."/includes/classes/mysql.class.php");        # MySQL Funktionen
	require(BASE_PATH."/includes/classes/template.class.php");     # Template Funktionen

	require(USER_PATH."/settings/base/mysql.settings.php");    # MySQL Konfiguration

	//Admin Grunddesign: Template Klasse initialisieren
	$ground_tpl       = new template_class;    # Klasse zuweisen
	$ground_tpl->path = ABS_PATH."/src/templates/"; # Pfad zu den Templates

	//Installation: Template Klasse initialisieren
	$tpl              = new template_class;    # Klasse zuweisen
	$tpl->path        = BASE_PATH."/templates/"; # Pfad zu den Templates
	
	//MySQL Verbindung
	$sql         = new mysql_class;                    # KLasse zuweisen
	$sql->prefix = $settings["mysql"]["table_prefix"]; # Tabellen Prefix
	$sql->order  = $settings["mysql"]["order_by"];     # Sortierung

	/*--- Grundfunktionen --- ende --- */



	/*--- install check --- start --- */

	//Verbindung herstellen
	$sql->connect($settings["mysql"]["host"],$settings["mysql"]["user"],$settings["mysql"]["pass"],$settings["mysql"]["db"]);

	//Installationsprüfung
	if ( $sql->install_check("base") )
	{
		die("Bereits installiert!");
	}

	/*--- install check --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground"); # Grunddesign
	$tpl->load("install");       # Installations Template
	$tpl->insertVar("path",REL_PATH);
	
	//Elemente einfügen
	$ground_tpl->insertVar("path",REL_PATH);                  # Sonderpfad
	$ground_tpl->insertVar("title","Installation");           # Name
	$ground_tpl->insertVar("name","Beeco Installation"); # Titel
	$ground_tpl->insertVar("text",$tpl->getResult());        # Installationstemplate
		
	//ausgabe
	$retVal = $ground_tpl->getResult();

	//speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();
	
	return $retVal;

	/*--- Ausgabe --- ende --- */
}

?>