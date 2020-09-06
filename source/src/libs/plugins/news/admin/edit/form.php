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
	require(BASE_PATH."/includes/functions/text.functions.php");  # Text Funktionen
	$tpl->path = PLUGIN_PATH."/templates/edit/";             # Template pfad (für den inhalt)

	//News funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");   # Neue MySQL Klasse
	$gbsql = new news_mysql_class;                       # Objekt erstellen
	$gbsql->prefix = $settings["mysql"]["table_prefix"];

	//Installationsprüfung
	if ( !$sql->install_check("news") )
	{
		$sql->close();
		die("<b>News noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
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

	$tpl->insertVar("time_h",substr($entry["time"],0,2));
	$tpl->insertVar("time_m",substr($entry["time"],3,2));

	$tpl->insertVar("date_y",substr($entry["date"],6,4));
	$tpl->insertVar("date_m",substr($entry["date"],3,2));
	$tpl->insertVar("date_d",substr($entry["date"],0,2));

	//Content einbauen
	$tpl->insertVar("content",stripslashes($entry['text']));	

	/*--- einträge --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	//Standard Informationen einsetzen
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("SID",$SID);

	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["news"]["title"]));
	$ground_tpl->insertVar("name",$settings["news"]["name"]);

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