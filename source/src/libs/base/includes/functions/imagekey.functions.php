<?

function imagekey($settings,$checksum,$tmpDir)
{
    /* --- DEFAULTS --- BEGIN --- */

    //Zufallsgenerator initieren
    srand((double)microtime()*1000000);

    //Verzeichnis öffnen zum Schriftarten sammeln
    $cdir = dir($settings["font"]["path"]);

    //Schriftarten auslesen
    while ( $entry = $cdir->read() )
    {
        //Nur TTF Schriftarten
        if ( preg_match("/^(.*)(\.ttf)$/si",$entry) )
        {
            //Schriftart merken
            $fonts[] = $settings["font"]["path"]."/".$entry;
        }
    }

    //Verzeichnis schließen
    $cdir->Close();

    //Bild erstellen
    $im = imagecreatetruecolor($settings["picture"]["width"],$settings["picture"]["height"]);

    //Farben festlegen (Textfarbe wird individuell festgelegt)
    $black    = imagecolorallocate ($im,0,0,0);                                                             # Hintergrund
    $randCol  = imagecolorallocate ($im,rand($settings["disturb"]["colBeg"],$settings["disturb"]["colEnd"]),rand($settings["disturb"]["colBeg"],$settings["disturb"]["colEnd"]),rand($settings["disturb"]["colBeg"],$settings["disturb"]["colEnd"])); # Störungen

    //Abstand der Buchstaben (X)
    $fontXStep = ceil(($settings["picture"]["width"]/$settings["font"]["maxNum"]));
    $fontXStep -= $settings["font"]["tolerance"];

    /* --- DEFAULTS --- END --- */



    /* --- PICTURE GENERATING --- BEGIN --- */

    /* EINFÜGEN DER STÖRUNGEN */

    //Jeden Pixel abarbeiten (X)
    for ( $i = 0; $i <= $settings["picture"]["width"]; $i++ )
    {
        //Jeden Pixel abarbeiten (Y)
        for ( $j = 0; $j <= $settings["picture"]["height"]; $j++ )
        {
             //Per Zufall ein Störfeld einfügen
             if ( rand(0,20) == "1" )
    	     {
	         	//Zufällige Störgröße
	         	$curW = rand(1,$settings["disturb"]["maxW"]); # Breite
	         	$curH = rand(1,$settings["disturb"]["maxH"]); # Höhe
	     
	         	//Störung zeichnen
	 			imagefilledrectangle($im,$i,$j,$i+$curW,$j+$curH,$randCol);
	     	}
        }
    }
	
	
    /* EINFÜGEN DES TEXTES */

    //Maximal angegebene Menge an Zahlen
    for ( $i = 1; $i <= $settings["font"]["maxNum"]; $i ++ )
    {
        //Konfiguration
        $curFont = $fonts[rand(0,sizeof($fonts)-1)]; # Current Font
		$curSize = rand($settings["font"]["minSize"],$settings["font"]["maxSize"]); # Aktuelle Schriftgröße
        
		//Position
		$curX    = ceil($fontXStep*$i-($fontXStep/2)); # Schriftposition (X)
        $curY    = rand($settings["font"]["minPosY"]+$curSize,$settings["picture"]["height"]-($settings["font"]["minPosY"])); # Schriftposition (Y)
        
		//Aktuelles Zeichen
		$char      = $settings["font"]["fields"][rand(0,sizeof($settings["font"]["fields"])-1)];
        
		//Farbe (G ausgelassen da es sonst zu dunkel wird)
		$r = rand($settings["font"]["colBegin"],$settings["font"]["colEnd"]);
		#$g = rand($settings["font"]["colBegin"],$settings["font"]["colEnd"]);
		$b = rand($settings["font"]["colBegin"],$settings["font"]["colEnd"]);
        $g = 255;
		$color = imagecolorallocate($im,$r,$g,$b);
		
		$angle = rand(-20,20);
        imagettftext ($im,$curSize,$angle,$curX,$curY,$color,$curFont,$char);
		
		//Zeichenkette merken um es als Ergebnis zu liefern
		$retval .= $char;
    }

    /* --- PICTURE GENERATING --- END --- */



    /* --- ENDINGS --- BEGIN --- */

    //Prüfsumme bilden
    $retval = md5($retval.$checksum);
    #$retval = md5($retval.$checksum.(date("i")+$settings["lifetime"]));

    //Bild ausgeben
    imagejpeg($im,$tmpDir."/".$retval.".jpg",$settings["picture"]["quality"]);

    //Speicher freigeben
    imagedestroy($im);
	
    //Ergebnis
    return $retval;

    /* --- ENDINGS --- END --- */
}

?>