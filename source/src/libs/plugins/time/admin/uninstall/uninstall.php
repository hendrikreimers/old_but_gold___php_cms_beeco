<?

/*
   BESCHREIBUNG:

   Entfernt das Kontaktfomular

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
	$tpl->path = PLUGIN_PATH."/templates/uninstall/"; # Template pfad (für den inhalt)
	$path      = REL_PATH;                            # Pfad zu den einzubindenden Scripten
	require(PLUGIN_PATH."/includes/functions/uninstall.functions.php");
	#require("../../includes/settings/messages.settings.php");

	/*--- defaults --- ende --- */



	/*--- check --- start --- */

	//Wurde der YES Button gedrückt?
	if ( $_POST['doit'] == "Ja" )
	{
	    //Erfolgsmeldung bei korrektem löschen der Tabellen
	    if ( uninstall(base64_encode(crypt_password($_POST['password'],$settings["base"]["checksum"])),$settings["mysql"]["table_prefix"]) )
	    {
	        $text = message(PLUGIN_PATH,"time","uninstall_true",$SID);
	    }
	    else $text = message(PLUGIN_PATH,"time","uninstall_false",$SID);
	}
	else $text = message(PLUGIN_PATH,"time","uninstall_false",$SID);

	/*---check --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title","Deinstallation");
	$ground_tpl->insertVar("name","time");

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