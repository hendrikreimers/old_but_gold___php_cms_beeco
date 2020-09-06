<?

/*
   BESCHREIBUNG:

   Zeigt das Sicherheitsformular für die Deinstallation an

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
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten

	//Installationspruefung
	if ( !$sql->install_check("backup") )
	{
	    $sql->close();
	    die("<b>Backup noch nicht installier!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$tpl->path = PLUGIN_PATH."/templates/uninstall/";
	$tpl->load("index");

	//Standard Informationen einsetzen
	$tpl->insertVar("path",$path);
	$tpl->insertVar("SID",$SID);

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title","Backup-System");
	$ground_tpl->insertVar("name","backup");

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