<?

/*
   BESCHREIBUNG:

   Entfernt die Bildergallerie

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
	require(PLUGIN_PATH."/includes/functions/uninstall.functions.php");
	require(USER_PATH."/settings/plugins/pics/other.settings.php"); # Zusätzliche Konfigurationen
	
	/*--- defaults --- ende --- */
	
	
	
	/*--- check --- start --- */
	
	//Wurde der YES Button gedrückt?
	if ( $_POST['doit'] == "Ja" )
	{
		//Erfolgsmeldung bei korrektem löschen der Tabellen
		if ( uninstall(base64_encode(crypt_password($_POST['password'],$settings["base"]["checksum"])),$settings["mysql"]["table_prefix"],$settings["pics"]["img_path"]) )
		{
			$text = message(PLUGIN_PATH,"pics","uninstall_true",$SID);
		}
		else $text = message(PLUGIN_PATH,"pics","uninstall_false",$SID);
	}
	else $text = message(PLUGIN_PATH,"pics","uninstall_false",$SID);
	
	/*---check --- ende --- */
	
	
	
	/*--- Ausgabe --- start --- */
	
	//Templates laden
	$ground_tpl->load("ground");
	
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title","Deinstallation");
	$ground_tpl->insertVar("name","pics");
	
	$ground_tpl->insertVar("text",$text);
	
	//Alles ausgeben
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