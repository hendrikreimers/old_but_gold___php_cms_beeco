<?

/*
   BESCHREIBUNG:

   Speichert die ID in die Session und wird somit von
   der index.php ignoriert

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
	$tpl->path .= "move/";                                        # Template pfad (für den inhalt)
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Standard Nachricht
	$text = message(BASE_PATH,"move","cut_false",$SID);

	//Templates laden
	$ground_tpl->load("ground");

	if ( $_GET['id'] > 0 )
	{
		$_SESSION['cut_id'] = $_GET['id'];
		$text = message(BASE_PATH,"move","cut_true",$SID);
	}

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);

	$ground_tpl->insertVar("text",$text);

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