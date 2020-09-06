<?

/*
   BESCHREIBUNG:

   Verschlüsselt das Kennwort und erstellt alle benötigten
   Tabellen und Einstellungen.

*/

//Überprüft ob die Installation über das Admin Menü aufgerufen wird
function login_check($sql)
{
    //Session ID zuweisen
    $SID = ( $_GET['SID'] ) ? $_GET['SID'] : $_POST['SID'];

    //Prüfen ob überhaupt eine existiert
    if ( $SID )
    {
        $settings = $sql->load_settings($settings);

        //Richtiges Passwort filtern (password overkill)
        $tmp = Explode("|",base64_decode($settings["base"]["pass"]));

		include("../../../includes/settings/other.settings.php");
		session_save_path($settings["tmp_dir"]);

        session_name("SID");
        session_id($SID);
        session_start();

        if ( (strtoupper($_SESSION['user']) == strtoupper(base64_decode($settings["base"]["user"]))) && ($_SESSION['pass'] == $tmp[0]) )
        {
            return "1";
        }
        else
        {
            session_destroy();
            return "0";
        }
    }
    else
    {
        return "0";
    }
}

//installiert das plugin
function install($settings)
{
    //Plugin
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."plugins VALUES ('','kontakt','".base64_encode("Kontakt-Formular")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin hinzufügen fehler",mysql_error()));
    
    $plugin_id = mysql_insert_id();
    
    //Mail TO
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','mailto','".base64_encode($_POST['mailto'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"mailto hinzufügen fehler",mysql_error()));
    
    //Pflichtfelder
    $nachname = ($_POST['nachname'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','nachname','".$nachname."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    $vorname = ($_POST['vorname'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','vorname','".$vorname."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    $firma = ($_POST['firma'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','firma','".$firma."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    $strasse = ($_POST['strasse'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','strasse','".$strasse."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    $plz = ($_POST['plz'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','plz','".$plz."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    $ort = ($_POST['ort'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','ort','".$ort."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    $telefon = ($_POST['telefon'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','telefon','".$telefon."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    $email = ($_POST['email'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','email','".$email."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    $nachricht = ($_POST['nachricht'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','nachricht','".$nachricht."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
	
	$security = ($_POST['security'] == "1") ? "1" : "0";
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','security','".$security."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Pflichtfelder Fehler!",mysql_error()));
    
    //Menüpunkt hinzufügen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."items VALUES ('','".$plugin_id."','0','99','1','1','".base64_encode("Kontakt")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Menüpunkt hinzufügen fehler",mysql_error()));
}

?>
