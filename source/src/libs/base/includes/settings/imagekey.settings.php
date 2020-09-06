<?

/* BESCHREIBUNG:
   Konfigurationsdatei fur die Bildgenerierung
   der Sicherheitsabfrage im Admin-Bereich, im
   Gaestebuch und im Kontaktformular
*/

//Maximale Bildgr��e
#$settings["imgGen"]["picture"]["width"] = 270;
#$settings["imgGen"]["picture"]["height"] = 70;
$settings["imgGen"]["picture"]["width"] = 170;
$settings["imgGen"]["picture"]["height"] = 40;

//Bildqualitaet (bzw. Dateigroesse)
$settings["imgGen"]["picture"]["quality"] = 30;

//Maximale Gr��e der St�rungen
#$settings["imgGen"]["disturb"]["maxW"] = 5;
#$settings["imgGen"]["disturb"]["maxH"] = 5;
$settings["imgGen"]["disturb"]["maxW"] = 1;
$settings["imgGen"]["disturb"]["maxH"] = 1;


//Farbbereich f�r die St�rungen
/*$settings["imgGen"]["disturb"]["colBeg"] = 130;
$settings["imgGen"]["disturb"]["colEnd"] = 180;*/
$settings["imgGen"]["disturb"]["colBeg"] = 0;
$settings["imgGen"]["disturb"]["colEnd"] = 55;

//Verf�gbare Schriftarten Pfad
$settings["imgGen"]["font"]["path"] = ABS_PATH."/src/gfx/fonts";

//Verf�gbare Zeichen
#$settings["imgGen"]["font"]["fields"] = array_merge(range(0,9),range("a","n"),range("p","z"),range("A","N"),range("P","Z"));  # Zahlen und Buchstaben (au�er dem Buchst. O aufgrund Verwechslungmit der Zahl 0)
$settings["imgGen"]["font"]["fields"] = range(0,9); # Alternative mit nur Zahlen

//Zeichen Eigenschaften
$settings["imgGen"]["font"]["maxNum"]    = 4; # Maximale Anzahl an Zeichen
#$settings["imgGen"]["font"]["tolerance"] = 10; # Toleranz Abstand zwischen den Buchstaben (ausprobieren)
$settings["imgGen"]["font"]["tolerance"] = 4; # Toleranz Abstand zwischen den Buchstaben (ausprobieren)

#$settings["imgGen"]["font"]["minSize"] = 35; # Mindesgr��e der Zahl
#$settings["imgGen"]["font"]["maxSize"] = 40; # Maximalgr��e der Zahl
$settings["imgGen"]["font"]["minSize"] = 28; # Mindesgr��e der Zahl
$settings["imgGen"]["font"]["maxSize"] = 30; # Maximalgr��e der Zahl


$settings["imgGen"]["font"]["minPosY"] = 5; # Mindestabstand zum oberen Rand
                                            # maxPosY wird errechnet aufgrund der abweichenden Bildgr��e
	                                    # und der abweichenden Schriftgr��e 
					    
$settings["imgGen"]["font"]["colBeg"] = 250;   # Farbbereich Anfang
$settings["imgGen"]["font"]["colEnd"] = 255; # Farbbereich Ende

?>