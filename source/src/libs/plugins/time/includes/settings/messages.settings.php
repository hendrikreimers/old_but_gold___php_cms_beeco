<?

/*
   BESCHREIBUNG:

   Enth�lt die Standard Meldungen
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zus�tzliche Templates f�r simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* hinzuf�gen */

$messages["time"]["false"] = "<center><b>Titel nicht ausgef�llt!</b></center><br><br>";

$messages["time"]["true"] = "<center><b>Eintrag wurde hinzugef�gt</b></center><br><br>";


/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["time"]["uninstall_true"] = "<b>Der Terminkalender wurde deinstalliert</b><br>".
                                       "Der Men�punkt zu den Terminen muss von Ihnen<br>".
                                       "�ber die Hauptverwaltung gel�scht werden<br><br>".
                                       "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["time"]["uninstall_false"] = "<b>Der Terminkalender konnten nicht deinstalliert werden</b><br>".
                                        "�berpr�fen Sie das eingegebene Passwort<br><br>".
                                        "Klicken Sie <a href=\"?action=uninstall&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["time"]["upd_true"]  = "<b>Die Einstellungen wurden �bernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["time"]["upd_false"] = "<b>Fehler!</b><br>".
                                  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Bearbeiten */

$messages["time"]["edit_true"]  = "<b>Die �nderungen wurden �bernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["time"]["edit_false"] = "<b>Fehler!</b><br>".
                                  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["time"]["del_true"]  = "<b>L�schen des Eintrages erfolgreich</b><br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["time"]["del_false"] = "<b>Fehler!</b><br>".
                                  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=time&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>