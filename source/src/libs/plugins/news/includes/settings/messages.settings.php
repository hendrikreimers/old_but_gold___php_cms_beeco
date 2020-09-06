<?

/*
   BESCHREIBUNG:

   Enthält die Standard Meldungen
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zusätzliche Templates für simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* hinzufügen */

$messages["news"]["false"] = "<center><b>Sie müssen alle Felder ausfüllen</b></center><br><br>";

$messages["news"]["true"] = "<center><b>Eintrag wurde hinzugefügt</b></center><br><br>";


/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["news"]["uninstall_true"] = "<b>Die News wurden deinstalliert</b><br>".
                                       "Der Menüpunkt zu diesem Gästebuch muss von Ihnen<br>".
                                       "über die Hauptverwaltung gelöscht werden<br><br>".
                                       "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["news"]["uninstall_false"] = "<b>Die News konnten nicht deinstalliert werden</b><br>".
                                        "Überprüfen Sie das eingegebene Passwort<br><br>".
                                        "Klicken Sie <a href=\"?action=uninstall&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["news"]["upd_true"]  = "<b>Die Einstellungen wurden übernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["news"]["upd_false"] = "<b>Fehler!</b><br>".
                                  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["news"]["edit_true"]  = "<b>Die Änderungen wurden übernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["news"]["edit_false"] = "<b>Fehler!</b><br>".
                                  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["news"]["del_true"]  = "<b>Löschen des Eintrages erfolgreich</b><br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["news"]["del_false"] = "<b>Fehler!</b><br>".
                                  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>