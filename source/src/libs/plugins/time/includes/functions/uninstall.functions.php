<?

//Deinstallieren des MiniCMS(SE)
function uninstall($password,$table_prefix)
{
    //Password Kontrolle
    $query = "SELECT settings.value AS pass
                FROM ".$table_prefix."settings AS settings,
                     ".$table_prefix."plugins AS plugins
               WHERE plugins.id = settings.plugin_id
                 AND plugins.name = 'base'
                 AND settings.key = 'pass'";

    $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Passwort konnte nicht abgefragt werden",mysql_error())); # Ausf�hren
    $pass   = Explode("|",base64_decode(@mysql_result($result,0,"pass")));                                                                                  # Ergebnis speichern
    mysql_free_result($result);

    //Bei korrektem Passwort deinstallation beginnen
    if ( $password == base64_encode($pass[0]) )
    {
        //PluginID
        $query  = "SELECT id FROM ".$table_prefix."plugins WHERE name = 'time'";
        $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"PluginID konnte nicht abgefragt werden",mysql_error())); # Ausf�hren
        $id     = @mysql_result($result,0,"id");                                                                                  # Ergebnis speichern
        mysql_free_result($result);

        //L�schen
        $query = "DELETE FROM ".$table_prefix."plugins WHERE id = '".$id."'";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin konnte nicht gel�scht werden",mysql_error())); # Ausf�hren
        
        $query = "DELETE FROM ".$table_prefix."settings WHERE plugin_id = '".$id."'";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Einstellungen konnten nicht gel�scht werden",mysql_error())); # Ausf�hren

        //Tabelle l�schen
        $query = "DROP TABLE ".$table_prefix."time";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Termin Tabelle konnte nicht gel�scht werden",mysql_error())); # Ausf�hren
        
        $query = "DROP TABLE ".$table_prefix."time_desc";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Termin Tabelle konnte nicht gel�scht werden",mysql_error())); # Ausf�hren

        return true;
    }
    else return false;
}
        
?>
