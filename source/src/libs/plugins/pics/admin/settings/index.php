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
	if ( !$sql->install_check("pics") ) { die("nicht installiert"); }
	
	/*--- defaults --- ende --- */
	
	
	
	/*--- Ausgabe --- start --- */
	
	//Templates laden
	$ground_tpl->load("ground");
	
	$tpl->path = PLUGIN_PATH."/templates/settings/";
	$tpl->load("index");
	
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["pics"]["title"]));
	$ground_tpl->insertVar("name",$settings["pics"]["name"]);
	
	$tpl->insertVar("SID",$SID);
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("max_cols",base64_decode($settings["pics"]["max_cols"]));
	$tpl->insertVar("max_rows",base64_decode($settings["pics"]["max_rows"]));
	$tpl->insertVar("max_preview_width",base64_decode($settings["pics"]["max_preview_width"]));
	$tpl->insertVar("max_details_width",base64_decode($settings["pics"]["max_details_width"]));
	$tpl->insertVar((( $settings["pics"]["auto_resize"] == "1" ) ? "auto_resize_true" : "auto_resize_false")," selected");
	$tpl->insertVar((( $settings["pics"]["view"] == "1" ) ? "view_select_trueA" : (($settings["pics"]["view"] == "2") ? "view_select_trueB"  : "view_select_false") )," selected");
	$tpl->insertVar((( $settings["pics"]["order_groups"] == "ASC" ) ? "orderG_select_true" : "orderG_select_false")," selected");
	$tpl->insertVar((( $settings["pics"]["order_items"] == "ASC" ) ? "orderI_select_true" : "orderI_select_false")," selected");
	
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