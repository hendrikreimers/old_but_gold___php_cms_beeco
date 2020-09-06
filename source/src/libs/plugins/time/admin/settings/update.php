<?

/*
   BESCHREIBUNG:

   Übernimmt die Einstellungen

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

	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["time"]["title"]));
	$ground_tpl->insertVar("name",$settings["time"]["name"]);

	$text = message(PLUGIN_PATH,"news","upd_false",$SID);

	if ( $_POST['SID'] )
	{
	    $new_settings["time"]["color_past"]    = ( preg_match("/^([#]?)([0-9a-z]*)$/i",trim($_POST['color_past'])) ) ? base64_encode($_POST['color_past']) : base64_encode("red");
	    $new_settings["time"]["color_present"] = ( preg_match("/^([#]?)([0-9a-z]*)$/i",trim($_POST['color_present'])) ) ? base64_encode($_POST['color_present']) : base64_encode("black");
    
	    $sql->update_settings("time",$new_settings,"1");
    
	    $text = message(PLUGIN_PATH,"time","upd_true",$SID);
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