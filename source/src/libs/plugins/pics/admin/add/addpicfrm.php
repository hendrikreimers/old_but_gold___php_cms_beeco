<?

/*
   BESCHREIBUNG:
   
   Zeigt das Formular an um ein Bild hinzuzufügen
   
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
	$path    = REL_PATH;                                 # Pfad zu den einzubindenden Scripten
	require(BASE_PATH."/includes/functions/text.functions.php");  # Text Funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");        # Zusätzliche Plugin SQL Funktionen

	//Templates laden
	$tpl->path = PLUGIN_PATH."/templates/add/";
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



	/*--- Template anzeigen --- start --- */

	//Auto Resize Formular anzeigen
	if ( $settings["pics"]["auto_resize"] == "1" )
	{
	    //AutoResize Formular anzeigen
	    $tpl->load("addpicfrm_resize");
    
	    //Maximale Uploadgröße?
	    $max_size = ini_get("upload_max_filesize")."B";       # Maximale Dateigröße für den user anzeigen (text)

	    //Benötigte Werte einfügen
	    $tpl->insertVar("max_size",$max_size);
	    $tpl->insertVar("max_form_size",$max_form_size);
	    $tpl->insertVar("group",$_GET['group']);
	    $tpl->insertVar("SID",$SID);
	}
	else
	{
	    //Standardformular anzeigen
	    $tpl->load("addpicfrm_default");
    
	    //Benötigte Werte einfügen
	    $tpl->insertVar("group",$_GET['group']);
	    $tpl->insertVar("SID",$SID);
	}

	/*--- Template anzeigen --- ende --- */



	/*--- abschluss --- start --- */

	//Plugin Template für Ausgabe bereit machen
	$tpl->insertVar("SID",$SID);
	$content = $tpl->getResult();

	//Ausgabe
	$ground_tpl->insertVar("text",$content);
	$ground_tpl->insertVar("path",$path);
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