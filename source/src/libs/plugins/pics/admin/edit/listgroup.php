<?

/*
   BESCHREIBUNG:
   
   Zeigt die Gruppen an
   
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
	
	//Standard Aktionen laden
	#require($path."/includes/functions/text.functions.php");  # Text Funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");        # Zusätzliche Plugin SQL Funktionen
	
	//Templates laden
	$tpl->path = PLUGIN_PATH."/templates/edit/";
	$tpl->load("listgroup",1);
	$ground_tpl->load("ground");
	
	//Klasse einrichten für die Galerie
	$psql = new pics_mysql_class;
	$psql->prefix = $settings["mysql"]["table_prefix"];
	
	//Installationsprüfung
	if ( !$sql->install_check("pics") )
	{
		$sql->close();
		die("<b>Bildergalerie noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}
	
	/*--- Grundaktionen --- ende --- */
	
	
	
	/*--- Bilder einfügen --- start --- */
	
	//Einträge laden
	$items = $psql->load_items($_GET['group'],$settings["pics"]["order_items"]);
	
	//Anzahl der Seiten errechnen
	$max_pages      = ceil(sizeof($items)/(4*3)); # Feste Darstellung für den Admin Bereich
	
	//Seitenpositionen errechnen
	$page["current"] = ( ($_GET['page']) && (preg_match("/^([0-9]*)$/i",$_GET['page'])) ) ? $_GET['page'] : "1"; # Aktuelle Seite
	$page["back"]    = ( $page["current"] > "1" ) ? $page["current"]-1 : "1";                                    # Seite zurück
	$page["forward"] = ( $page["current"] < $max_pages ) ? $page["current"]+1 : $max_pages;                      # Seite vorwärts
	
	//Einträge pro Seite errechnen
	$entries_per_page = 4*3; # Feste Darstellung für den Admin Bereich
	
	//In die Kopfzeile alles einfügen
	$tpl->insertVar("SID",$SID);
	$tpl->insertVar("group",$_GET['group']);
	$tpl->insertVar("page_current",$page["current"]);
	$tpl->insertVar("page_back",$page["back"]);
	$tpl->insertVar("page_forward",$page["forward"]);
	$tpl->insertVar("max_pages",$max_pages);
	
	//Einfügen
	if ( $items[0] )
	{
		//Maximale Bilder pro Zeile
		$max_cols = 4; # Feste Darstellung für den Admin Bereich
	
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
					$tpl->insertVar(null,null,array("row"));
				}
				
				//Übersetzen
				$items[$i]["title"] = $items[$i]["title"];
				$items[$i]["desc"]  = $items[$i]["desc"];
				$items[$i]["group"] = $_GET['group'];
				$items[$i]["SID"]   = $SID;
				$items[$i]["path"]  = REL_PATH;
			
				//Buffer frei machen für nächsten Eintrag
				$tpl->buffer["col"] = "";        # Buffer zurücksetzen
				$tpl->insertArray($items[$i],array("row","col"));  # Werte einfügen und Buffer somit füllen
				$count++;
	
				//Bei bedarf Zeile beenden und Counter zurück
				if ( $count == $max_cols )
				{
					$count = 0;
				}
			}
		}
	}
	
	/*--- Bilder einfügen --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Ausgabe
	$ground_tpl->insertVar("text",$tpl->getResult());
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title","Bildergalerie");
	$ground_tpl->insertVar("name","pics");
	$retVal = $ground_tpl->getResult();
	
	//Verbindung beenden
	$sql->close();
	
	//Speicher freigeben
	$tpl->clear();
	$ground_tpl->clear();
	
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>