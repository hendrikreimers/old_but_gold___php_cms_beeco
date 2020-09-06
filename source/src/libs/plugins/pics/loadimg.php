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

	require("includes/classes/mysql.class.php"); # Neue MySQL Klasse
	require(USER_PATH."/settings/plugins/pics/other.settings.php"); # Zusätzliche Konfiguration

	//Installationsprüfung
	if ( !$sql->install_check("pics") )
	{
		$sql->close();
		die("<b>Bildergalerie noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	//Gästebuch funktionen
	$psql = new pics_mysql_class;              # Objekt erstellen
	$psql->prefix = $settings["mysql"]["table_prefix"];

	/*--- Grundaktionen --- ende --- */



	/*--- anzeige --- start --- */

	$item     = $psql->load_item($_GET['pid']);
	$imgPath = $settings["pics"]["img_path"]."/".$_GET['pid'].$_GET['size'].".jpg";

	//Nur existierende Dateien und nicht deaktiverte Bilder (durch die Gruppe) anzeigen
	if ( file_exists($imgPath) && ($item["mode"] < "2") )
	{
	    //Bild Header senden
	    header("Content-Type: image/jpeg");

		if ( $settings['pics']['view'] == 2 ) {
			header('Cache-Control: no-store, no-cache, must-revalidate');
		}

		// Kontrolle ob das Bild verändert werden soll
		if ( (((int)$_GET['width']) > 0) && (((int)$_GET['height']) > 0) && ($settings["display"]["imgResize"] == '1') ) {

			// Neue Größe merken
			$new_size['width'] = (int)$_GET['width'];
			$new_size['height'] = (int)$_GET['height'];
						
			//Original Bildgröße
	        $orig_size = getimagesize($imgPath);
			
			// Nur verändern wenn absolut nötig
			if ( ($new_size['width'] != $orig_size[0]) || ($new_size['height'] != $orig_size[1]) ) {
				// Original Bild öffnen
				$orig_img = imagecreatefromjpeg($imgPath);
			
				// Neues Bild erstellen
				$new_img  = imagecreatetruecolor($new_size['width'],$new_size['height']);                                          # Bild erstellen
				imagecopyresampled($new_img,$orig_img,0,0,0,0,$new_size['width'],$new_size['height'],$orig_size[0],$orig_size[1]); # Inhalt kopieren
				
				// Ausgabe
				imagejpeg($new_img,null,$settings["pics"]["quality"]);
				
				// Speicher freigeben
				imagedestroy($orig_img);
				imagedestroy($new_img);
			} else {
				//Bild senden
				$fp = fopen($imgPath,"rb");
				fpassthru($fp);
				fclose($fp);
			}

		} else {
	    	//Bild senden
	    	$fp = fopen($imgPath,"rb");
	    	fpassthru($fp);
	    	fclose($fp);
		}
	}

	//Anhalten damit keine sonstigen daten zwischen rutschen
	die();

	/*--- anzeige --- ende --- */
}

?>