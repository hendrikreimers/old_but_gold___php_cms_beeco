<?

/* BESCHREIBUNG:
   Konfigurationsdatei fur die Bildgenerierung
   der Sicherheitsabfrage im Admin-Bereich, im
   Gaestebuch und im Kontaktformular
*/

//Maximale Bildgröße
#$settings["imgGen"]["picture"]["width"] = 270;
#$settings["imgGen"]["picture"]["height"] = 70;
$settings["imgGen"]["picture"]["width"] = 170;
$settings["imgGen"]["picture"]["height"] = 40;

//Bildqualitaet (bzw. Dateigroesse)
$settings["imgGen"]["picture"]["quality"] = 30;

//Maximale Größe der Störungen
#$settings["imgGen"]["disturb"]["maxW"] = 5;
#$settings["imgGen"]["disturb"]["maxH"] = 5;
$settings["imgGen"]["disturb"]["maxW"] = 1;
$settings["imgGen"]["disturb"]["maxH"] = 1;


//Farbbereich für die Störungen
/*$settings["imgGen"]["disturb"]["colBeg"] = 130;
$settings["imgGen"]["disturb"]["colEnd"] = 180;*/
$settings["imgGen"]["disturb"]["colBeg"] = 0;
$settings["imgGen"]["disturb"]["colEnd"] = 55;

//Verfügbare Schriftarten Pfad
$settings["imgGen"]["font"]["path"] = ABS_PATH."/src/gfx/fonts";

//Verfügbare Zeichen
#$settings["imgGen"]["font"]["fields"] = array_merge(range(0,9),range("a","n"),range("p","z"),range("A","N"),range("P","Z"));  # Zahlen und Buchstaben (außer dem Buchst. O aufgrund Verwechslungmit der Zahl 0)
$settings["imgGen"]["font"]["fields"] = range(0,9); # Alternative mit nur Zahlen

//Zeichen Eigenschaften
$settings["imgGen"]["font"]["maxNum"]    = 4; # Maximale Anzahl an Zeichen
#$settings["imgGen"]["font"]["tolerance"] = 10; # Toleranz Abstand zwischen den Buchstaben (ausprobieren)
$settings["imgGen"]["font"]["tolerance"] = 4; # Toleranz Abstand zwischen den Buchstaben (ausprobieren)

#$settings["imgGen"]["font"]["minSize"] = 35; # Mindesgröße der Zahl
#$settings["imgGen"]["font"]["maxSize"] = 40; # Maximalgröße der Zahl
$settings["imgGen"]["font"]["minSize"] = 28; # Mindesgröße der Zahl
$settings["imgGen"]["font"]["maxSize"] = 30; # Maximalgröße der Zahl


$settings["imgGen"]["font"]["minPosY"] = 5; # Mindestabstand zum oberen Rand
                                            # maxPosY wird errechnet aufgrund der abweichenden Bildgröße
	                                    # und der abweichenden Schriftgröße 
					    
$settings["imgGen"]["font"]["colBeg"] = 250;   # Farbbereich Anfang
$settings["imgGen"]["font"]["colEnd"] = 255; # Farbbereich Ende

?>