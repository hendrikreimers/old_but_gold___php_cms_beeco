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
	$plugtpl->load("index",1);              # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- einträge --- start --- */

	//Einträge für die aktuelle Seite laden
	$entries = $nsql->load_entries();

	//Alle Einträge hinzufügen
	if ( $entries )
	{
		foreach ( $entries as $entry )
		{	
		    //Zeitstempel zum Datumsvergleich (ob alt oder aktueller termin)
		    $entry["color"] = ( mktime(0,0,0,substr($entry["date"],3,2),substr($entry["date"],0,2),substr($entry["date"],6,4)) < mktime(0,0,0,date("m"),date("d"),date("Y")) ) ? base64_decode($settings["time"]["color_past"]) : base64_decode($settings["time"]["color_present"]);
	    
			//Einfügen
			$entry["timeid"] = $entry["id"];
			$entry["id"]     = $_GET['id'];
		
			if ( $entry["text"] )
			{
				if ( $settings["display"]["rewrite"] == "1" ) {
					$iData = $sql->load_item_data($_GET['id']);
					$iTitle = strtolower(umlRewEncode(trim(base64_decode($iData["title"]))));

					$entry["url"] = "<a href=\"".REL_PATH."/".$iTitle."/t/t".$entry["timeid"].".html\">Info</a>";

					unset($iData);
					unset($iTitle);
				} else $entry["url"] = "<a href=\"?init=more&amp;timeid=".$entry["timeid"]."&amp;id=".$entry["id"].(( $settings["base"]["template_override"] ) ? "&amp;style=".$_GET['style'] : "")."\">Info</a>";
			}
		
			$plugtpl->insertArray($entry,array("termin"));
		}
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
		$retVal = array("implode" => 1,"content" => $content);
	} else {
		$retVal = array("implode" => 0,"content" => $content);
	}

	//Verbindung beenden
	$sql->close();
	$plugtpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>