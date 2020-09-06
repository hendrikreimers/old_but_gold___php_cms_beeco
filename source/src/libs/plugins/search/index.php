<?

/*
   BESCHREIBUNG:
   
   Zeigt die Menüpunkte an und den inhalt der vom plugin geladen wird
   
*/

function initialize($dObj)
{

	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	#$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];
	
	/*--- Grundaktionen --- start --- */
	
	//Installationspruefung
	if ( !$sql->install_check("search") )
	{
	    $sql->close();
	    die("<b>Suche noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!");
	}

	//Plugin Template
	$plugtpl = new template_class;        # Neues Objekt
	$plugtpl->path = USER_PATH."/templates/plugins/search/"; # Template Pfad
	$plugtpl->load("index");              # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- abschluss --- start --- */

	//Sonstige informationen
	$plugtpl->insertVar("id",$_GET['id']);              # Menüpunkt ID
	if ( $settings["base"]["template_override"] == "1" ) # Template
	{
		$plugtpl->insertVar("style",$_GET['style']);
	}

	//Plugin Template für Ausgabe bereit machen
	$content = $plugtpl->getResult();

	//Inhalt ins Grund Template einfügen
	if ( $settings["display"]["pluginground"] == "1" )
	{
		$retVal = array("implode" => 1, "content" => &$content);
	} else {
		$retVal = array("implode" => 0, "content" => &$content);
	}
	
	$plugtpl->clear();
	$sql->close();

	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>