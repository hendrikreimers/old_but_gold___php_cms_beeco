<?

function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];
	
	//Dateiname generieren und Datei öffnen
	require(USER_PATH."/settings/plugins/kontakt/other.settings.php");
	
	$seckey = ( preg_match("=^([0-9a-z]{1,})$=si",$_GET['seckey']) ) ? $_GET['seckey'] : "0";
	$filename = $settings["tmp_dir"]."/".$seckey.".jpg";

	if ( file_exists($filename) ) {
		$fp = fopen($filename,"rb");
	
		//Image Header ausgeben und Bild ausgeben
		header("Content-Type: image/jpeg");
		fpassthru($fp);
	
		//Schließen und löschen
		fclose($fp);
		unlink($filename);
	}

	die();
}
?>