<?

/*
   BESCHREIBUNG:

   Enthält die Standard Meldungen
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zusätzliche Templates für simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* hinzufügen */

$messages["time"]["false"] = "<center><b>Titel nicht ausgefüllt!</b></center><br><br>";

$messages["time"]["true"] = "<center><b>Eintrag wurde hinzugefügt</b></center><br><br>";


/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["time"]["uninstall_true"] = "<b>Der Terminkalender wurde deinstalliert</b><br>".
                                       "Der Menüpunkt zu den Terminen muss von Ihnen<br>".
                                       "über die Hauptverwaltung gelöscht werden<br><br>".
                                       "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["time"]["uninstall_false"] = "<b>Der Terminkalender konnten nicht deinstalliert werden</b><br>".
                                        "Überprüfen Sie das eingegebene Passwort<br><br>".
                                        "Klicken Sie <a href=\"?action=uninstall&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["time"]["upd_true"]  = "<b>Die Einstellungen wurden übernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["time"]["upd_false"] = "<b>Fehler!</b><br>".
                                  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Bearbeiten */

$messages["time"]["edit_true"]  = "<b>Die Änderungen wurden übernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["time"]["edit_false"] = "<b>Fehler!</b><br>".
                                  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["time"]["del_true"]  = "<b>Löschen des Eintrages erfolgreich</b><br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["time"]["del_false"] = "<b>Fehler!</b><br>".
                                  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>