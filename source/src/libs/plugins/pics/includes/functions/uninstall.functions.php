<?

//Deinstallieren des MiniCMS(SE)
function uninstall($password,$table_prefix,$img_path)
{
    //Password Kontrolle
    $query = "SELECT settings.value AS pass
                FROM ".$table_prefix."settings AS settings,
                     ".$table_prefix."plugins AS plugins
               WHERE plugins.id = settings.plugin_id
                 AND plugins.name = 'base'
                 AND settings.key = 'pass'";

    $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Passwort konnte nicht abgefragt werden",mysql_error())); # Ausführen
    $pass   = Explode("|",base64_decode(@mysql_result($result,0,"pass")));                                                                                  # Ergebnis speichern
    mysql_free_result($result);

    //Bei korrektem Passwort deinstallation beginnen
    if ( $password == base64_encode($pass[0]) )
    {
        //PluginID
        $query  = "SELECT id FROM ".$table_prefix."plugins WHERE name = 'pics'";
        $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"PluginID konnte nicht abgefragt werden",mysql_error())); # Ausführen
        $id     = @mysql_result($result,0,"id");                                                                                  # Ergebnis speichern
        mysql_free_result($result);

        //Löschen
        $query = "DELETE FROM ".$table_prefix."plugins WHERE id = '".$id."'";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin konnte nicht gelöscht werden",mysql_error())); # Ausführen
        
        $query = "DELETE FROM ".$table_prefix."settings WHERE plugin_id = '".$id."'";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Einstellungen konnten nicht gelöscht werden",mysql_error())); # Ausführen

        //Tabelle löschen
        $query = "DROP TABLE ".$table_prefix."pics_items";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Gästebuch Tabelle konnte nicht gelöscht werden",mysql_error())); # Ausführen
        
        $query = "DROP TABLE ".$table_prefix."pics_groups";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Gästebuch Tabelle konnte nicht gelöscht werden",mysql_error())); # Ausführen

        //Bilder löschen
        $dir = dir($img_path);
        while ( $entry = $dir->read() )
        {
            if ( preg_match("/(.*)(\.jpg)/i",$entry) )
            {
                unlink($img_path."/".$entry);
            }
        }

        return true;
    }
    else return false;
}
        
?>