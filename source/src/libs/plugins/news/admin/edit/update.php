<?

/*
   BESCHREIBUNG:

   Zeigt die verfügbaren Funktionen/Menüs
   der Administrationsoberfläche an

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
	
	//News funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$gbsql = new news_mysql_class;              # Objekt erstellen
	$gbsql->prefix = $settings["mysql"]["table_prefix"];
	
	//Installationsprüfung
	if ( !$sql->install_check("news") )
	{
		$sql->close();
		die("<b>News noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}
	
	/*--- defaults --- ende --- */
	
	
	
	/*--- löschen --- start --- */
	
	//ID angegeben?
	if ( $_POST['id'] )
	{
		$date = $_POST["date_y"]."-".$_POST["date_m"]."-".$_POST["date_d"];
		$time = $_POST["time_h"].":".$_POST["time_m"].":00";
		
		if ( $gbsql->update_entry($_POST['id'],$date,$time,$_POST['title'],$_POST['text']) )
		{
			$content = message(PLUGIN_PATH,"news","edit_true",$SID);
		}
		else $content = message(PLUGIN_PATH,"news","edit_false",$SID);
	}
	
	/*--- löschen --- ende --- */
	
	
	
	/*--- Ausgabe --- start --- */
	
	//Templates laden
	$ground_tpl->load("ground");
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title",base64_decode($settings["news"]["title"]));
	$ground_tpl->insertVar("name",$settings["news"]["name"]);
	$ground_tpl->insertVar("text",$content);
	
	//Alles ausgeben
	$retVal = $ground_tpl->getResult();
	
	/*--- Ausgabe --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Verbindung beenden
	$sql->close();
	
	//Speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>