<?

/*
   BESCHREIBUNG:

   Zeigt das passende Formular
   um die Einstellungen des Gästebuches ändern zu können

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
	$path    = REL_PATH;                                    # Pfad zu den einzubindenden Scripten

	//Install check
	if ( !$sql->install_check("gbook") ) { die("nicht installiert"); }

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["gbook"]["title"]));
	$ground_tpl->insertVar("name",$settings["gbook"]["name"]);

	$text = message(PLUGIN_PATH,"gbook","updsets_false",$SID);

	if ( $_POST['SID'] )
	{
	    $new_settings["gbook"]["max_entries"]  = ( preg_match("/^([0-9]*)$/i",trim($_POST['max_entries'])) ) ? $_POST['max_entries'] : "5";
	    $new_settings["gbook"]["enable_html"]  = ($_POST['enable_html'] == "1") ? "1" : "0";
	    $new_settings["gbook"]["send_notice"]  = (($_POST['send_notice'] == "1") && ($_POST['notice_email'])) ? "1" : "0";
	    $new_settings["gbook"]["notice_email"] = base64_encode($_POST['notice_email']);
		$new_settings["gbook"]["security"]     = ($_POST['security']) ? "1" : "0";
    
	    $sql->update_settings("gbook",$new_settings,"1");
    
	    $text = message(PLUGIN_PATH,"gbook","updsets_true",$SID);
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