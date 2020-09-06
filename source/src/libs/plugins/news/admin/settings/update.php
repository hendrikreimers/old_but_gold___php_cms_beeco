<?

/*
   BESCHREIBUNG:

   Ändert die Einstellungen in der DB

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
	if ( !$sql->install_check("news") ) { die("nicht installiert"); }

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["news"]["title"]));
	$ground_tpl->insertVar("name",$settings["news"]["name"]);

	$text = message(PLUGIN_PATH,"news","upd_false",$SID);

	if ( $_POST['SID'] )
	{
	    $new_settings["news"]["max_entries"] = ( preg_match("/^([0-9]*)$/i",trim($_POST['max_entries'])) ) ? $_POST['max_entries'] : "5";
	    $new_settings["news"]["max_words"]   = ( preg_match("/^([0-9]*)$/i",trim($_POST['max_words'])) ) ? $_POST['max_words'] : "15";
    
	    $sql->update_settings("news",$new_settings,"1");
    
	    $text = message(PLUGIN_PATH,"news","upd_true",$SID);
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