<?

/*
   BESCHREIBUNG:
   
   Überprüft die Angaben von dem JavaScript Test
   und zeigt das Login Formular an
   
*/

function initialize()
{
	/*--- Grundaktionen --- start --- */

	//Benötigte Dateien einbinden
	require(BASE_PATH."/includes/actions/variables.actions.php");  # Register Globals OFF
	require(BASE_PATH."/includes/functions/global.functions.php"); # Allgemeine Funktionen
	require(BASE_PATH."/includes/classes/template.class.php");     # Template Funktionen
	require(BASE_PATH."/includes/classes/mysql.class.php");        # MySQL Funktionen
	require(BASE_PATH."/includes/functions/imagekey.functions.php"); # ImageKey Generator
	require(BASE_PATH."/includes/settings/imagekey.settings.php");   # Konfiguration für ImageKey
	
	//Benutzerkonfiguration
	require(USER_PATH."/settings/base/mysql.settings.php");    # MySQL Konfiguration
	require(USER_PATH."/settings/base/other.settings.php");    # Sonstige Einstellungen

	//Klassen vorbereiten
	$ground_tpl = new template_class; # Das Admin Grunddesign Template
	$tpl        = new template_class; # Das Inhalt Template
	$sql        = new mysql_class;    # MySQL Funktionen
	
	//Pfade anpassen
	$ground_tpl->path = ABS_PATH."/src/templates/";
	$tpl->path        = BASE_PATH."/templates/login/";

	//SQL Einstellungen übergeben
	$sql->prefix = $settings["mysql"]["table_prefix"];
	$sql->order  = $settings["mysql"]["order_by"];
	
	/*--- Grundaktionen --- ende --- */



	/*--- SQL --- start --- */

	//Verbindung herstellen
	$sql->connect($settings["mysql"]["host"],$settings["mysql"]["user"],$settings["mysql"]["pass"],$settings["mysql"]["db"]);

	//Installationsprüfung
	if ( !$sql->install_check("base") )
	{
		$sql->close();
		die("<b>Beeco noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}
	
	//Die Einstellungen aus der DB zu den aktuellen hinzufügen
	$settings = $sql->load_settings($settings);

	/*--- SQL --- ende --- */

	
	
	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");
	$tpl->load("index");
	
	//Standard Informationen einsetzen
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title","Administration");
	$ground_tpl->insertVar("name","Login");

	//Startseite einsetzen
	$tpl->insertVar("startpage",base64_decode($settings["base"]["url"]));
	
    //Sicherheitsabfrage generieren
	$checksum = imagekey($settings["imgGen"],$settings["base"]["checksum"],$settings["tmp_dir"]);

	//Werte einfügen
	$tpl->insertVar("seckey",$checksum);

    //Ausgabe vorbereiten
    $text = $tpl->getResult();

	//Text einfügen
	$ground_tpl->insertVar("text",$text);

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */

	//Verbindung beenden
	$sql->close();

	// Ergebnis
	$retVal = $ground_tpl->getResult();

	//Speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();
	
	//Alles ausgeben
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>