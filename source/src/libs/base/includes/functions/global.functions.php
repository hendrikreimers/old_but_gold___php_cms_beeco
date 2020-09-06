<?

/*
   BESCHREIBUNG:
   
   Allgemein benötigte Funktionen
*/

//Gibt SQL Fehlermeldungen aus
function _sqlerror($file,$line,$message,$error)
{
    echo "<font face=\"verdana\" size=\"2\">\n".
         "<b>FEHLER !!!</b><br><br>\n".
         $message."<br><br>\n".
         "<b>Datei:</b>&nbsp;".$file."<br>\n".
         "<b>Zeile:</b>&nbsp;".$line."<br><br>\n".
         "<b>SQL-Meldung:</b><br>".$error.
         "</font>";
}

//Gibt normale Fehlermeldungen aus
function _error($file,$line,$message)
{
    echo "<font face=\"verdana\" size=\"2\">\n".
         "<b>FEHLER !!!</b><br><br>\n".
         $message."<br><br>\n".
         "<b>Datei:</b>&nbsp;".$file."<br>\n".
         "<b>Zeile:</b>&nbsp;".$line."<br><br>\n".
         "</font>";
}

//Verschlüsselt das Passwort mit der MD5 Checksumme aus der DB
function crypt_password($password,$md5)
{
    $crypted = md5($password.$md5);
    return $crypted;
}

//Gibt Info Meldungen aus
function message($path,$msg_group,$msg_type,$SID = "")
{
    require ($path."/includes/settings/messages.settings.php");
    return str_replace("%SID%",$SID,$messages[$msg_group][$msg_type]);
}

?>