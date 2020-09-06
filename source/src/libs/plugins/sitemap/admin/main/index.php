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
	$tpl->path = PLUGIN_PATH."/templates/main/";               # Template pfad (für den inhalt)

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");
	$tpl->load("index");
	
	//Standard Informationen einsetzen
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("SID",$SID);
	
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title","Backup");
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
