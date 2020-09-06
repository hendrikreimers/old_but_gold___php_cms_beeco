<?

//Admin Bereich
$settings["login"]["timeout"] = '900'; # Login Timeout (in Sekunden), Standard 900 Sek (15 Min.)

//Temporärer Pfad (für die Sicherheitsabfrage und Session Ablage)
$settings["tmp_dir"]  = ini_get("upload_tmp_dir"); # Aus der PHP Konfiguration beziehen
#$settings["tmp_dir"] = "/home/user/temp";         #Absolut Path Sample

//Spezielle MIME Header
#$settings["display"]["mime"] = 'application/xml';               # Darstellungsformat der Templates für die User-Seiten
#$settings["display"]["mime"] = 'text/html';                     # Darstellungsformat der Templates für die User-Seiten
$settings["display"]["mime"]  = 'text/html; charset=iso-8859-1'; # Darstellungsformat der Templates für die User-Seiten

//Dartellungs Optionen
$settings["display"]["pluginground"] = '1'; # Lädt das Plugin in das User Template, nützlich bei nicht HTML Darstellung wie XML (Standard: 1)
$settings["display"]["textencode"]   = '0'; # Ignoriert alle HTML Tags (htmlentities), bei jeglichem Text für BASE (auch plugins ignorieren). Nützlich bei XML.
$settings["display"]["encodeUml"]    = '0'; # Wandelt alle DE Umlaute in HTML um (Empfohlen: 1)

//Format Umwandlung
$settings["display"]["secureMail"] = '1'; # Wandelt jede E-Mail in ASCII um. (Scheinbar effektiver Spam Schutz im Augenblick)
$settings["display"]["autoLink"]   = '0'; # Wandelt jede E-Mail und jeden Verweis (Bsp.: http://... oder www.kern23.de) in einen Link um.

//Rewrite Engine
$settings["display"]["rewrite"] = '0'; # Alle PHP typischen URLs sehen aus wie HTML Links (benoetigt HTACCESS mit "AllowOverride ALL" und mod_rewrite)

// Wenn ein Bild eine andere Original Größe hat wird diese verändert
// Bezieht sich nur auf Bilder aus der Bildergalerie und erforder diese auch
$settings["display"]["imgResize"] = '0';

//Abschalten des BEECO Comments im Quellcode
#define("IMP",0);
define("IMP_OVERRIDE","0");
define("IMP_ATTACH","  /* =============================================== */\n".
                    "  /*  GESTALTUNG UND UMSETZUNG:                      */\n".
		    "  /*  kern23 | IT & Neue Medien                      */\n".
		    "  /*  www.kern23.de                                  */\n".
		    "  /* =============================================== */\n");
	  
?>