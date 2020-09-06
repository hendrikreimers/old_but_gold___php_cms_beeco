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
	//require(PLUGIN_PATH."/includes/functions/install.functions.php"); # Funktionen für die Installation nötig

	//Installation: Template Klasse initialisieren
	$tpl->path        = PLUGIN_PATH."/templates/"; # Pfad zu den Templates

	/*--- Grundfunktionen --- ende --- */



	/*--- install check --- start --- */

	//Installationsprüfung
	if ( $sql->install_check("search") )
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
	$ground_tpl->insertVar("title","Installation");    # Name
	$ground_tpl->insertVar("name","Suche");            # Titel
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