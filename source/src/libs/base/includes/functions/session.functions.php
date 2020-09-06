<?

/*
   BESCHREIBUNG:
   
   Verwaltet Sessions und kontrolliert diese auf Ihre Gültigkeit
*/

//Öffnet eine bestehende oder neue Session
function session_open($save_path,$SID = "")
{
    session_save_path($save_path);
    session_name("SID");
    if ( $SID ) { session_id($SID); }
    session_start();

    return session_id();
}

//Überprüft eine Session auf Ihre Gültigkeit
function session_check($settings)
{
    //Passwort laden
    $db_pass = base64_decode($settings["base"]["pass"]);

    //Aktuelle SID
    $SID = session_id();

    //Session Timeout checken und SID gleicheit mit DB
    if ( $SID <> $settings['base']['loginSID'] )
    {
        return "2";
    }
    else
    {
        if ( ((time()-$settings['base']['time']) < $settings['login']['timeout']) )
        {
            //Benutzer und Kennwort überprüfen (+IP und HOST)
            if ( (strtoupper($_SESSION['user']) == strtoupper(base64_decode($settings["base"]["user"]))) && ($_SESSION['pass'] == $db_pass) && ($_SESSION['user_ip'] == $_SERVER['REMOTE_ADDR']) && ($_SESSION['user_host'] == gethostbyaddr($_SERVER['REMOTE_ADDR'])) )
            {
                return "1";
            }
            else return "0";
        }
        else return "0";
    }
}

//Beendet eine Session
function Session_Close()
{
    session_destroy();
}

?>
