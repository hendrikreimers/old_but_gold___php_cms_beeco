<?

/*
   BESCHREIBUNG:

   Zeigt die verfügbaren Funktionen/Menüs
   der Administrationsoberfläche an

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
	$tpl->path = PLUGIN_PATH."/templates/delete/";             # Template pfad (für den inhalt)

	//time funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$gbsql = new time_mysql_class;              # Objekt erstellen
	$gbsql->prefix = $settings["mysql"]["table_prefix"];

	//Installationsprüfung
	if ( !$sql->install_check("time") )
	{
		$sql->close();
		die("<b>time noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- defaults --- ende --- */



	/*--- einträge --- start --- */

	//Template
	$tpl->load("index",1);

	//Einträge für die aktuelle Seite laden
	$entries = $gbsql->load_entries();

	//Alle Einträge hinzufügen
	if ( $entries )
	{
		foreach ( $entries as $entry )
		{	
			//Einfügen
			$entry["path"] = REL_PATH;
			$entry["SID"]  = $SID;

			//Datum filtern um alte von aktuellen Terminen zu trennen
			$date            = explode(".",$entry["date"]);              # Datum auftrennen
			$curT            = time();                                   # Aktuelle Zeit
			$entT            = mktime(0,0,0,$date[1],$date[0],$date[2]); # Zeit des Eintrages
			$entry["weight"] = ( $entT < $curT ) ? "0" : "bold";         # Entscheiden!

			//Speicher freigeben
			unset($date);
			unset($entT);
			unset($curT);

			//Einfügen
			$tpl->insertArray($entry,array("entries"));
		}
	}

	/*--- einträge --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	//Standard Informationen einsetzen
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("SID",$SID);

	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["time"]["title"]));
	$ground_tpl->insertVar("name",$settings["time"]["name"]);

	$ground_tpl->insertVar("text",$tpl->getResult());

	//Alles ausgeben
	$retVal = $ground_tpl->getResult();

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>