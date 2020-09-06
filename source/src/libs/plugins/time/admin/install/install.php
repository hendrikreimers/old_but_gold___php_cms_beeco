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
	if ( $sql->install_check("time") )
	{
	    $sql->close();
		die("Bereits installiert!");
	}

	/*--- Installationsprüfung --- ende --- */



	/*--- Check --- start --- */

	//Mengen Angaben falsch?
	if ( !preg_match("/^([#]?)([a-z0-9]*)$/i",$_POST['color_past']) )
	{
	    $_POST['color_past'] = "red";
	}

	//Mengen Angaben falsch?
	if ( !preg_match("/^([#]?)([a-z0-9]*)$/i",$_POST['color_present']) )
	{
	    $_POST['color_present'] = "black";
	}

	/*--- Check --- ende --- */



	/*--- installation --- start --- */

	//Ergebnis Ausgabe
	$text = "<b>Installation erfolgreich!</b><br><br>".
	        "Klicken Sie <a href=\"?action=main&plugin=time&SID=".$_POST['SID']."\">hier</a> ".
	        "um in den<br>Admin-Bereich zu gelangen.";
            
	install($settings);
	send_message("time");
    
	@mysql_close() or die(_sqlerror(__FILE__,__LINE__,"Verbindung konnte nicht geschlossen werden",mysql_error()));

	/*--- installation --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground"); # Grunddesign

	$ground_tpl->insertVar("path",REL_PATH);         # Sonderpfad
	$ground_tpl->insertVar("title","Installation");    # Name
	$ground_tpl->insertVar("name","Time");             # Titel
	$ground_tpl->insertVar("text",$text);              # Text

	//ausgabe
	$retVal = $ground_tpl->getResult();

	//speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;

	/*--- Ausgabe --- ende --- */
}

?>