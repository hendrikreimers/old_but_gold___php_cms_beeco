<?

/*
   BESCHREIBUNG:

   Löscht den ausgewählten Menüpunkt
   mit allen Untermenüs

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

	if ( !preg_match("/^[0-9]*$/i",$_POST['id']) ) { header("Location: index.php?SID=".$SID); }

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);

	//Fehler Ausgabe (wird bei erfolg überschrieben
	$text = message(BASE_PATH,"delete","false",$SID);

	if ( $_POST['id'] )
	{
	    $sql->delete_menue($_POST['id']);
	    $text = message(BASE_PATH,"delete","true",$SID);
	}

	$ground_tpl->insertVar("text",$text);
	$retVal = $ground_tpl->getResult();

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>