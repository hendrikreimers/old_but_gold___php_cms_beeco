<?

/*
   BESCHREIBUNG:

   Importiert die Sicherungsdatei

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
	$path    = REL_PATH;                                 # Pfad zu den einzubindenden Scripten
	require(PLUGIN_PATH."/includes/functions/import.functions.php"); # Import Funktionen

	//Installationspruefung
	if ( !$sql->install_check("backup") )
	{
	    $sql->close();
	    die("<b>Backup noch nicht installier!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- defaults --- ende --- */



	/*--- Import --- start --- */

	if ( (crypt_password($_POST['pass'],$settings["base"]["checksum"]) == base64_decode($settings["base"]["pass"])) && (file_exists($_FILES['backup']['tmp_name'])) )
	{
	    if ( importXML($_FILES['backup']['tmp_name'],$sql,$settings) )
		{
			$status = message(PLUGIN_PATH,"backup","true",$SID);
		}
		else
		{
			$status = message(PLUGIN_PATH,"backup","false",$SID);
		}
	} 
	else
	{
		$status = message(PLUGIN_PATH,"backup","false",$SID);
	}

	/*--- Import --- start --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title","Backup");
	$ground_tpl->insertVar("name","backup");

	$ground_tpl->insertVar("text",$status);

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