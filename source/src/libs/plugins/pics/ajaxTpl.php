<?

/*
   BESCHREIBUNG:
   
   Laedt das Template fuer AJAX Funktionen (Einblendung)
   
*/

function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	require(USER_PATH."/settings/plugins/pics/other.settings.php");

	//Installationsprüfung
	if ( !$sql->install_check("pics") )
	{
		$sql->close();
		die("<b>Bildergalerie noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	//Plugin Template
	$plugtpl = new template_class;        												           # Neues Objekt
	$plugtpl->path = PLUGIN_PATH."/templates/"; # Template Pfad
	$plugtpl->load("xmlTpl_AJAX"); # Template laden

	//Bei einblendung mit AJAX template laden
	$subtpl = new template_class;                         # Neues Objekt
	$subtpl->path = USER_PATH."/templates/plugins/pics/"; # Template Pfad
	$subtpl->load("details_ajax");                        # Template laden
	$subtpl->insertVar("path",REL_PATH."/user");

	$plugtpl->insertVar("tpl",trim($subtpl->getResult()));
	    
	$subtpl->clear();
	unset($subtpl);
	
	//Plugin Template für Ausgabe bereit machen
	$content = utf8_encode($plugtpl->getResult());

	//Inhalt ins Grund Template einfügen (nur wenn es kein popup fenster ist)
	header("Content-Type: text/xml");
	echo $content;

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$tpl->clear();
	$plugtpl->clear();
}

?>