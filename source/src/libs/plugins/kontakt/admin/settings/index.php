<?

/*
   BESCHREIBUNG:

   Zeigt das passende Formular
   um die Einstellungen des Kontakts ändern zu können

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
	$path    = REL_PATH;                                    # Pfad zu den einzubindenden Scripten

	//Install check
	if ( !$sql->install_check("kontakt") ) { die("nicht installiert"); }

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$tpl->path = PLUGIN_PATH."/templates/settings/";
	$tpl->load("index");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["kontakt"]["title"]));
	$ground_tpl->insertVar("name",$settings["kontakt"]["name"]);

	$tpl->insertVar("SID",$SID);
	$tpl->insertVar("mailto",base64_decode($settings["kontakt"]["mailto"]));

	//Pflichtfelder anhacken
	$tpl->insertVar("nachname_checked",( ($settings["kontakt"]["nachname"]  == "1") ? " checked" : ""));
	$tpl->insertVar("vorname_checked",( ($settings["kontakt"]["vorname"]   == "1") ? " checked" : ""));
	$tpl->insertVar("firma_checked",( ($settings["kontakt"]["firma"]     == "1") ? " checked" : ""));
	$tpl->insertVar("strasse_checked",( ($settings["kontakt"]["strasse"]   == "1") ? " checked" : ""));
	$tpl->insertVar("plz_checked",( ($settings["kontakt"]["plz"]       == "1") ? " checked" : ""));
	$tpl->insertVar("ort_checked",( ($settings["kontakt"]["ort"]       == "1") ? " checked" : ""));
	$tpl->insertVar("telefon_checked",( ($settings["kontakt"]["telefon"]   == "1") ? " checked" : ""));
	$tpl->insertVar("email_checked",( ($settings["kontakt"]["email"]     == "1") ? " checked" : ""));
	$tpl->insertVar("nachricht_checked",( ($settings["kontakt"]["nachricht"] == "1") ? " checked" : ""));
	$tpl->insertVar("security_checked",( ($settings["kontakt"]["security"]  == "1") ? " checked" : ""));

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