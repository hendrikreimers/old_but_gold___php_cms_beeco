<?

/*
   BESCHREIBUNG:

   Zeigt das passende Formular
   um eine Bestätigung anzuzeigen zum Löschen

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
	$tpl->path .= "delete/";                                     # Template pfad (für den inhalt)
	$path       = REL_PATH;                                       # Pfad zu den einzubindenden Scripten
	
	//Variablen definieren
	if ( !preg_match("=^([0-9]*)$=",$_GET['id']) ) { header("location: index.php?SID=".$SID); }

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);

	if ( $_GET['id'] )
	{
		//Passendes Formular laden
	    $tpl->load("form");
    
	    //Standard Informationen einsetzen
	    $tpl->insertVar("path",$path);
	    $tpl->insertVar("SID",$SID);
	    $tpl->insertVar("id",$_GET['id']);

		//Ergebnis
		$text = $tpl->getResult();
	}

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