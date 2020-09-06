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

	$tpl->path .= "main/";

	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");
	$tpl->load("index");

	//Standard Informationen einsetzen
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("SID",$SID);
	
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);

	$ground_tpl->insertVar("text",$tpl->getResult());

	//Alles ausgeben
	echo $ground_tpl->getResult();

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();

	/*--- abschluss --- ende --- */
}

?>