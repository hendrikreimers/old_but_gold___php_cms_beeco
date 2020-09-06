<?

/*
   BESCHREIBUNG:

   Verschlüsselt das Kennwort und erstellt alle benötigten
   Tabellen und Einstellungen.

*/

//installiert das plugin
function install($settings)
{
    //Plugin
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."plugins VALUES ('','time','".base64_encode("Terminkalender")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin hinzufügen fehler",mysql_error()));
    
    $plugin_id = mysql_insert_id();
    
    //Einstellungen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','color_past','".base64_encode($_POST['color_past'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','color_present','".base64_encode($_POST['color_present'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    //Menüpunkt hinzufügen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."items VALUES ('','".$plugin_id."','0','99','1','1','".base64_encode("Termine")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Menüpunkt hinzufügen fehler",mysql_error()));
    
    //Tabelle erstellen
    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."time` (
                           `id` bigint(255) NOT NULL auto_increment,
                           `date` date NOT NULL default '0000-00-00',
                           `title` varchar(255) NOT NULL default '',
                           `desc_id` bigint(255) NOT NULL default '0',
                           PRIMARY KEY  (`id`)
                           ) TYPE=MyISAM AUTO_INCREMENT=1";

    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle nicht erstellt",mysql_error()));

    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."time_desc` (
                           `id` bigint(255) NOT NULL auto_increment,
                           `text` longblob NOT NULL,
                           PRIMARY KEY  (`id`)
                           ) TYPE=MyISAM AUTO_INCREMENT=1";

    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle nicht erstellt",mysql_error()));
}

?>
