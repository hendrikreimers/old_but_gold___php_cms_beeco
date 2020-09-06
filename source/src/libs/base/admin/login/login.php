<?

/*
   BESCHREIBUNG:

   Überprüft die Benutzereingaben auf GÜltigkeit
   Anschließend wird entweder eine Fehlermeldung angezeigt
   oder zur nächsten Seite weiter geleitet

*/

function initialize()
{
	/*--- Grundaktionen --- start --- */

	//Benötigte Dateien einbinden
	require(BASE_PATH."/includes/actions/variables.actions.php");    # Register Globals OFF
	require(BASE_PATH."/includes/functions/global.functions.php");   # Allgemeine Funktionen
	require(BASE_PATH."/includes/classes/template.class.php");       # Template Funktionen
	require(BASE_PATH."/includes/classes/mysql.class.php");          # MySQL Funktionen
	require(BASE_PATH."/includes/functions/session.functions.php");  # Session funktionen

	require(USER_PATH."/settings/base/mysql.settings.php");     # MySQL Konfiguration
	require(USER_PATH."/settings/base/other.settings.php");    # Zusätzliche Konfigurationen

	//Klassen vorbereiten
	$ground_tpl = new template_class; # Das Admin Grunddesign Template
	$sql        = new mysql_class;    # MySQL Funktionen
	
	//Pfade anpassen
	$ground_tpl->path = ABS_PATH."/src/templates/";
	
	//SQL Einstellungen übergeben
	$sql->prefix = $settings["mysql"]["table_prefix"];
	$sql->order  = $settings["mysql"]["order_by"];
	
	/*--- Grundaktionen --- ende --- */
	
	
	
	/*--- SQL --- start --- */
	
	//Verbindung herstellen
	$sql->connect($settings["mysql"]["host"],$settings["mysql"]["user"],$settings["mysql"]["pass"],$settings["mysql"]["db"]);
	
	//Die Einstellungen aus der DB zu den aktuellen hinzufügen
	$settings = $sql->load_settings($settings);
	
	/*--- SQL --- ende --- */
	
	
	
	/*--- Check --- start --- */
	
	//Passwort verschlüsseln um es mit dem aus der DB vergleichen zu können
	$crypted_password = crypt_password($_POST['password'],$settings["base"]["checksum"]);
	
	//Kennwort aus der DB holen
	$db_pass = base64_decode($settings["base"]["pass"]);
	
	//Bei korrekten Daten weitermachen
	if ( (strtoupper($_POST['username']) == strtoupper(base64_decode($settings["base"]["user"]))) && ($crypted_password == $db_pass) && (crypt_password($_POST['secval'],$settings["base"]["checksum"]) == $_POST['seckey']) )
	{
	    //Session öffnen
	    session_name("SID");
	    $SID = session_open($settings["tmp_dir"]);
	
	    //Benutzerdaten sichern (zum testen einer gültigen session)
	    $_SESSION['user']      = $_POST['username'];
	    $_SESSION['pass']      = $crypted_password;
	    $_SESSION['user_ip']   = $_SERVER['REMOTE_ADDR'];
	    $_SESSION['user_host'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	
	    //SID speichern
	    if ( ($settings['base']['loginSID'] <> "0") && ($settings['base']['loginSID'] <> $SID) )
	    {
	        //Die SID des Anmeldeversuches beenden
	        Session_Close();
	
	        //Aktuelle SID öffnen und auf gültigkeit prüfen
	        if ( $settings["login"]["timeout"] < (time()-($sql->getTime())) )
	        {
	            //Session des anderen Users schließen
	            session_open($settings["tmp_dir"],$settings['base']['loginSID']);
	            Session_Close();
	            
	            //Die neue Session starten (ansonsten kann der andere user evtl. drin bleiben)
	            //Weil die alte SID neu vergeben wird
	            session_open($settings["tmp_dir"],$SID);
	            $_SESSION['user'] = $_POST['username'];
	            $_SESSION['pass'] = $crypted_password;
	            $sql->updateTime();
	            $sql->register_SID($SID);
	            
	            //Ausgabe
	            $text = message(BASE_PATH,"login","unlogged",$SID);
	        }
	        else $text = message(BASE_PATH,"login","logged");
	
	        //Fehlerausgabe
	        $ground_tpl->load("ground");
	        $ground_tpl->insertVar("path",REL_PATH);
	        $ground_tpl->insertVar("title","Administration");
	        $ground_tpl->insertVar("name","Login");
	
	        //Fehlermeldung einsetzen
	        $ground_tpl->insertVar("text",$text);
	
	        //Ausgabe
	        $retVal = $ground_tpl->getResult();
	
	        //Speicher friegeben
	        $ground_tpl->clear();
	
	        //Verbindung beenden
	        $sql->close();
			
			return $retVal;
	    }
	    else
	    {
	        $sql->updateTime();
	        $sql->register_SID($SID);
	
	        //Verbindung beenden
	        $sql->close();
	
	        //Zur nächsten Seite leiten...
	        header("Location: index.php?SID=".$SID."&action=main");
	    }
	}
	else
	{
	    //Fehlerausgabe
	    $ground_tpl->load("ground");
	    $ground_tpl->insertVar("path",REL_PATH);
	    $ground_tpl->insertVar("title","Administration");
	    $ground_tpl->insertVar("name","Login");
	
	    //Fehlermeldung vorbereiten
	    $text = message(BASE_PATH,"login","false");
	    
	    //Fehlermeldung einsetzen
	    $ground_tpl->insertVar("text",$text);
	    
	    //Ausgabe
	    $retVal = $ground_tpl->getResult();
	    
	    //Speicher friegeben
	    $ground_tpl->clear();
		
		return $retVal;
	}

	/*--- Check --- ende --- */
}

?>