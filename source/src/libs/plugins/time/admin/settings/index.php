<?

/*
   BESCHREIBUNG:

   Zeigt das passende Formular
   um die Einstellungen des Newss ändern zu können

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

	//Variablen definieren
	if ( !preg_match("=^([0-9]*)$=",$_GET['id']) ) { header("Location: index.php?SID=".$SID); }

	//Install check
	if ( !$sql->install_check("time") ) { die("nicht installiert"); }

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$tpl->path = PLUGIN_PATH."/templates/settings/";
	$tpl->load("index");

	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["time"]["title"]));
	$ground_tpl->insertVar("name",$settings["time"]["name"]);

	$tpl->insertVar("SID",$SID);
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("color_past",base64_decode($settings["time"]["color_past"]));
	$tpl->insertVar("color_present",base64_decode($settings["time"]["color_present"]));

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