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

	//Standard Aktionen laden                                         # Template pfad (für den inhalt)
	$path    = REL_PATH;                                    # Pfad zu den einzubindenden Scripten

	//Install check
	if ( !$sql->install_check("kontakt") ) { die("nicht installiert"); }

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["kontakt"]["title"]));
	$ground_tpl->insertVar("name",$settings["kontakt"]["name"]);

	$text = message(PLUGIN_PATH,"kontakt","upd_false",$SID);

	if ( $_POST['mailto'] )
	{
	    $new_settings["kontakt"]["mailto"]    = base64_encode($_POST['mailto']);
	    $new_settings["kontakt"]["nachname"]  = ($_POST['nachname']  == "1") ? "1" : "0";
	    $new_settings["kontakt"]["vorname"]   = ($_POST['vorname']   == "1") ? "1" : "0";
	    $new_settings["kontakt"]["firma"]     = ($_POST['firma']     == "1") ? "1" : "0";
	    $new_settings["kontakt"]["strasse"]   = ($_POST['strasse']   == "1") ? "1" : "0";
	    $new_settings["kontakt"]["plz"]       = ($_POST['plz']       == "1") ? "1" : "0";
	    $new_settings["kontakt"]["ort"]       = ($_POST['ort']       == "1") ? "1" : "0";
	    $new_settings["kontakt"]["telefon"]   = ($_POST['telefon']   == "1") ? "1" : "0";
	    $new_settings["kontakt"]["email"]     = ($_POST['email']     == "1") ? "1" : "0";
	    $new_settings["kontakt"]["nachricht"] = ($_POST['nachricht'] == "1") ? "1" : "0";
		$new_settings["kontakt"]["security"]  = ($_POST['security'] == "1") ? "1" : "0";
    
	    $sql->update_settings("kontakt",$new_settings,"1");
    
	    $text = message(PLUGIN_PATH,"kontakt","upd_true",$SID);
	}

	$ground_tpl->insertVar("text",$text);

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