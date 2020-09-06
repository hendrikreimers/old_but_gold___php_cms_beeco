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
	$path    = REL_PATH; # Pfad zu den einzubindenden Scripten

	//Gästebuch funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");        # Neue MySQL Klasse
	require(USER_PATH."/settings/plugins/gbook/other.settings.php"); # Gästebuch Sonderkonfiguration

	// Globalisieren für die update Funktion
	$GLOBALS['settings'] = $settings;

	//Köasse initialiseren
	$gbsql = new gbook_mysql_class;              # Objekt erstellen
	$gbsql->prefix = $settings["mysql"]["table_prefix"];

	//Installationsprüfung
	if ( !$sql->install_check("gbook") )
	{
		$sql->close();
		die("<b>Gästebuch noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- defaults --- ende --- */



	/*--- löschen --- start --- */

	//ID angegeben?
	if ( $_POST['id'] )
	{
		if ( $gbsql->update_entry($_POST['id'],htmlentities(strip_tags($_POST['name'])),htmlentities(strip_tags($_POST['email'])),htmlentities(strip_tags($_POST['icq'])),htmlentities(strip_tags($_POST['homepage'])),( ($settings["gbook"]["enable_html"] == "1") ? $_POST['text'] : strip_tags($_POST['text']) ),htmlentities(strip_tags($_POST['comment']))) )
		{
			$content = message(PLUGIN_PATH,"gbook","upd_true",$SID);
		}
		else $content = message(PLUGIN_PATH,"gbook","upd_false",$SID);
	}

	/*--- löschen --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");
	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["gbook"]["title"]));
	$ground_tpl->insertVar("name",$settings["gbook"]["name"]);
	$ground_tpl->insertVar("text",$content);

	//Alles ausgeben
	$retVal = $ground_tpl->getResult();

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>