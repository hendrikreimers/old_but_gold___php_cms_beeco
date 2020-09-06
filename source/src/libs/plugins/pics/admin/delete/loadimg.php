<?

/*
   BESCHREIBUNG:

   Zeigt ein Bild an
   Dadurch wird verheimlig wo das original bild sitzt.
   Und wenn die Bildgruppe inaktiv ist wird es auch nicht angezeigt.

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
	
	require(USER_PATH."/settings/plugins/pics/other.settings.php"); # Zusätzliche Konfiguration
	
	//Installationsprüfung
	if ( !$sql->install_check("pics") )
	{
		$sql->close();
		die("<b>Bildergalerie noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}
	
	//Galerie funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
	$psql = new pics_mysql_class;              # Objekt erstellen
	$psql->prefix = $settings["mysql"]["table_prefix"];
	
	/*--- Grundaktionen --- ende --- */
	
	
	
	/*--- anzeige --- start --- */
	
	$item = $psql->load_item($_GET['id']);
	
	//Nur existierende Dateien und nicht deaktiverte Bilder (durch die Gruppe) anzeigen
	if ( file_exists($settings["pics"]["img_path"]."/".$_GET['id'].$_GET['size'].".jpg") )
	{
		//Bild Header senden
		header("Content-Type: image/jpeg");
	
		//Bild senden
		$fp = fopen($settings["pics"]["img_path"]."/".$_GET['id'].$_GET['size'].".jpg","rb");
		fpassthru($fp);
		fclose($fp);
	}
	
	/*--- anzeige --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Verbindung beenden
	$sql->close();
	
	/*--- abschluss --- ende --- */
}

?>