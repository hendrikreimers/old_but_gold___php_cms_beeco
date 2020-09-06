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

	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse

	//Installationspruefung
	if ( !$sql->install_check("search") )
	{
    	$sql->close();
	    die("<b>Suche noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	//Plugin Template
	$plugtpl = new template_class;        # Neues Objekt
	$plugtpl->path = USER_PATH."/templates/plugins/search/"; # Template Pfad
	$plugtpl->load("search",1); # Template laden und auftrennen
	
	//Funktionen laden
	$sSql = new search_mysql_class;              # Objekt erstellen
	$sSql->prefix = $settings["mysql"]["table_prefix"];

	//Suche nur beginnen wenn inhalt vorhanden
	if ( strlen($_POST['search_text']) > 0 ) {

	/*--- Grundaktionen --- ende --- */



	/*--- Suche --- start ---*/

	//Es gibt 3 Arten von Suche: Exakte Suche, Alle the Words und One of the Words
	//Exakte wird mit " " (und) gesucht... die anderen beiden mit | (oder)
	//wobei bei All the words geguckt werden muss ob wirklich jedes wort in der
	//suche vorgekommen ist. damit das die funktion weiß muss ein BOOLEAN übergeben werden

	//Regulären Ausdruck zusammen bauen
	$regexp = "=".str_replace(" ",(($_POST['search_type'] == "1") ? " " : "|"),htmlentities(preg_quote(strip_tags(trim($_POST['search_text'])))))."=si";

	//Suchen
	$sResult["base"]      = $sSql->search_base($regexp,$_POST['search_type'],$settings);      # Normales CMS
	$sResult["news"]      = $sSql->search_news($regexp,$_POST['search_type'],$settings);      # News
	$sResult["time"]      = $sSql->search_time($regexp,$_POST['search_type'],$settings);      # Termine
	$sResult["picgroups"] = $sSql->search_picgroups($regexp,$_POST['search_type'],$settings); # Bilder Gruppen

	//Anzahl der Treffer
	$match_count = sizeof($sResult["base"]) +
	               sizeof($sResult["news"]) +
	               sizeof($sResult["time"]) +
	               sizeof($sResult["picgroups"]);

	/*--- Suche --- ende ---*/



	/*--- ausgabe --- start ---*/

	foreach ($sResult as $plugin => $entries)
	{
		if ( strlen($sResult[$plugin][0]["id"]) > 0 )
		{
			foreach ( $entries as $entry )
			{
				//Dekodieren
				$entry["title"] = base64_decode($entry["title"]);
				$entry["text"]  = preg_replace("/{([a-z]*):([a-z0-9\,\-\+]*)}/siU","",strip_tags(base64_decode($entry["text"])));
			
				//text kürzen
				$text = @Explode(" ",$entry["text"]);
				$entry["text"] = "";
	
				for ( $i = 0; $i < 10; $i++ )
				{
					$entry["text"] .= $text[$i]." ";
				}
			
				$entry["text"] .= "...";
			
				//URL evtl. anpassen
				if ( $settings["base"]["template_override"] == "1" ) {
					$entry["url"] .= "&amp;style=".$_POST['style'];
				}
	
				$plugtpl->insertArray($entry,array($plugin));
			}
		}
	}

	/*--- ausgabe --- start ---*/



	/*--- abschluss --- start --- */

	//Suche nur beginnen wenn inhalt vorhanden (abschluss)
	}

	//Sonstige informationen
	$plugtpl->insertVar((($_POST['search_type'] == "1") ? "type1_checked" : (($_POST['search_type'] == "2") ? "type2_checked" : "type3_checked") ),'checked="checked"'); # Menüpunkt ID
	$plugtpl->insertVar("search_text",$_POST['search_text']);
	$plugtpl->insertVar("match_count",($match_count > 0) ? $match_count : "0");
	$plugtpl->insertVar("id",$_GET['id']);

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