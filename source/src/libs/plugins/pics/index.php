<?

/*
   BESCHREIBUNG:
   
   Zeigt die Bildgruppen an
   
*/

function initialize($dObj)
{

	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	/*--- Grundaktionen --- start --- */

	//Installationsprüfung
	if ( !$sql->install_check("pics") )
	{
		$sql->close();
		die("<b>Bildergalerie noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	//Gästebuch funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$psql = new pics_mysql_class;              # Objekt erstellen
	$psql->prefix = $settings["mysql"]["table_prefix"];

	//Plugin Template
	$plugtpl = new template_class;        # Neues Objekt
	$plugtpl->path = USER_PATH."/templates/plugins/pics/"; # Template Pfad
	$plugtpl->load("index",1);              # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- einträge --- start --- */

	$groups = $psql->load_groups($settings["pics"]["order_groups"]);

	if ( $groups[0] )
	{
		foreach ( $groups as $group )
		{
			if ( $group["mode"] < "1" )
			{
				if ( $settings["display"]["rewrite"] == "1" ) {
					$iData = $sql->load_item_data($_GET['id']);
					$iTitle = strtolower(umlRewEncode(trim(base64_decode($iData["title"]))));
					unset($iData);

					$group["url"] = REL_PATH."/".$iTitle."/p/group".$group["id"].".html";

					unset($iTitle);
				} else $group["url"] = "?dad=pics&amp;init=listgroup&amp;id=".$_GET['id']."&amp;group=".$group["id"].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "");

			    $plugtpl->insertArray($group,array("group"));
			}
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
		$retVal = array("implode" => 1, "content" => &$content);
	} else {
		$retVal = array("implode" => 0, "content" => &$content);
	}

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$plugtpl->clear();
	
	// Ergebnis liefern
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>