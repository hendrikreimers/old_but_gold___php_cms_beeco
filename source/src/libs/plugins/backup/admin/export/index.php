<?

/*
   BESCHREIBUNG:

   Exportiert alle Daten aus der DB

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
	require(PLUGIN_PATH."/includes/functions/export.functions.php"); # Export Funktionen

	//Installationspruefung
	if ( !$sql->install_check("backup") )
	{
	    $sql->close();
	    die("<b>Backup noch nicht installier!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- defaults --- ende --- */



	/*--- Vorbereitung --- start --- */

	//XML Datei erstellen
	$xml = createXML($tpl,$sql,$settings);

	/*--- Vorbereitung --- ende --- */



	/*--- abschluss --- start --- */

	header("Content-Type: application/x-force-download");
	header("Content-Disposition: attachment; filename=\"cms-backup.xml\"");
	echo base64_encode($xml);

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$tpl->clear();

	/*--- abschluss --- ende --- */
}

?>