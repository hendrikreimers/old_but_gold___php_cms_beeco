<?

/*
   BESCHREIBUNG:

   Zeigt das passende Formular
   um die Einstellungen des Gästebuchs ändern zu können

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
	
	//Install check
	if ( !$sql->install_check("gbook") ) { die("nicht installiert"); }

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$tpl->path = PLUGIN_PATH."/templates/settings/";
	$tpl->load("index");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["gbook"]["title"]));
	$ground_tpl->insertVar("name",$settings["gbook"]["name"]);

	$tpl->insertVar("SID",$SID);
	$tpl->insertVar("path",$path);
	$tpl->insertVar("max_entries",$settings["gbook"]["max_entries"]);
	$tpl->insertVar("notice_email",base64_decode($settings["gbook"]["notice_email"]));

	//Pflichtfelder anhacken
	$tpl->insertVar("html_checked",(($settings["gbook"]["enable_html"]  == "1") ? " selected" : ""));
	$tpl->insertVar("notice_checked",(($settings["gbook"]["send_notice"]  == "1") ? " selected" : ""));
	$tpl->insertVar("security_checked",(($settings["gbook"]["security"]  == "1") ? "checked" : ""));

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