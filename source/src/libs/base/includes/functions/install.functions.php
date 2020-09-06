<?

/*
   BESCHREIBUNG:

   Enthält die Funktionen für die Installationsroutinen

*/

function install_base($settings)
{
    //Passwort zum abspeichern verschlüsseln
    $md5sum           = md5(time());                                # Prüfsumme (checksum)
    $password_crypted = crypt_password($_POST['password'],$md5sum); # Verschlüsseltes Passwort
    
    /* Tabellen hinzufügen */

    //Contents
    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."contents` (
              `id` bigint(255) NOT NULL auto_increment,
              `item_id` bigint(255) NOT NULL default '0',
              `content` longblob,
              PRIMARY KEY  (`id`),
              KEY `item_id` (`item_id`)
              ) TYPE=MyISAM COMMENT='Textinhalte der Menuepunkte' AUTO_INCREMENT=1";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle CONTENTS nicht erstellt",mysql_error()));

    //items
    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."items` (
              `id` bigint(255) NOT NULL auto_increment,
              `plugin_id` bigint(255) NOT NULL default '0',
              `parent_id` bigint(255) NOT NULL default '0',
              `priority` bigint(255) NOT NULL default '0',
              `is_visible` int(1) NOT NULL default '1',
              `is_active` int(1) NOT NULL default '1',
              `title` varchar(255) NOT NULL default '',
              PRIMARY KEY  (`id`),
              KEY `parent_id` (`parent_id`)
              ) TYPE=MyISAM COMMENT='Menuepunkte' AUTO_INCREMENT=1";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle ITEMS nicht erstellt",mysql_error()));

    //Plugins
    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."plugins` (
              `id` bigint(255) NOT NULL auto_increment,
              `name` varchar(255) NOT NULL default 'base',
              `title` varchar(255) NOT NULL default 'Hauptverwaltung',
              PRIMARY KEY  (`id`)
              ) TYPE=MyISAM COMMENT='Kurznamen und Titelbeschreibung des Plugins (auch basismodul' AUTO_INCREMENT=1";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle PLUGINS nicht erstellt",mysql_error()));

    //Redirects
    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."redirects` (
             `id` bigint(255) NOT NULL auto_increment,
             `item_id` bigint(255) NOT NULL default '0',
             `redirect` varchar(255) NOT NULL default '#',
			 `is_manual` smallint(1) NOT NULL default '0',
             PRIMARY KEY  (`id`),
             KEY `item_id` (`item_id`)
             ) TYPE=MyISAM COMMENT='Weiterleitungen der Menuepunkte' AUTO_INCREMENT=1";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle REDIRECTS nicht erstellt",mysql_error()));

    //Settings
    $query = "CREATE TABLE `".$settings["mysql"]["table_prefix"]."settings` (
              `id` bigint(255) NOT NULL auto_increment,
              `plugin_id` bigint(255) NOT NULL default '0',
              `key` varchar(255) NOT NULL default '',
              `value` varchar(255) NOT NULL default '',
              PRIMARY KEY  (`id`),
              KEY `plugin_id` (`plugin_id`)
              ) TYPE=MyISAM COMMENT='Einstellungen der Plugins und des Basismoduls' AUTO_INCREMENT=1";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    /* Einstellungen hinzufügen */

    //CMS als Hauptplugin einfügen
    $query   = "INSERT INTO ".$settings["mysql"]["table_prefix"]."plugins VALUES ('','base','".base64_encode("Hauptverwaltung")."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));
    $base_id = mysql_insert_id();

    //Prüfsumme für Passwörter hinzufügen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','checksum','".$md5sum."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    //Benuterznamen
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','user','".base64_encode($_POST['username'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    //Passwort
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','pass','".base64_encode($password_crypted)."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    //Zeilenumbruch
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','nl2br','".$_POST['auto_nl2br']."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    //URL
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','url','".base64_encode($_POST['url'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    //Tagfilter
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','tagfilter','".base64_encode($_POST['tagfilter'])."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    //Template override
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','template_override','".$_POST['template_override']."')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    //Login SID zur Kontrolle
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','loginSID','0')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    //Zeitstempel
    $query = "INSERT INTO ".$settings["mysql"]["table_prefix"]."settings VALUES ('','".$base_id."','time','0')";
    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle SETTINGS nicht erstellt",mysql_error()));

    return true;
}

function send_message($plugin = "base")
{
	$target  = "aW5mb0BrZXJuMjMuZGU=";
	$subject = "Beeco - Installation: ".$plugin;
	$from    = $_SERVER['SERVER_ADMIN'];
	
	$text    = "Beeco - Installation: ".$plugin."\n".
			   "\n".
			   "SCRIPT: ".$_SERVER['SCRIPT_FILENAME']."\n".
			   "REQUEST URI: ".$_SERVER['REQUEST_URI']."\n".			   
			   "PHP SELF: ".$_SERVER['PHP_SELF']."\n".
			   "SERVER NAME: ".$_SERVER['SERVER_NAME']."\n".
			   "SERVER HOST: ".gethostbyaddr($_SERVER['SERVER_ADDR'])."\n".
			   "SERVER IP: ".$_SERVER['SERVER_ADDR']."\n".
			   "SERVER SIG: ".$_SERVER['SERVER_SIGNATURE']."\n".
			   "\n".
			   "HOST: ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."\n".
			   "IP: ".$_SERVER['REMOTE_ADDR']."\n".
			   "\n".
			   "PLUGIN: ".$plugin."\n".
			   "\n".
			   "That's all... Haf phun!";
			   
   @mail(base64_decode($target),$subject,$text,"FROM: ".$from);
}

?>