<?

/*

   BESCHREIBUNG:

   F�hrt die am h�ufig ben�tigten Aktionen aus in den Admin
   Bereichen. Wie z.B. ben�tigte Dateien einbinden
   Template Klassen vorbeiten, Session �berpr�fen, usw.

   Dies gilt aber nicht f�r die Login Skripte, da diese zwar
   �hnliche Aufgaben erf�llen m�ssen aber dennoch nicht die selben.

*/

function init_admin() {
	/*--- Grundaktionen --- start --- */

	//Ben�tigte Dateien einbinden
	require(BASE_PATH."/includes/actions/variables.actions.php");   # Register Globals OFF
	require(BASE_PATH."/includes/functions/global.functions.php");  # Allgemeine Funktionen
	require(BASE_PATH."/includes/classes/template.class.php");      # Template Funktionen
	require(BASE_PATH."/includes/classes/mysql.class.php");         # MySQL Funktionen
	require(BASE_PATH."/includes/functions/session.functions.php"); # Session Funktionen
	require(BASE_PATH."/includes/functions/menue.functions.php");    # Sortierfunktionen

	//Benutzerfunktionen
	require_once(USER_PATH."/settings/base/mysql.settings.php");     # MySQL Konfiguration
	require_once(USER_PATH."/settings/base/other.settings.php");     # Zus�tzliche Konfigurationen

	//Klassen vorbereiten
	$ground_tpl = new template_class; # Das Admin Grunddesign Template
	$tpl        = new template_class; # Das Inhalt Template
	$sql        = new mysql_class;    # MySQL Funktionen

	//Pfade anpassen
	$ground_tpl->path = ABS_PATH."/src/templates/";
	$tpl->path        = BASE_PATH."/templates/";

	//SQL Einstellungen �bergeben
	$sql->prefix = $settings["mysql"]["table_prefix"];
	$sql->order  = $settings["mysql"]["order_by"];

	//Variablen setzen
	$SID = ( $_GET['SID'] ) ? $_GET['SID'] : $_POST['SID'];

	/*--- Grundaktionen --- ende --- */



	/*--- SQL --- start --- */

	//Verbindung herstellen
	$sql->connect($settings["mysql"]["host"],$settings["mysql"]["user"],$settings["mysql"]["pass"],$settings["mysql"]["db"]);
	
	//Installationspruefung
	if ( !$sql->install_check("base") )
	{
	    $sql->close();
	    die("<b>Beeco noch nicht installiert</b><br>Lesen Sie dazu die Anleitung!");
	}

	//Die Einstellungen aus der DB zu den aktuellen hinzuf�gen
	$settings = $sql->load_settings($settings);

	/*--- SQL --- ende --- */



	/*--- check --- start --- */

	//Session vorhanden?
	if ( $SID )
	{
	    //Sesstion start
	    session_open($settings["tmp_dir"],$SID);

	    $sid_check = session_check($settings);

	    //G�ltigkeitstest
	    if ( ($sid_check == "0") || ($sid_check == "2") )
	    {
	        //Bei ung�ltiger session schlie�en
	        Session_Close();

	        //Verbindung beenden
	        if ( $sid_check == "0" ) { $sql->unregister_SID(); }
	        $sql->close();

	        //Zum login
	        header("Location: ".REL_PATH."/admin/");

	        //Sicherheitsbeenden falls header mislingt
	        die();
	    }
	    else $sql->updateTime();
	    
	    unset($sid_check);
	}
	else
	{
	    //Verbindung beenden
	    $sql->close();

	    //Zum login
	    header("Location: ".REL_PATH."/admin/");
    
	    //Sicherheitsbeenden falls header mislingt
	    die();
	}

	/*--- check --- start --- */

	//Objekte �bergeben
	return array("ground" => &$ground_tpl,"tpl" => &$tpl,"sql" => &$sql,"settings" => &$settings,"SID" => &$SID);
}

?>
