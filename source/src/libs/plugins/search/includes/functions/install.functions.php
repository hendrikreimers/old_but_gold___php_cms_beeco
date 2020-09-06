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
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."plugins VALUES ('','search','".base64_encode("Suche")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin hinzufügen fehler",mysql_error()));
    
    $plugin_id = mysql_insert_id();
    
    //Menüpunkt hinzufügen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."items VALUES ('','".$plugin_id."','0','99','1','1','".base64_encode("Suche")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Menüpunkt hinzufügen fehler",mysql_error()));
}

?>
