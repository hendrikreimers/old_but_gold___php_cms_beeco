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

    $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Passwort konnte nicht abgefragt werden",mysql_error())); # Ausführen
    $pass   = Explode("|",base64_decode(@mysql_result($result,0,"pass")));                                                                                  # Ergebnis speichern
    mysql_free_result($result);

    //Bei korrektem Passwort deinstallation beginnen
    if ( $password == base64_encode($pass[0]) )
    {
        //Löschen
        $query = "DELETE FROM ".$table_prefix."plugins WHERE name = 'backup'";
        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin konnte nicht gelöscht werden",mysql_error())); # Ausführen

        return true;
    }
    else return false;
}
        
?>
