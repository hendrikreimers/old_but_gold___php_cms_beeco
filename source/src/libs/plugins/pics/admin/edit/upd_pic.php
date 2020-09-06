<?

/*
   BESCHREIBUNG:
   
   Löscht ein Bild
   
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
	
	
	
	/*--- Löschen --- start --- */
	
	//Alle bilder löschen
	if ( $_POST['id'] )
	{
		//Aktualisieren
		$psql->upd_item($_POST['id'],$_POST['new_group'],$_POST['title'],$_POST['desc']);
		
		//Meldung
		$content = message(PLUGIN_PATH,"pics","picupd_true",$SID."&group=".$_POST['group']);
	}
	else
	{
		//Fehlermeldung
		$content = message(PLUGIN_PATH,"pics","picupd_false",$SID."&group=".$_POST['group']);
	}
	
	/*--- Löschen --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Ausgabe
	$ground_tpl->insertVar("text",$content);
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