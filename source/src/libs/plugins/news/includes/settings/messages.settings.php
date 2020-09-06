<?

/*
   BESCHREIBUNG:

   Enth�lt die Standard Meldungen
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zus�tzliche Templates f�r simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* hinzuf�gen */

$messages["news"]["false"] = "<center><b>Sie m�ssen alle Felder ausf�llen</b></center><br><br>";

$messages["news"]["true"] = "<center><b>Eintrag wurde hinzugef�gt</b></center><br><br>";


/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["news"]["uninstall_true"] = "<b>Die News wurden deinstalliert</b><br>".
                                       "Der Men�punkt zu diesem G�stebuch muss von Ihnen<br>".
                                       "�ber die Hauptverwaltung gel�scht werden<br><br>".
                                       "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["news"]["uninstall_false"] = "<b>Die News konnten nicht deinstalliert werden</b><br>".
                                        "�berpr�fen Sie das eingegebene Passwort<br><br>".
                                        "Klicken Sie <a href=\"?action=uninstall&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["news"]["upd_true"]  = "<b>Die Einstellungen wurden �bernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["news"]["upd_false"] = "<b>Fehler!</b><br>".
                                  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["news"]["edit_true"]  = "<b>Die �nderungen wurden �bernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["news"]["edit_false"] = "<b>Fehler!</b><br>".
                                  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["news"]["del_true"]  = "<b>L�schen des Eintrages erfolgreich</b><br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["news"]["del_false"] = "<b>Fehler!</b><br>".
                                  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=news&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>