<?

/*
   BESCHREIBUNG:
   
   Macht die Vorbereitung für die User Anzeige
   die von der index.php und von den plugins genutzt wird
   
   Wird ausgelagert da diese Zeilen ständig genutzt werden
   udn somit nur eine große datei anstatt 20 kleine (je nach plugins menge)
   existiert.
   
*/

function init() {
	/*--- Grundaktionen --- start --- */

	//Benötigte Dateien einbinden
	require(BASE_PATH."/includes/actions/variables.actions.php");  # Register Globals OFF
	require(BASE_PATH."/includes/functions/global.functions.php"); # Allgemeine Funktionen
	require(BASE_PATH."/includes/functions/text.functions.php");   # Textdarstellungsfunktionen
	require(BASE_PATH."/includes/classes/mysql.class.php");        # MySQL Funktionen
	require(BASE_PATH."/includes/classes/template.class.php");     # Template Funktionen
	require(BASE_PATH."/includes/functions/menue.functions.php");   # Sortierfunktionen

	//Benötigte Benutzer Konfurationen
	require(USER_PATH."/settings/base/mysql.settings.php");    # MySQL Konfiguration
	require(USER_PATH."/settings/base/other.settings.php");    # Zusätzliche Konfigurationen

	//Objekte vorbereiten
	$sql = new mysql_class;    # SQL Funktionen
	$tpl = new template_class; # Template funktionen

	//Objekte konfigurieren
	$sql->prefix = $settings["mysql"]["table_prefix"];
	$sql->order  = $settings["mysql"]["order_by"];

	$tpl->path = USER_PATH."/templates/base/";

	//Variablen auf Gueltigkeit pruefen
	if ( !preg_match("=^[0-9]*$=",$_GET['id']) ) { unset($_GET['id']); }                                # Gültige ID?
	if ( !preg_match("=^([a-z0-9_-]*)([/a-z0-9_-]{0,})$=i",$_GET['style']) ) { unset($_GET['style']); } # Gültige Template angabe?

	//Rewrite Variablen auf Gueltigkeit pruefen
	if ( (!preg_match("=^([a-z0-9_-]*)$=i",$_GET['title'])) || ($settings["display"]["rewrite"] == "0") ) { unset($_GET['title']); } # Gültige Template angabe?

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

	//Einstellungen laden
	$settings = $sql->load_settings($settings);
	
	//Im Falle der Rewrite Funktion ID laden
	if ( ($settings["display"]["rewrite"] == "1") && (strlen($_GET['title']) > 0) ) {
		$_GET['id'] = $sql->loadRewID($_GET['title']);
	}
	
	//Wenn keine ID erst beste ID
	if ( $_GET['id'] < 1 )
	{
	   #$_GET['id'] = $sql->get_default_id();
	   $menue = $sql->load_menue(0);
	   $deep[0] = $menue;
	   $deep = sort_menues($deep);
	   $menue   = $deep[0];
	   $_GET['id'] = $menue[0]["id"];
	   unset($menue);
	   unset($deep);
	}
	/*--- SQL --- ende --- */



	/* --- ausgabe vorbereitung --- start --- */
	
	//Template laden
	if ( ($settings["base"]["template_override"] == "1") && ($_GET['style']) )
	{
	    //Wenn Template Override aktiviert ist das angegebene Template laden
	    $tpl->load($_GET['style'],1);
	}
	else $tpl->load("index",1); # Ansonsten Standard Template
	
	//Das Menue in das Template laden
	$menues = $sql->load_rek_menues($_GET['id']); # Alle Menüeinträge und Position laden

	//Sortieren
	$menues["items"] = sort_menues($menues["items"]);

	if ( $menues )
	{
	    $refTpl = &$tpl;
		insert_menue($settings,$refTpl,$menues["items"],$menues["position"],"0"); # Alles in das Template
		unset($refTpl);
	}

	//Variablen einsetzen
	$tpl->insertVar("path",REL_USER_PATH);      # Pfad für Grafiken usw.
	
	//Aktueller Titel des ausgewählten Menüpunktes
	$tpl->insertVar("cur_title",htmlentities(stripslashes(base64_decode($menues["position"][sizeof($menues["position"])-1]["title"]))));
	$tpl->insertVar("cur_title_raw",stripslashes(base64_decode($menues["position"][sizeof($menues["position"])-1]["title"])));
	$tpl->insertVar("cur_id",$_GET['id']);

	//Header für Darstellung ausgeben (laut config file)
	header('Content-Type: '.$settings['display']['mime']);

	/* --- ausgabe vorbereitung --- ende --- */

	//Rückgabewert
	return array("sql" => &$sql, "tpl" => &$tpl,"settings" => &$settings);
}

?>