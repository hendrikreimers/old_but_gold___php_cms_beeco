<?

/*
   BESCHREIBUNG:
   
   Zeigt das Formular f�r die Grundeinstellungen an
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
	$tpl->path .= "settings/";                                # Template pfad (f�r den inhalt)
	$path    = REL_PATH;                                     # Pfad zu den einzubindenden Scripten

	/*--- defaults --- ende --- */
	
	
	
	/*--- ausgabe --- start --- */
	
	//Templates laden
	$ground_tpl->load("ground");
	$tpl->load("index");
	
	//Standard Variablen einf�gen
	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);
	
	//Formular ausf�llen
	$tpl->insertVar("path",$path);                              	             # Pfad zum Stammverzeichnis
	$tpl->insertVar("SID",$SID);                              	    	         # Session ID
	$tpl->insertVar("url",base64_decode($settings["base"]["url"]));              # URL zur Startseite
	$tpl->insertVar("tagfilter",base64_decode($settings["base"]["tagfilter"]));  # Tags f�r den Filter
	$tpl->insertVar("username",base64_decode($settings["base"]["user"]));        # Benutzername
	
	//Select Felder ausw�hlen
	$tpl->insertVar((($settings["base"]["nl2br"] == "0" ) ? "nl2br_selected_no" : ( ($settings["base"]["nl2br"] == "1") ? "nl2br_selected_yes" : "nl2br_selected_auto"))," selected"); # NL2BR Select Auswahl
	$tpl->insertVar((($settings["base"]["template_override"] == "1") ? "override_selected_yes" : "override_selected_no")," selected");
	
	//Formular einf�gen
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