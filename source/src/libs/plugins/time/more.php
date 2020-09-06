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

	//Installationsprüfung
	if ( !$sql->install_check("time") )
	{
		$sql->close();
		die("<b>time noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	//Gästebuch funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$nsql = new time_mysql_class;              # Objekt erstellen
	$nsql->prefix = $settings["mysql"]["table_prefix"];

	//Plugin Template
	$plugtpl = new template_class;        # Neues Objekt
	$plugtpl->path = USER_PATH."/templates/plugins/time/"; # Template Pfad
	$plugtpl->load("more");               # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- einträge --- start --- */

	//Einträge für die aktuelle Seite laden
	$entry = $nsql->load_entry($_GET['timeid']);

	//Alle Einträge hinzufügen
	if ( $entry )
	{
		if ( $settings["display"]["textencode"] == "1" )
		{
	    	$entry["text"] = base64_encode(htmlentities(stripslashes(base64_decode($entry["text"]))));
	    }

	    //Einfügen
		$plugtpl->insertVar("title",$entry["title"]);
		$plugtpl->insertVar("date",$entry["date"]);
		$plugtpl->insertVar("time",$entry["time"]);
		$plugtpl->insertVar("text",auto_nl2br(stripslashes(base64_decode($entry["text"])),$settings["base"]["nl2br"],base64_decode($settings["base"]["tagfilter"])));
	}

	/*--- einträge --- ende --- */



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
		$retVal = array("implode" => 1,"content" => directadd($content,LIB_PATH,$settings["base"]["nl2br"],$settings["base"]["tagfilter"],$settings)); # Inhalt
	} else {
		$retVal = array("implode" => 0,"content" => $content);
	}

	$sql->close();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>