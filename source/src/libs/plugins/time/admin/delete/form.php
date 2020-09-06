<?

/*
   BESCHREIBUNG:

   Zeigt die verfügbaren Funktionen/Menüs
   der Administrationsoberfläche an

*/

function initialize($dObj)
{

	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];
	
	/*--- defaults --- start --- */

	//Standard Aktionen laden
	$tpl->path = PLUGIN_PATH."/templates/delete/";             # Template pfad (für den inhalt)

	//Installationsprüfung
	if ( !$sql->install_check("time") )
	{
		$sql->close();
		die("<b>time noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- defaults --- ende --- */



	/*--- einträge --- start --- */

	//Template
	$tpl->load("form");
	$tpl->insertVar("id",$_GET['id']);

	/*--- einträge --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	//Standard Informationen einsetzen
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("SID",$SID);

	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["time"]["title"]));
	$ground_tpl->insertVar("name",$settings["time"]["name"]);

	$ground_tpl->insertVar("text",$tpl->getResult());

	//Alles ausgeben
	$retVal = $ground_tpl->getResult();

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>