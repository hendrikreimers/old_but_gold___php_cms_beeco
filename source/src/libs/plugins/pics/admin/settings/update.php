<?

/*
   BESCHREIBUNG:

   Zeigt das passende Formular
   um die Einstellungen des News es ändern zu können

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
	if ( !$sql->install_check("pics") ) { die("nicht installiert"); }
	
	/*--- defaults --- ende --- */
	
	
	
	/*--- Ausgabe --- start --- */
	
	//Templates laden
	$ground_tpl->load("ground");
	
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["pics"]["title"]));
	$ground_tpl->insertVar("name",$settings["pics"]["name"]);
	
	$text = message(PLUGIN_PATH,"pics","upd_false",$SID);
	
	if ( $_POST['SID'] )
	{
		$new_settings["pics"]["max_cols"] = base64_encode((( preg_match("/^([0-9]*)$/i",trim($_POST['max_cols'])) ) ? $_POST['max_cols'] : "5"));
		$new_settings["pics"]["max_rows"] = base64_encode((( preg_match("/^([0-9]*)$/i",trim($_POST['max_rows'])) ) ? $_POST['max_rows'] : "5"));
		$new_settings["pics"]["max_preview_width"] = base64_encode((( preg_match("/^([0-9]*)$/i",trim($_POST['max_preview_width'])) ) ? $_POST['max_preview_width'] : "100"));
		$new_settings["pics"]["max_details_width"] = base64_encode((( preg_match("/^([0-9]*)$/i",trim($_POST['max_details_width'])) ) ? $_POST['max_details_width'] : "350"));
		$new_settings["pics"]["view"] = ( $_POST['view'] == "1" ) ? "1" : (($_POST['view'] == "2") ? "2" : "0");
		$new_settings["pics"]["auto_resize"] = ( $_POST['auto_resize'] == "1" ) ? "1" : "0";
		$new_settings["pics"]["order_groups"] = ( $_POST['order_groups'] == "1" ) ? "ASC" : "DESC";
		$new_settings["pics"]["order_items"] = ( $_POST['order_items'] == "1" ) ? "ASC" : "DESC";
		
		$sql->update_settings("pics",$new_settings,"1");
		
		$text = message(PLUGIN_PATH,"pics","upd_true",$SID);
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