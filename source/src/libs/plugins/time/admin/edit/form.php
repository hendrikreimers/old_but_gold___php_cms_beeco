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

	$tpl->path = PLUGIN_PATH."/templates/edit/";             # Template pfad (für den inhalt)

	//time funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");   # Neue MySQL Klasse
	$gbsql = new time_mysql_class;                       # Objekt erstellen
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
	$tpl->load("form");

	//Eintrag laden
	$entry = $gbsql->load_entry($_GET['id']);

	//In Template einfügen
	$tpl->insertVar("id",$entry["id"]);
	$tpl->insertVar("title",$entry["title"]);

	//FckEditor vorbereiten
	$tpl->insertVar("text",stripslashes(base64_decode($entry['text'])));
	$tpl->insertVar("date_y",substr($entry["date"],6,4));
	$tpl->insertVar("date_m",substr($entry["date"],3,2));
	$tpl->insertVar("date_d",substr($entry["date"],0,2));

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
	$ground_tpl->insertVar("SID",$SID);

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