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
        
        //das richtige passwort filtern
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
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."plugins VALUES ('','gbook','".base64_encode("Gästebuch")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin hinzufügen fehler",mysql_error()));
    
    $plugin_id = mysql_insert_id();
    
    //Einstellungen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','max_entries','".$_POST['max_entries']."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));
    
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','enable_html','".$_POST['enable_html']."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));
    
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','send_notice','".$_POST['send_notice']."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));
    
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','notice_email','".base64_encode($_POST['notice_email'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','security','".(($_POST['security']) ? "1" : "0")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));
      
    //Menüpunkt hinzufügen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."items VALUES ('','".$plugin_id."','0','99','1','1','".base64_encode("Gästebuch")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Menüpunkt hinzufügen fehler",mysql_error()));
    
    //Tabelle erstellen
    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."gbook` (
                           `id` bigint(255) NOT NULL auto_increment,
                           `ip` varchar(255) NOT NULL default '',
                           `host` varchar(255) NOT NULL default '',
                           `date` date NOT NULL default '0000-00-00',
                           `time` time NOT NULL default '00:00:00',
                           `name` varchar(255) NOT NULL default '',
                           `email` varchar(255) NOT NULL default '',
                           `icq` varchar(255) NOT NULL default '',
                           `homepage` varchar(255) NOT NULL default '',
                           `text` longblob NOT NULL,
                           `comment` longblob NOT NULL,
                           PRIMARY KEY  (`id`)
                           ) TYPE=MyISAM AUTO_INCREMENT=4";

    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle nicht erstellt",mysql_error()));        
}

?>