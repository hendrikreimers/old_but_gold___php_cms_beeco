<?

/*
   BESCHREIBUNG:
   
   Baut das Installationsformular in das Grdunddesign des Admin
   Bereiches ein.
   
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
	require(PLUGIN_PATH."/includes/functions/install.functions.php");                      # Installationsroutinen
	require(BASE_PATH."/includes/functions/install.functions.php");

	/*--- Grundfunktionen --- ende --- */



	/*--- Installationsprüfung --- start --- */

	//Installationsprüfung
	if ( $sql->install_check("kontakt") )
	{
	    $sql->close();
		die("Bereits installiert!");
	}

	/*--- Installationsprüfung --- ende --- */



	/*--- Check --- start --- */
	
	//Variablen initialisieren
	$uncorrect = 0;                                                       # Fehler-Prüfsumme
	$text    = "<B>Bitte überprüfen Sie Ihre Eingaben</B><br>".
	           "Klicken Sie dazu im Browser auf \"Zur&uuml;ck\"<br><br>"; # Fehler Ausgabe Text

	//URL Eingabe korrekt?
	if ( ($_POST['mailto'] == "") )
	{
	    $uncorrect = 1;
	    $text   .= "- Ungültigen E-Mail Empfänger angegeben!<br>";
	}

	/*--- Check --- ende --- */



	/*--- installation --- start --- */

	//Installation bei bedarf beginnen
	if ( $uncorrect == "0" )
	{
	    //Ergebnis Ausgabe
	    $text = "<b>Installation erfolgreich!</b><br><br>".
	            "Klicken Sie <a href=\"?plugin=kontakt&action=main&SID=".$_POST['SID']."\">hier</a> ".
	            "um in den<br>Admin-Bereich zu gelangen.";
	            
	    install($settings);
	    send_message("kontakt");
	    
	    @mysql_close() or die(_sqlerror(__FILE__,__LINE__,"Verbindung konnte nicht geschlossen werden",mysql_error()));
	}

	/*--- installation --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground"); # Grunddesign

	$ground_tpl->insertVar("path",REL_PATH);                # Sonderpfad
	$ground_tpl->insertVar("title","Installation");           # Name
	$ground_tpl->insertVar("name","Kontakt-Formular");        # Titel
	$ground_tpl->insertVar("text",$text);                     # Text

	//ausgabe
	$retVal = $ground_tpl->getResult();

	//speicher freigeben
	$ground_tpl->clear();

	return $retVal;

	/*--- Ausgabe --- ende --- */
}

?>