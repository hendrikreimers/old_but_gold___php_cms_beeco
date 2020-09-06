<?

/*
   BESCHREIBUNG:
   
   Fügt ein neues Bild hinzu
   
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
	$path    = REL_PATH;
	require(BASE_PATH."/includes/functions/text.functions.php");  # Text Funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");        # Zusätzliche Plugin SQL Funktionen
	require(USER_PATH."/settings/plugins/pics/other.settings.php");    # Zusätzliche Konfiguration

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

	if ( !is_writable($settings["pics"]["img_path"]) )
	{
	    $sql->close();
	    die("<b>Schreibrechte fuer den Upload Ordner fehlen!</b>");
	}

	/*--- Grundaktionen --- ende --- */



	/*--- Bild hinzufügen --- start --- */

	//Welche Art von Formular wurde ausgefüllt?
	if ( $settings["pics"]["auto_resize"] == "1" )
	{
	    //Wurde ein Bild angegeben?
	    //if ( $_FILES['origfile']['type'] == "image/jpeg" )
	    if ( stristr($_FILES['origfile']['type'],"jpeg") )
	    {
	        //Zur Datenbank hinzufügen
	        $img_id = $psql->add_item($_POST['group'],$_POST['title'],$_POST['desc']);

	        //Original Bild öffnen
	        $orig_img = imagecreatefromjpeg($_FILES['origfile']['tmp_name']);
        
	        //Aktuelle bildgröße
	        $orig_size = getimagesize($_FILES['origfile']['tmp_name']);
        
	        //Neue Bildmaßen ausrechnen...
    	    //Ausrechnen, wenn das Bild waagerecht ist (Breite ist GRÖSSER als die höhe)
	        if ( $orig_size[0] >= $orig_size[1] )
	        {
    	        //Vorschaubild
	            $preview_size["x"] = base64_decode($settings["pics"]["max_preview_width"]);
	            $preview_size["y"] = ceil(($orig_size[1]/$orig_size[0])*$preview_size["x"]);
            
	            //Detailbild (Größe beibehalten wenn bild kleiner als die Einstellung
				if ( $orig_size[0] < base64_decode($settings["pics"]["max_details_width"]) )
				{
				    $detail_size["x"] = $orig_size[0];
				} else $detail_size["x"]  = base64_decode($settings["pics"]["max_details_width"]);
	            $detail_size["y"]  = ceil(($orig_size[1]/$orig_size[0])*$detail_size["x"]);
	        }
	        //Ausrechnen, wenn das Bild senkrecht ist (Breite ist KLEINER als die höhe)
	        else
	        {
	            //Vorschaubild
	            $preview_size["y"] = base64_decode($settings["pics"]["max_preview_width"]);
	            $preview_size["x"] = ceil(($orig_size[0]/$orig_size[1])*$preview_size["y"]);
            
	            //Detailbild (Größe beibehalten wenn bild kleiner als die Einstellung
				if ( $orig_size[1] < base64_decode($settings["pics"]["max_details_width"]) )
				{
				    $detail_size["y"] = $orig_size[1];
	            } else $detail_size["y"]  = base64_decode($settings["pics"]["max_details_width"]);
	            $detail_size["x"]  = ceil(($orig_size[0]/$orig_size[1])*$detail_size["y"]);
    	    }

	        //Neue Bilder vorbereiten
	        $preview_img = imagecreatetruecolor($preview_size["x"],$preview_size["y"]); # Vorschaubild
	        $detail_img  = imagecreatetruecolor($detail_size["x"],$detail_size["y"]);   # Detailbild
        
	        //neue Bilder zurecht stutzen auf die neue größe
	        imagecopyresampled($preview_img,$orig_img,0,0,0,0,$preview_size["x"],$preview_size["y"],$orig_size[0],$orig_size[1]); # Vorschaubild
	        imagecopyresampled($detail_img,$orig_img,0,0,0,0,$detail_size["x"],$detail_size["y"],$orig_size[0],$orig_size[1]);    # Detailbild
        
	        //Speichern
	        imagejpeg($preview_img,$settings["pics"]["img_path"]."/".$img_id."s.jpg",$settings["pics"]["quality"]); # Vorschaubild
	        imagejpeg($detail_img,$settings["pics"]["img_path"]."/".$img_id."b.jpg",$settings["pics"]["quality"]);  # Detailbild
        
	        //Meldung
	        $content = message(PLUGIN_PATH,"pics","pictrue",$SID);
	    }
	    else
	    {
	        //Fehlermeldung
	        $content = message(PLUGIN_PATH,"pics","picfalse",$SID);
	    }
	}
	else
	{
	    //Wurde ein Bild angegeben?
	    //if ( ($_FILES['previewfile']['type'] == "image/jpeg") && ($_FILES['detailfile']['type'] == "image/jpeg") )
	    if ( (stristr($_FILES['previewfile']['type'],"jpeg")) && (stristr($_FILES['detailfile']['type'],"jpeg")) )
	    {
	        //Zur Datenbank hinzufügen
	        $img_id = $psql->add_item($_POST['group'],$_POST['title'],$_POST['desc']);
        
	        //Bilder speichern
	        move_uploaded_file($_FILES['previewfile']['tmp_name'],$settings["pics"]["img_path"]."/".$img_id."s.jpg"); # Vorschaubild
	        move_uploaded_file($_FILES['detailfile']['tmp_name'],$settings["pics"]["img_path"]."/".$img_id."b.jpg");  # Detailbild
        
	        //Meldung
	        $content = message(PLUGIN_PATH,"pics","pictrue",$SID);
	    }
	    else
	    {
	        //Fehlermeldung
	        $content = message(PLUGIN_PATH,"pics","picfalse",$SID);
	    }
	}


	/*--- Bild hinzufügen --- ende --- */



	/*--- abschluss --- start --- */

	//Ausgabe
	$ground_tpl->insertVar("text",$content);
	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title","Bildergalerie");
	$ground_tpl->insertVar("name","pics");
	$retVal = $ground_tpl->getResult();

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>