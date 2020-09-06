<?

/*
   BESCHREIBUNG:

   Zeigt das Formular für das Hinzufügen an
   und fügt den neuen termin gegebenfalls hinzu

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

	$tpl->path = PLUGIN_PATH."/templates/add/"; # Template pfad (für den inhalt)

	//Installationsprüfung
	if ( !$sql->install_check("time") )
	{
		$sql->close();
		die("<b>Time noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	$status = "";

	//Templates laden
	$ground_tpl->load("ground");
	$tpl->load("index");

	//Standard Informationen einsetzen
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("SID",$SID);

	/*--- defaults --- ende --- */



	/*--- hinzufügen --- start --- */

	if ( $_POST['SID'] )
	{
	    if ( strlen($_POST['title']) > 0 )
	    {
	        $status = message(PLUGIN_PATH,"time","true",$SID);
        
	        //Daten vorbereiten
	        $title = base64_encode($_POST['title']);
	        $text  = ( strlen($_POST['text']) > 0 ) ? base64_encode($_POST['text']) : "";
        
	        $date_y = ( preg_match("/([0-9]{4})/i",$_POST['date_y']) ) ? $_POST['date_y'] : date("Y");
	        $date_m = ( preg_match("/([0-9]{2})/i",$_POST['date_m']) ) ? $_POST['date_m'] : date("m");
	        $date_d = ( preg_match("/([0-9]{2})/i",$_POST['date_d']) ) ? $_POST['date_d'] : date("d");
	        $date   = $date_y."-".$date_m."-".$date_d;
        
	        //Hinzufügen
	        require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
			$tsql = new time_mysql_class;                      # Objekt erstellen
			$tsql->prefix = $settings["mysql"]["table_prefix"];
			$tsql->add_entry($date,$title,$text);
			unset($tsql);
			unset($_POST['text']);
		
			//Datum wieder für neue einträge einfügen
			$tpl->insertVar("date_y",date("Y"));
	        $tpl->insertVar("date_m",date("m"));
	        $tpl->insertVar("date_d",date("d"));
	    }
	    else
	    {
	        $status = message(PLUGIN_PATH,"time","false",$SID);#
        
	        $tpl->insertVar("date_y",( preg_match("/([0-9]{4})/i",$_POST['date_y']) ) ? $_POST['date_y'] : date("Y"));
	        $tpl->insertVar("date_m",( preg_match("/([0-9]{2})/i",$_POST['date_m']) ) ? $_POST['date_m'] : date("Y"));
	        $tpl->insertVar("date_d",( preg_match("/([0-9]{2})/i",$_POST['date_d']) ) ? $_POST['date_d'] : date("Y"));
	    }
	}
	else
	{
	    $tpl->insertVar("date_y",date("Y"));
	    $tpl->insertVar("date_m",date("m"));
	    $tpl->insertVar("date_d",date("d"));
	}

	/*--- hinzufügen --- ende --- */



	/*--- Ausgabe --- start --- */

	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["time"]["title"]));
	$ground_tpl->insertVar("name",$settings["time"]["name"]);

	//FCKeditor einbauen
	$tpl->insertVar("text",$_POST['text']);
	
	$content = $status.$tpl->getResult();
	$ground_tpl->insertVar("text",$content);
	$ground_tpl->insertVar("SID",$SID);

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