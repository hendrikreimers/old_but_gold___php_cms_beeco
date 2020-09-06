<?

/*
   BESCHREIBUNG:
   
   Installiert das Plugin
   
*/
function initialize($dObj)
{

	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];
	
	/*--- Grundfunktionen --- start --- */

	//Benötigte Dateien einbinden
	require(PLUGIN_PATH."/includes/functions/install.functions.php");    # Installationsroutinen
	require(BASE_PATH."/includes/functions/install.functions.php");    # Installationsroutinen
	require(USER_PATH."/settings/plugins/pics/other.settings.php"); # Zusätzliche Konfiguration

	/*--- Grundfunktionen --- ende --- */



	/*---schreibrechte prüfen --- start --- */

	$fp = @fopen($settings["pics"]["img_path"]."/test.tmp","w+");

	if ( !@fputs($fp,"test") )
	{
	    die("In <b>".$settings["pics"]["img_path"]."</b> kann nicht geschrieben werden!!!<br>
	         Stellen Sie zuvor die Schreibrechte auf 0777 bevor<br>Sie das Plugin installieren");
	}
	else
	{
	    fclose($fp);
	    unlink($settings["pics"]["img_path"]."/test.tmp");
	}

	/*---schreibrechte prüfen --- ende --- */



	/*--- Installationsprüfung --- start --- */

	//Installationsprüfung
	if ( $sql->install_check("pics") )
	{
    	$sql->close();
		die("Bereits installiert!");
	}

	/*--- Installationsprüfung --- ende --- */



	/*--- Check --- start --- */

	//Mengen Angaben falsch?
	if ( !preg_match("/^([0-9]*)$/i",$_POST['max_cols']) )
	{
	    $_POST['max_cols'] = "4";
	}

	if ( !preg_match("/^([0-9]*)$/i",$_POST['max_rows']) )
	{
	    $_POST['max_rows'] = "3";
	}

	if ( !preg_match("/^([0-9]*)$/i",$_POST['max_preview_width']) )
	{
	    $_POST['max_preview_width'] = "150";
	}

	if ( !preg_match("/^([0-9]*)$/i",$_POST['max_details_width']) )
	{
	    $_POST['max_details_width'] = "450";
	}

	/*--- Check --- ende --- */



	/*--- installation --- start --- */

	//Ergebnis Ausgabe
	$text = "<b>Installation erfolgreich!</b><br><br>".
	        "Klicken Sie <a href=\"?action=main&plugin=pics&SID=".$_POST['SID']."\">hier</a> ".
	        "um in den<br>Admin-Bereich zu gelangen.";
            
	install($settings);
	send_message("pics");

	@mysql_close() or die(_sqlerror(__FILE__,__LINE__,"Verbindung konnte nicht geschlossen werden",mysql_error()));

	/*--- installation --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground"); # Grunddesign

	$ground_tpl->insertVar("path",REL_PATH);         # Sonderpfad
	$ground_tpl->insertVar("title","Installation");    # Name
	$ground_tpl->insertVar("name","Pics");             # Titel
	$ground_tpl->insertVar("text",$text);              # Text

	//ausgabe
	$retVal = $ground_tpl->getResult();

	//speicher freigeben
	$ground_tpl->clear();

	return $retVal;

	/*--- Ausgabe --- ende --- */
}

?>
