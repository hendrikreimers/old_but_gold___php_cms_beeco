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

	//Ben�tigte Dateien einbinden
	require(PLUGIN_PATH."/includes/functions/install.functions.php");                      # Installationsroutinen
	require(BASE_PATH."/includes/functions/install.functions.php");

	/*--- Grundfunktionen --- ende --- */



	/*--- Installationspr�fung --- start --- */

	//Installationspr�fung
	if ( $sql->install_check("gbook") )
	{
	    $sql->close();
		die("Bereits installiert!");
	}

	/*--- Installationspr�fung --- ende --- */



	/*--- Check --- start --- */

	//Mengen Angaben falsch?
	if ( !preg_match("/^([0-9]*)$/i",$_POST['max_entries']) )
	{
	    $_POST['max_entries'] = "5";
	}

	/*--- Check --- ende --- */



	/*--- installation --- start --- */

	//Ergebnis Ausgabe
	$text = "<b>Installation erfolgreich!</b><br><br>".
	        "Klicken Sie <a href=\"?action=main&plugin=gbook&SID=".$_POST['SID']."\">hier</a> ".
	        "um in den<br>Admin-Bereich zu gelangen.";
            
	install($settings);
	send_message("gbook");
   
	@mysql_close() or die(_sqlerror(__FILE__,__LINE__,"Verbindung konnte nicht geschlossen werden",mysql_error()));

	/*--- installation --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground"); # Grunddesign

	$ground_tpl->insertVar("path",REL_PATH);           # Sonderpfad
	$ground_tpl->insertVar("title","Installation");    # Name
	$ground_tpl->insertVar("name","G�stebuch");        # Titel
	$ground_tpl->insertVar("text",$text);              # Text

	//ausgabe
	echo $ground_tpl->getResult();

	//speicher freigeben
	$ground_tpl->clear();

	/*--- Ausgabe --- ende --- */
}

?>
