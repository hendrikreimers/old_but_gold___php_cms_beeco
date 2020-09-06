<?

/*
   BESCHREIBUNG:

   Zeigt die verf�gbaren Funktionen/Men�s
   der Administrationsoberfl�che an

*/

function initialize($dObj)
{

	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];
	
	/*--- defaults --- start --- */
	
	//Standard Aktionen laden
	require(BASE_PATH."/includes/functions/text.functions.php");  # Text Funktionen
	$tpl->path = PLUGIN_PATH."/templates/delete/";             # Template pfad (f�r den inhalt)
	
	//News funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$gbsql = new news_mysql_class;              # Objekt erstellen
	$gbsql->prefix = $settings["mysql"]["table_prefix"];
	
	//Installationspr�fung
	if ( !$sql->install_check("news") )
	{
		$sql->close();
		die("<b>News noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}
	
	/*--- defaults --- ende --- */
	
	
	
	/*--- eintr�ge --- start --- */
	
	//Template
	$tpl->load("index",1);
	
	//Seitenzahlen anpassen
	$max_pages       = $gbsql->get_max_pages($settings["news"]["max_entries"]);                                 # Anzahl der Seiten
	$page["current"] = ( ($_GET['page']) && (preg_match("/^([0-9]*)$/i",$_GET['page'])) ) ? $_GET['page'] : "1"; # Aktuelle Seite
	$page["back"]    = ( $page["current"] > "1" ) ? $page["current"]-1 : "1";                                    # Seite zur�ck
	$page["forward"] = ( $page["current"] < $max_pages ) ? $page["current"]+1 : $max_pages;                      # Seite vorw�rts
	
	//Eintr�ge f�r die aktuelle Seite laden
	$entries = $gbsql->news_load_page($page["current"],$settings["news"]["max_entries"],$settings["base"]["nl2br"],$settings["base"]["tagfilter"],"1",$settings["news"]["max_words"],"../..");
	
	//Alle Eintr�ge hinzuf�gen
	if ( $entries )
	{
		foreach ( $entries as $entry )
		{	
			//Einf�gen
			$entry["path"] = REL_PATH;
			$entry["SID"]  = $SID;
			$tpl->insertArray($entry,array("entries"));
		}
	}
	
	/*--- eintr�ge --- ende --- */
	
	
	
	/*--- Ausgabe --- start --- */
	
	//Seitenzahlen einf�gen
	$tpl->insertVar("page_current",$page["current"]);
	$tpl->insertVar("page_back",$page["back"]);
	$tpl->insertVar("page_forward",$page["forward"]);
	$tpl->insertVar("max_pages",$max_pages);
	
	//Templates laden
	$ground_tpl->load("ground");
	
	//Standard Informationen einsetzen
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("SID",$SID);
	
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["news"]["title"]));
	$ground_tpl->insertVar("name",$settings["news"]["name"]);
	
	$ground_tpl->insertVar("text",$tpl->getResult());
	
	//Alles ausgeben
	$retVal = $ground_tpl->getResult();
	
	/*--- Ausgabe --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Verbindung beenden
	$sql->close();
	
	//Speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();
	
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>