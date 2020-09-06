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
	if ( !$sql->install_check("news") )
	{
		$sql->close();
		die("<b>News noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	//News funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$nsql = new news_mysql_class;              # Objekt erstellen
	$nsql->prefix = $settings["mysql"]["table_prefix"];

	//Plugin Template
	$plugtpl = new template_class;        # Neues Objekt
	$plugtpl->path = USER_PATH."/templates/plugins/news/"; # Template Pfad
	$plugtpl->load("index",1);              # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- einträge --- start --- */

	//Seitenzahlen anpassen
	$max_pages       = $nsql->get_max_pages($settings["news"]["max_entries"]);                                 # Anzahl der Seiten
	$page["current"] = ( ($_GET['page']) && (preg_match("/^([0-9]*)$/i",$_GET['page'])) ) ? $_GET['page'] : "1"; # Aktuelle Seite
	$page["back"]    = ( $page["current"] > "1" ) ? $page["current"]-1 : "1";                                    # Seite zurück
	$page["forward"] = ( $page["current"] < $max_pages ) ? $page["current"]+1 : $max_pages;                      # Seite vorwärts

	//Einträge für die aktuelle Seite laden
	$entries = $nsql->news_load_page($page["current"],$settings["news"]["max_entries"],$settings["base"]["nl2br"],$settings["base"]["tagfilter"],"1",$settings["news"]["max_words"]);

	//Alle Einträge hinzufügen
	if ( $entries )
	{
		foreach ( $entries as $entry )
		{	
			//Einfügen
			$entry["path"] = REL_PATH;

			//URL einfügen (ggf. mit Rewrite Engine)
			if ( $settings["display"]["rewrite"] == "1" ) {
				$entry["url"] = REL_PATH."/";
				$iData = $sql->load_item_data($_GET['id']);
				$iTitle = strtolower(umlRewEncode(trim(base64_decode($iData["title"]))));
				unset($iData);

				$entry["url"] .= $iTitle."/n/n".$entry["id"].".html";
			} else {
				$entry["url"]  = "?init=more&amp;id=".$_GET['id']."&amp;newsid=".$entry["id"].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" );
			}

			if ( $settings["display"]["textencode"] == "1" )
			{
			    $entry["text"] = htmlentities($entry["text"]);
			}
		
			$plugtpl->insertArray($entry,array("entries"));
		}
	}

	/*--- einträge --- ende --- */



	/*--- abschluss --- start --- */

	//Seitenzahlen einfügen
	$plugtpl->insertVar("page_current",$page["current"]);
	$plugtpl->insertVar("max_pages",$max_pages);
	$plugtpl->insertVar("path",REL_PATH);

	//Sonstige URLs einfügen
	if ( $settings["display"]["rewrite"] == "0" ) {
		$plugtpl->insertVar("url_begin","?id=".$_GET['id']."&amp;page=1".( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" ));
		$plugtpl->insertVar("url_end","?id=".$_GET['id']."&amp;page=".$max_pages.( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" ));
		$plugtpl->insertVar("url_back","?id=".$_GET['id']."&amp;page=".$page["back"].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" ));
		$plugtpl->insertVar("url_next","?id=".$_GET['id']."&amp;page=".$page["forward"].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" ));
	} else {
		$plugtpl->insertVar("url_begin",REL_PATH."/".$iTitle."/n/page1.html");
		$plugtpl->insertVar("url_end",REL_PATH."/".$iTitle."/n/page".$max_pages.".html");
		$plugtpl->insertVar("url_back",REL_PATH."/".$iTitle."/n/page".$page["back"].".html");
		$plugtpl->insertVar("url_next",REL_PATH."/".$iTitle."/n/page".$page["forward"].".html");
	}

	//Plugin Template für Ausgabe bereit machen
	$content = $plugtpl->getResult();

	//Inhalt ins Grund Template einfügen (directadd nur in den details anzeigen)
	if ( $settings["display"]["pluginground"] == "1" )
	{
		$retVal = array("implode" => 1, "content" => preg_replace("/{(.*)}/i","",$content)); # Inhalt
	} else {
		$retVal = array("implode" => 0, "content" => &$content);
	}
	
	$sql->close();
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>