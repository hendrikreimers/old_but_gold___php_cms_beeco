<?

/*
   BESCHREIBUNG:
   
   Zeigt ein Bild an
   
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

	require(USER_PATH."/settings/plugins/pics/other.settings.php");

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
	$plugtpl = new template_class;        												           # Neues Objekt
	$plugtpl->path = ( ($settings["pics"]["view"] != "2") || ($_GET['ajax'] != "true") ) ? USER_PATH."/templates/plugins/pics/" : PLUGIN_PATH."/templates/"; # Template Pfad
	$plugtpl->load(($settings["pics"]["view"] == "1") ? "details_popup" : (( ($settings["pics"]["view"] == "2") && ($_GET['ajax'] === "true") ) ? "xmlImg_AJAX"  : "details") ); # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- einträge --- start --- */

	//Alles für den aktuellen Eintrag einfügen
	$item  = $psql->load_item($_GET['pic']);
	if ( $item["mode"] < "2" )
	{
		//Einfügen
		$plugtpl->insertVar("pic",$item["id"]);
		$plugtpl->insertVar("title",$item["title"]);
		$plugtpl->insertVar("desc",$item["desc"]);

		//Einträg vor und nach dem aktuellen finden
		$items = $psql->load_items($item["group"],$settings["pics"]["order_items"]);

		if ( $items[0] )
		{
			for ( $i = 0; $i < sizeof($items); $i++ )
			{
				//Speichern bei richtiger Position
				if ( $items[$i]["id"] == $item["id"] )
				{
					//Nur gültige sonst das selbe wie das aktuelle damit man nicht im leeren landet
					$item_back    = ( $items[$i-1] ) ? $items[$i-1] : $item;
					$item_forward = ( $items[$i+1] ) ? $items[$i+1] : $item;
				}
			}
		}

		//Urls vorbereiten
		if ( $settings["display"]["rewrite"] == "1" ) 
		{
			$iData = $sql->load_item_data($_GET['id']);
			$iTitle = strtolower(umlRewEncode(trim(base64_decode($iData["title"]))));
			unset($iData);

			$url_back     = REL_PATH."/".$iTitle."/p/details".$item_back["id"].".html";
			$url_forward  = REL_PATH."/".$iTitle."/p/details".$item_forward["id"].".html";
			$url_img      = REL_PATH."/".$iTitle."/p/big".$item["id"].".jpg";
			$url_overview = REL_PATH."/".$iTitle."/p/group".$item['group'].".html";

			unset($iTitle);
		}
		else
		{
			$url_back    = "?dad=pics&amp;init=details&amp;id=".$_GET['id']."&amp;pic=".$item_back["id"].(($settings["base"]["template_override"] == 1) ? "&amp;style=".$_GET['style'] : "");
			$url_forward = "?dad=pics&amp;init=details&amp;id=".$_GET['id']."&amp;pic=".$item_forward["id"].(($settings["base"]["template_override"] == 1) ? "&amp;style=".$_GET['style'] : "");
			$url_img     = "?init=loadimg&amp;dad=pics&amp;id=".$_GET['id']."&amp;pid=".$item["id"]."&amp;size=b";
			$url_overview = "?dad=pics&amp;init=listgroup&amp;id=".$_GET['id']."&amp;group=".$item["group"].(($settings["base"]["template_override"] == 1) ? "&amp;style=".$_GET['style'] : "");
		}

		//Zusätzliche Optionen für Fenster Einblendung
		if ( ($settings["pics"]["view"] == "1") || ($settings["pics"]["view"] == "2") )
		{
			//Bildmaßen
			$size = getimagesize($settings["pics"]["img_path"]."/".$item["id"]."b.jpg");

			//Einfügen um die Fenstergröße anzupassen
			$plugtpl->insertVar("pic_width",$size[0]);
			$plugtpl->insertVar("pic_height",$size[1]);
		}

		//Passende URLs einfügen
		$plugtpl->insertVar("url_back",$url_back);
		$plugtpl->insertVar("url_forward",$url_forward);
		$plugtpl->insertVar("url_overview",$url_overview);
		$plugtpl->insertVar("url_img",$url_img);
	}
	/*--- einträge --- ende --- */



	/*--- abschluss --- start --- */

	//Plugin Template für Ausgabe bereit machen
	$plugtpl->insertVar("id",$_GET['id']);
	$plugtpl->insertVar("path",REL_PATH);
	$content = $plugtpl->getResult();

	//Inhalt ins Grund Template einfügen (nur wenn es kein popup fenster ist)
	if ( ($settings["pics"]["view"] == "0") || ($settings["pics"]["view"] == "2") && ($_GET['ajax'] != "true") )
	{
		//Nur anzeigen wenn auch das Haupttemplate (Grund) geladen werden soll
		if ( $settings["display"]["pluginground"] == "1" )
		{
		    $retVal = array("implode" => 1, "content" => &$content);
		} else {
			$retVal = array("implode" => 0, "content" => &$content);
		}
	}
	else
	{
		//If AJAX Template, send the Header
		if ( ($settings["pics"]["view"] == "2") && ($_GET['ajax'] === "true") ) {
		    header("Content-Type: text/xml");
			$retVal = array("implode" => 0, "content" => utf8_encode($content));
		} else $retVal = array("implode" => 0, "content" => &$content);
	}

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$plugtpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>