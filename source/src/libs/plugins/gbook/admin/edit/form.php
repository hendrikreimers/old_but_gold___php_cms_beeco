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
	$path    = REL_PATH;                                 # Pfad zu den einzubindenden Scripten
	$tpl->path = PLUGIN_PATH."/templates/edit/";             # Template pfad (für den inhalt)

	//Gästebuch funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$gbsql = new gbook_mysql_class;              # Objekt erstellen
	$gbsql->prefix = $settings["mysql"]["table_prefix"];

	//Installationsprüfung
	if ( !$sql->install_check("gbook") )
	{
		$sql->close();
		die("<b>Gästebuch noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- defaults --- ende --- */



	/*--- einträge --- start --- */

	//Template
	$tpl->load("form");

	//Eintrag laden
	$entry = $gbsql->load_entry($_GET['id']);

	//In Template einfügen
	$tpl->insertVar("id",$entry["id"]);
	$tpl->insertVar("ip",$entry["ip"]);
	$tpl->insertVar("host",$entry["host"]);
	$tpl->insertVar("date",$entry["date"]);
	$tpl->insertVar("time",$entry["time"]);
	$tpl->insertVar("name",$entry["name"]);
	$tpl->insertVar("email",$entry["email"]);
	$tpl->insertVar("icq",$entry["icq"]);
	$tpl->insertVar("homepage",$entry["homepage"]);
	$tpl->insertVar("text",htmlspecialchars($entry["text"]));
	$tpl->insertVar("comment",htmlspecialchars($entry["comment"]));

	/*--- einträge --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	//Standard Informationen einsetzen
	$tpl->insertVar("path",$path);
	$tpl->insertVar("SID",$SID);

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["gbook"]["title"]));
	$ground_tpl->insertVar("name",$settings["gbook"]["name"]);

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