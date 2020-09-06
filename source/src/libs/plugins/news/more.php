<?

/*
   BESCHREIBUNG:
   
   Zeigt die Men�punkte an und den inhalt der vom plugin geladen wird
   
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

	//Installationspr�fung
	if ( !$sql->install_check("news") )
	{
		$sql->close();
		die("<b>News noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	//G�stebuch funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$nsql = new news_mysql_class;              # Objekt erstellen
	$nsql->prefix = $settings["mysql"]["table_prefix"];

	//Plugin Template
	$plugtpl = new template_class;        # Neues Objekt
	$plugtpl->path = USER_PATH."/templates/plugins/news/"; # Template Pfad
	$plugtpl->load("more");               # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- eintr�ge --- start --- */

	//Seitenzahlen anpassen
	$max_pages       = $nsql->get_max_pages($settings["news"]["max_entries"]);                                 # Anzahl der Seiten
	$page["current"] = ( ($_GET['page']) && (preg_match("/^([0-9]*)$/i",$_GET['page'])) ) ? $_GET['page'] : "1"; # Aktuelle Seite
	$page["back"]    = ( $page["current"] > "1" ) ? $page["current"]-1 : "1";                                    # Seite zur�ck
	$page["forward"] = ( $page["current"] < $max_pages ) ? $page["current"]+1 : $max_pages;                      # Seite vorw�rts

	//Eintr�ge f�r die aktuelle Seite laden
	$entry = $nsql->load_entry($_GET['newsid']);

	//Alle Eintr�ge hinzuf�gen
	if ( $entry )
	{
		if ( $settings["display"]["textencode"] == "1" )
		{
		    $entry["text"] = htmlentities(stripslashes($entry["text"]));
		}
	
	    //Einf�gen
		$plugtpl->insertVar("title",$entry["title"]);
		$plugtpl->insertVar("date",$entry["date"]);
		$plugtpl->insertVar("time",$entry["time"]);
		$plugtpl->insertVar("text",auto_nl2br(stripslashes($entry["text"]),$settings["base"]["nl2br"],base64_decode($settings["base"]["tagfilter"])));
	}

	/*--- eintr�ge --- ende --- */



	/*--- abschluss --- start --- */

	//Seitenzahlen einf�gen
	$plugtpl->insertVar("page_current",$page["current"]);
	$plugtpl->insertVar("page_back",$page["back"]);
	$plugtpl->insertVar("page_forward",$page["forward"]);
	$plugtpl->insertVar("max_pages",$max_pages);

	//Sonstige informationen
	$plugtpl->insertVar("id",$_GET['id']);              # Men�punkt ID
	if ( $settings["base"]["template_override"] == "1" ) # Template
	{
		$plugtpl->insertVar("style",$_GET['style']);
	}

	//Plugin Template f�r Ausgabe bereit machen
	$content = $plugtpl->getResult();

	//Inhalt ins Grund Template einf�gen
	if ( $settings["display"]["pluginground"] == "1" )
	{
		$retVal = array("implode" => 1,"content" => directadd($content,LIB_PATH,$settings["base"]["nl2br"],$settings["base"]["tagfilter"],$settings)); # Inhalt
	} else {
		$retVal = array("implode" => 0,"content" => &$content);
	}
	
	$sql->close();
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>