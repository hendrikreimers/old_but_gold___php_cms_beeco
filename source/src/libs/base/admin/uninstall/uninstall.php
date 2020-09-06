<?

/*
   BESCHREIBUNG:

   Zeigt das Sicherheitsformular für die Deinstallation an

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
	$tplpath = "uninstall/";                                  # Template pfad (für den inhalt)
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten

	/*--- defaults --- ende --- */



	/*--- check --- start --- */

	//Wurde der YES Button gedrückt?
	if ( $_POST['doit'] == "Ja" )
	{
	    //Pruefen ob Plugins noch installiert, anschlieend ob Deinstallieren korrekt
	    $plugins = $sql->load_plugins();
	    
		if ( sizeof($plugins[0]) > 0 ) {
			//Gibt noch Plugins
			$text = message(BASE_PATH,"uninstall","fplug",$SID);
	    } elseif ( $sql->uninstall(base64_encode(crypt_password($_POST['password'],$settings["base"]["checksum"]))) ) {
			//Erfolgsmeldung
	        $text = message(BASE_PATH,"uninstall","true");
	        Session_Close();
	    }
	    else $text = message(BASE_PATH,"uninstall","false",$SID);
	    
		unset($plugins);
	}
	else $text = message(BASE_PATH,"uninstall","false",$SID);

	/*---check --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");
	
	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title","Deinstallation");
	$ground_tpl->insertVar("name","Beeco L&ouml;schung");
	
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