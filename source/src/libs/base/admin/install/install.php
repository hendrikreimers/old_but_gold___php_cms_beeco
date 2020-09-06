<?

/*
   BESCHREIBUNG:
   
   Überprüft die Eingaben und ruft
   die Installationsroutine auf
   
*/
function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	/*--- Grundfunktionen --- start --- */

	//Benötigte Dateien einbinden
	require(BASE_PATH."/includes/actions/variables.actions.php");   # Register Globals AUS
	require(BASE_PATH."/includes/functions/global.functions.php");  # Allgemeine Funktionen
	require(BASE_PATH."/includes/classes/template.class.php");      # Template Funktionen
	require(BASE_PATH."/includes/functions/install.functions.php"); # Installationsroutinen

	require(USER_PATH."/settings/base/mysql.settings.php");    # MySQL Konfiguration

	//Admin Grunddesign: Template Klasse initialisieren
	$ground_tpl       = new template_class;    # Klasse zuweisen
	$ground_tpl->path = ABS_PATH."/src/templates/"; # Pfad zu den Templates

	/*--- Grundfunktionen --- ende --- */



	/*--- Check --- start --- */

	//Variablen initialisieren
	$uncorrect = 0;                                                       # Fehler-Prüfsumme
	$text    = "<B>Bitte überprüfen Sie Ihre Eingaben</B><br>".
    	       "Klicken Sie dazu im Browser auf \"Zur&uuml;ck\"<br><br>"; # Fehler Ausgabe Text

	//URL Eingabe korrekt?
	if ( ($_POST['url'] == "http://") || ($_POST['url'] == "") )
	{
	    $uncorrect = 1;
	    $text   .= "- Ungültigen URL angegeben!<br>";
	}

	//Benutzername korrekt
	if ( !$_POST['username'] )
	{
	    $uncorrect = 1;
	    $text     .= "- Keinen Benutzernamen angegeben<br>";
	}

	//passwort eingegeben
	if ( !$_POST['password'] )
	{
	    $uncorrect = 1;
	    $text     .= "- Kein Passwort angegeben<br>";
	}

	//Passwörter stimmen überein
	if ( $_POST['password'] != $_POST['password_phrase'] )
	{
	    $uncorrect = 1;
	    $text     .= "- Passwort Eingaben stimmen nicht überein<br>";
	}

	/*--- Check --- ende --- */



	/*--- installation --- start --- */

	//Installation bei bedarf beginnen
	if ( $uncorrect == "0" )
	{
	    //Ergebnis Ausgabe
	    $text = "<b>Installation erfolgreich!</b><br><br>".
	            "Klicken Sie <a href=\"./\">hier</a> ".
	            "um in den<br>Admin-Bereich zu gelangen.";
	            
	    //Datenbank Verbindung herstellen
	    @mysql_connect($settings["mysql"]["host"],
	                   $settings["mysql"]["user"],
	                   $settings["mysql"]["pass"]) or die(_sqlerror(__FILE__,__LINE__,"Verbindungsfehler",mysql_error()));
	
	    //Datenbank auswählen
	    @mysql_select_db($settings["mysql"]["db"]) or die(_sqlerror(__FILE__,__LINE__,"Verbindungsfehler",mysql_error()));
	
	    //Gucken ob es schon isntalliert ist
	    $query  = "SELECT id FROM ".$settings["mysql"]["table_prefix"]."plugins WHERE name = 'base'";
	    $result = @mysql_query($query);   
	
	    if ( !@mysql_result($result,0,"id") )
	    {
	        $pw = install_base($settings);
	    }
	    else { $text = "<b>Bereits installiert!</b><br><br>&Auml;ndern Sie den Tabellen Prefix in der SQL Konfiguration,<br> oder deinstallieren Sie zuvor das alte MiniCMS-SE"; }
	    
	    @mysql_close() or die(_sqlerror(__FILE__,__LINE__,"Verbindung konnte nicht geschlossen werden",mysql_error()));
	    send_message("base");
	}
	
	/*--- installation --- ende --- */
	
	
	
	/*--- Ausgabe --- start --- */
	
	//Templates laden
	$ground_tpl->load("ground"); # Grunddesign

	$ground_tpl->insertVar("path",REL_PATH);             # Sonderpfad
	$ground_tpl->insertVar("title","Installation");      # Name
	$ground_tpl->insertVar("name","Beeco Installation"); # Titel
	$ground_tpl->insertVar("text",$text);                # Text
	
	//ausgabe
	$retVal = $ground_tpl->getResult();
	
	//speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;

	/*--- Ausgabe --- ende --- */
}

?>