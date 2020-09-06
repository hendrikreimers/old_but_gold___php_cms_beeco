<?

/*
   BESCHREIBUNG:

   Verschl�sselt das Kennwort und erstellt alle ben�tigten
   Tabellen und Einstellungen.

*/

//�berpr�ft ob die Installation �ber das Admin Men� aufgerufen wird
function login_check($sql)
{
    //Session ID zuweisen
    $SID = ( $_GET['SID'] ) ? $_GET['SID'] : $_POST['SID'];

    //Pr�fen ob �berhaupt eine existiert
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
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin hinzuf�gen fehler",mysql_error()));
    
    $plugin_id = mysql_insert_id();
    
    //Men�punkt hinzuf�gen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."items VALUES ('','".$plugin_id."','0','99','1','1','".base64_encode("Suche")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Men�punkt hinzuf�gen fehler",mysql_error()));
}

?>
