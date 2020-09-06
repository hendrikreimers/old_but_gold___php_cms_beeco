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
	if ( !$sql->install_check("gbook") )
	{
		$sql->close();
		die("<b>Gästebuch noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	//Gästebuch funktionen
	require(USER_PATH."/settings/plugins/gbook/other.settings.php");
	#require(BASE_PATH."/includes/functions/text.functions.php");
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$gbsql = new gbook_mysql_class;              # Objekt erstellen
	$gbsql->prefix = $settings["mysql"]["table_prefix"];

	//Plugin Template
	$plugtpl = new template_class;        # Neues Objekt
	$plugtpl->path = USER_PATH."/templates/plugins/gbook/"; # Template Pfad
	$plugtpl->load("index",1);              # Template laden
	
	/*--- Grundaktionen --- ende --- */



	/*--- einträge --- start --- */

	//Seitenzahlen anpassen
	$max_pages       = $gbsql->get_max_pages($settings["gbook"]["max_entries"]);                                 # Anzahl der Seiten
	$page["current"] = ( ($_GET['page']) && (preg_match("/^([0-9]*)$/i",$_GET['page'])) ) ? $_GET['page'] : "1"; # Aktuelle Seite
	$page["back"]    = ( $page["current"] > "1" ) ? $page["current"]-1 : "1";                                    # Seite zurück
	$page["forward"] = ( $page["current"] < $max_pages ) ? $page["current"]+1 : $max_pages;                      # Seite vorwärts

	//Einträge für die aktuelle Seite laden
	$settings["gbook"]["enable_html"] = ( $settings["display"]["textencode"] == "0" ) ? $settings["gbook"]["enable_html"] : "0";
	$entries = $gbsql->gbook_load_page($page["current"],$settings["gbook"]["max_entries"],$settings["base"]["nl2br"],$settings["base"]["tagfilter"],$settings["gbook"]["enable_html"],$settings["gbook"]["ignore_icq"],$settings["gbook"]["ignore_homepage"],$settings["gbook"]["ignore_email"]);

	//Alle Einträge hinzufügen
	if ( $entries )
	{
		foreach ( $entries as $entry )
		{	
			//Einfügen
			$plugtpl->insertArray($entry,array("entries"));
		}
	}

	/*--- einträge --- ende --- */



	/*--- abschluss --- start --- */

	//Seitenzahlen einfügen
	$plugtpl->insertVar("page_current",$page["current"]);
	$plugtpl->insertVar("max_pages",$max_pages);

	//URLs einfügen ggf. Rewrite
	if ( $settings["display"]["rewrite"] == "1" ) {
		$iData = $sql->load_item_data($_GET['id']);
		$iTitle = strtolower(umlRewEncode(trim(base64_decode($iData["title"]))));
		unset($iData);

		//URLs einfügen
		$plugtpl->insertVar("url_add",REL_PATH."/".$iTitle."/g/add.html");
		$plugtpl->insertVar("url_back",REL_PATH."/".$iTitle."/g/page".$page["back"].".html");
		$plugtpl->insertVar("url_next",REL_PATH."/".$iTitle."/g/page".$page["forward"].".html");

		unset($iTitle);
	}
	else
	{
		//Template Style ggf einfügen
		$style = ( $settings["base"]["template_override"] == "1" ) ? "&amp;style=".$_GET['style'] : "";

		//URLs einfügen
		$plugtpl->insertVar("url_add","?init=add&amp;id=".$_GET['id'].$style);
		$plugtpl->insertVar("url_back","?id=".$_GET['id']."&amp;page=".$page["back"].$style);
		$plugtpl->insertVar("url_next","?id=".$_GET['id']."&amp;page=".$page["forward"].$style);
	}

	//Plugin Template für Ausgabe bereit machen
	$content = $plugtpl->getResult();

	//DirectADD aus dem Content entfernen
	$content = preg_replace("=\{[a-z]{1,}:[a-z0-9,-]{1,}\}=i","",$content);

	//Inhalt ins Grund Template einfügen
	if ( $settings["display"]["pluginground"] == "1" )
	{
		$retVal = array("implode" => 1,"content" => &$content);
	} else {
		$retVal = array("implode" => 0,"content" => &$content);
	}
	
	$sql->close();
	$plugtpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>