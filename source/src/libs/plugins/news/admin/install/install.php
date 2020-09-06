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
	require(PLUGIN_PATH."/includes/functions/install.functions.php"); # Installationsroutinen
	require(BASE_PATH."/includes/functions/install.functions.php"); # Installationsroutinen

	/*--- Grundfunktionen --- ende --- */



	/*--- Installationsprüfung --- start --- */

	//Installationsprüfung
	if ( $sql->install_check("news") )
	{
	    $sql->close();
		die("Bereits installiert!");
	}

	/*--- Installationsprüfung --- ende --- */



	/*--- Check --- start --- */

	//Mengen Angaben falsch?
	if ( !preg_match("/^([0-9]*)$/i",$_POST['max_entries']) )
	{
	    $_POST['max_entries'] = "5";
	}

	//Mengen Angaben falsch?
	if ( !preg_match("/^([0-9]*)$/i",$_POST['max_words']) )
	{
	    $_POST['max_words'] = "15";
	}

	/*--- Check --- ende --- */



	/*--- installation --- start --- */

	//Ergebnis Ausgabe
	$text = "<b>Installation erfolgreich!</b><br><br>".
	        "Klicken Sie <a href=\"?action=main&plugin=news&SID=".$_POST['SID']."\">hier</a> ".
	        "um in den<br>Admin-Bereich zu gelangen.";
            
	install($settings);
        send_message("news");
    
	@mysql_close() or die(_sqlerror(__FILE__,__LINE__,"Verbindung konnte nicht geschlossen werden",mysql_error()));

	/*--- installation --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground"); # Grunddesign

	$ground_tpl->insertVar("path",REL_PATH);         # Sonderpfad
	$ground_tpl->insertVar("title","Installation");    # Name
	$ground_tpl->insertVar("name","News");             # Titel
	$ground_tpl->insertVar("text",$text);              # Text

	//ausgabe
	$retVal = $ground_tpl->getResult();

	//speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;

	/*--- Ausgabe --- ende --- */
}

?>