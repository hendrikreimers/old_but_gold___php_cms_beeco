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
	$plugtpl->load(($settings["pics"]["view"] == "1") ? "listgroup_popup" : (($settings["pics"]["view"] == "2") ? "listgroup_ajax" : "listgroup"),1); # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- einträge --- start --- */

	//Einträge laden
	$items = $psql->load_items($_GET['group'],$settings["pics"]["order_items"]);

	//Anzahl der Seiten errechnen
	$max_pages       = ceil(sizeof($items)/(base64_decode($settings["pics"]["max_rows"])*base64_decode($settings["pics"]["max_cols"])));

	//Seitenpositionen errechnen
	$page["current"] = ( ($_GET['page']) && (preg_match("/^([0-9]*)$/i",$_GET['page'])) ) ? $_GET['page'] : "1"; # Aktuelle Seite
	$page["back"]    = ( $page["current"] > "1" ) ? $page["current"]-1 : "1";                                    # Seite zurück
	$page["forward"] = ( $page["current"] < $max_pages ) ? $page["current"]+1 : $max_pages;                      # Seite vorwärts

	//Einträge pro Seite errechnen
	$entries_per_page = base64_decode($settings["pics"]["max_cols"])*base64_decode($settings["pics"]["max_rows"]);

	//URLs vorbereiten (ggf. Rewrite Funktion)
	if ( $settings["display"]["rewrite"] == "1" ) 
	{
		$iData = $sql->load_item_data($_GET['id']);
		$iTitle = strtolower(umlRewEncode(trim(base64_decode($iData["title"]))));
		unset($iData);

		$overview_url = REL_PATH."/".$iTitle.".html";
		$begin_url    = REL_PATH."/".$iTitle."/p/page-".$_GET['group']."-1.html";
		$end_url      = REL_PATH."/".$iTitle."/p/page-".$_GET['group']."-".$max_pages.".html";
		$next_url     = REL_PATH."/".$iTitle."/p/page-".$_GET['group']."-".$page["forward"].".html";
		$back_url     = REL_PATH."/".$iTitle."/p/page-".$_GET['group']."-".$page["back"].".html";

	}
	else
	{
		$overview_url = "?dad=pics&amp;id=".$_GET['id'].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" );
		$begin_url    = "?dad=pics&amp;init=listgroup&amp;group=".$_GET['group']."&amp;page=1&amp;id=".$_GET['id'].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" );
		$end_url      = "?dad=pics&amp;init=listgroup&amp;group=".$_GET['group']."&amp;page=".$max_pages."&amp;id=".$_GET['id'].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" );
		$next_url     = "?dad=pics&amp;init=listgroup&amp;group=".$_GET['group']."&amp;page=".$page["forward"]."&amp;id=".$_GET['id'].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" );
		$back_url     = "?dad=pics&amp;init=listgroup&amp;group=".$_GET['group']."&amp;page=".$page["back"]."&amp;id=".$_GET['id'].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" );
	}

	//In die Kopfzeile alles einfügen
	$plugtpl->insertVar("overview_url",$overview_url);
	$plugtpl->insertVar("begin_url",$begin_url);
	$plugtpl->insertVar("end_url",$end_url);
	$plugtpl->insertVar("next_url",$next_url);
	$plugtpl->insertVar("back_url",$back_url);
	$plugtpl->insertVar("page_current",$page["current"]);
	$plugtpl->insertVar("max_pages",$max_pages);
	$plugtpl->insertVar("path",REL_PATH);
	$plugtpl->insertVar("group_id",(int)$_GET['group']);

	//Speicher freigeben
	unset($overview_url);
	unset($begin_url);
	unset($end_url);
	unset($next_url);
	unset($back_url);

	//Einfügen
	if ( ($items[0]) && ($items[0]["mode"] < 2) )
	{
	    //Maximale Bilder pro Zeile
	    $max_cols = base64_decode($settings["pics"]["max_cols"]);

	    //Startzähler auf 0
	    $count = 0;

	    //Nicht mehr einträge als nötig anzeigen
	    for ( $i = (($page["current"]*$entries_per_page)-$entries_per_page); $i < $entries_per_page*$page["current"]; $i++ )
	    {
	        if ( $items[$i] )
	        {
	            //Bei Bedarf neue Zeile beginnen
	            if ( $count == 0 )
	            {
	                $plugtpl->insertVar(null,null,array("row"));
	            }
            
	            //Zusätzliche Werte (ggf. Rewrite Funktion aktivieren)
				if ( $settings["display"]["rewrite"] == "1" ) 
				{
					$items[$i]["url"] = REL_PATH."/".$iTitle."/p/details".$items[$i]["id"].".html";
					$items[$i]["img"] = REL_PATH."/".$iTitle."/p/small".$items[$i]["id"].".jpg";
					
					if ( $settings["pics"]["view"] == 2 ) {
						$items[$i]["ajaxUrl"] = REL_PATH."/".$iTitle."/p/ajaxDetails".$items[$i]["id"].".html";;
					}
				} 
				else 
				{
					$items[$i]["url"] = "?init=details&amp;page=".$page["current"]."&amp;id=".$_GET['id']."&amp;group=".$_GET['group']."&amp;pic=".$items[$i]["id"].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" );
					$items[$i]["img"] = "?init=loadimg&amp;id=".$_GET['id']."&amp;pid=".$items[$i]["id"]."&amp;size=s";
					
					if ( $settings["pics"]["view"] == 2 ) {
						$items[$i]["ajaxUrl"] = "?init=details&amp;ajax=true&amp;page=".$page["current"]."&amp;id=".$_GET['id']."&amp;group=".$_GET['group']."&amp;pic=".$items[$i]["id"].( ($settings["base"]["template_override"] == "1") ? "&amp;style=".$_GET['style'] : "" );
					}
				}

	            //Buffer frei machen für nächsten Eintrag
	            $plugtpl->insertArray($items[$i],array("row","col"));  # Werte einfügen und Buffer somit füllen
	            $count++;

	            //Bei bedarf Zeile beenden und Counter zurück
	            if ( ($count == $max_cols) )
	            {
	                $count = 0;
	            }
	        }
	    }
	}

	/*--- einträge --- ende --- */



	/*--- abschluss --- start --- */

	//Speicher freigeben
	unset($iTitle);
	
	// Inhalt zwischenspeichern
	$content = $plugtpl->getResult();

	//Inhalt ins Grund Template einfügen
	if ( $settings["display"]["pluginground"] == "1" )
	{
		$retVal = array("implode" => 1, "content" => &$content);
	} else {
		$retVal = array("implode" => 0, "content" => &$content);
	}
	
	$sql->close();
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>