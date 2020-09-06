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

	$tpl->path        = PLUGIN_PATH."/templates/"; # Pfad zu den Templates

	/*--- Grundfunktionen --- ende --- */



	/*--- install check --- start --- */

	//Verbindung herstellen
	$sql->connect($settings["mysql"]["host"],$settings["mysql"]["user"],$settings["mysql"]["pass"],$settings["mysql"]["db"]);

	//Installationsprüfung
	if ( $sql->install_check("gbook") )
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
	$ground_tpl->insertVar("title","Installation");    # Name
	$ground_tpl->insertVar("name","Gästebuch");        # Titel
	$ground_tpl->insertVar("text",$tpl->getResult()); # Installationstemplate

	//ausgabe
	echo $ground_tpl->getResult();

	//speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();

	/*--- Ausgabe --- ende --- */
}

?>