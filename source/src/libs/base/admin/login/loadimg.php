<?

/* Beschreibung
   Zeigt das Bild für die Sicherheitsabfrage an
*/

function initialize()
{
	//Dateiname generieren und Datei öffnen
	require(USER_PATH."/settings/base/other.settings.php");

	$seckey   = ( preg_match("=^([0-9a-z]{1,})$=si",$_GET['seckey']) ) ? $_GET['seckey'] : "0";
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
}

?>