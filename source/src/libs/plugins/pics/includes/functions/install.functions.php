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
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."plugins VALUES ('','pics','".base64_encode("Bildergalerie")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin hinzufügen fehler",mysql_error()));
    
    $plugin_id = mysql_insert_id();
    
    //Einstellungen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','max_cols','".base64_encode($_POST['max_cols'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','max_rows','".base64_encode($_POST['max_rows'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','view','".$_POST['view']."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','auto_resize','".$_POST['auto_resize']."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','max_preview_width','".base64_encode($_POST['max_preview_width'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','max_details_width','".base64_encode($_POST['max_details_width'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));
	
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','order_groups','".(($_POST['order_groups'] == "1") ? "ASC" : "DESC")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));
	
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$plugin_id."','order_items','".(($_POST['order_items'] == "1") ? "ASC" : "DESC")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"hinzufügen fehler",mysql_error()));

    //Menüpunkt hinzufügen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."items VALUES ('','".$plugin_id."','0','99','1','1','".base64_encode("Galerie")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Menüpunkt hinzufügen fehler",mysql_error()));
    
    //Tabelle erstellen
    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."pics_groups` (
                           `id` bigint(255) NOT NULL auto_increment,
                           `mode` int(1) NOT NULL default '0',
                           `title` varchar(255) NOT NULL default '',
                           `desc` longblob NOT NULL,
                           PRIMARY KEY  (`id`)
                           ) TYPE=MyISAM AUTO_INCREMENT=1";

    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle nicht erstellt",mysql_error()));

    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."pics_items` (
                           `id` bigint(255) NOT NULL auto_increment,
                           `group_id` bigint(255) NOT NULL,
                           `title` varchar(255) NOT NULL default '',
                           `desc` longblob NOT NULL,
                           PRIMARY KEY  (`id`)
                           ) TYPE=MyISAM AUTO_INCREMENT=1";

    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle nicht erstellt",mysql_error()));
}

?>
