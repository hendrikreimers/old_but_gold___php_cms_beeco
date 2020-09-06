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
	#require(PLUGIN_PATH."/admin/install/install.functions.php");    # Funktionen für die Installation nötig
	require(USER_PATH."/settings/plugins/pics/other.settings.php"); # Zusatzkonfig

	//Installation: Template Klasse initialisieren
	$tpl->path        = PLUGIN_PATH."/templates/"; # Pfad zu den Templates
	
	//MySQL Verbindung
	$sql         = new mysql_class;                    # KLasse zuweisen
	$sql->prefix = $settings["mysql"]["table_prefix"]; # Tabellen Prefix
	$sql->order  = $settings["mysql"]["order_by"];     # Sortierung

	/*--- Grundfunktionen --- ende --- */



	/*--- install check --- start --- */

	//Installationsprüfung
	if ( $sql->install_check("pics") )
	{
	    $sql->close();
		die("Bereits installiert!");
	}


	/*--- install check --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground"); # Grunddesign
	$tpl->load("install");       # Installations Template
	$tpl->insertVar("SID",$_GET['SID']);

	//Elemente einfügen
	$ground_tpl->insertVar("path",REL_PATH);         # Sonderpfad
	$tpl->insertVar("path",REL_PATH);                # Sonderpfad
	$tpl->insertVar("upload",str_replace(ABS_PATH,"",$settings["pics"]["img_path"]));
	$ground_tpl->insertVar("title","Installation");    # Name
	$ground_tpl->insertVar("name","Pics");             # Titel
	$ground_tpl->insertVar("text",$tpl->getResult()); # Installationstemplate

	//ausgabe
	$retVal = $ground_tpl->getResult();

	//speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();
	
	return $retVal;

	/*--- Ausgabe --- ende --- */
}

?>
