<?

/*
   BESCHREIBUNG:

   Enthält die Standard Meldungen
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zusätzliche Templates für simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* hinzufügen */

$messages["pics"]["false"] = "<b>Sie müssen einen Titel eingeben</b><br><br>".
                             "Klicken Sie <a href=\"?action=add&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["true"]  = "<b>Eintrag wurde hinzugefügt</b><br><br>".
                             "Klicken Sie <a href=\"?action=add&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["picfalse"] = "<b>Sie haben kein(e) Bild(er) angeben.</b><br><br>".
                                "Bedenken Sie dass nur Jpeg (JPG) Bilder akzeptiert werden<br><br>".
                                "Klicken Sie <a href=\"?action=add&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["pictrue"]  = "<b>Eintrag wurde hinzugefügt</b><br><br>".
                                "Klicken Sie <a href=\"?action=add&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";


/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["pics"]["uninstall_true"] = "<b>Die Galerien wurden deinstalliert</b><br>".
                                       "Der Menüpunkt zu diesem Plugin muss von Ihnen<br>".
                                       "über die Hauptverwaltung gelöscht werden<br><br>".
                                       "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["uninstall_false"] = "<b>Die Galerien konnten nicht deinstalliert werden</b><br>".
                                        "Überprüfen Sie das eingegebene Passwort<br><br>".
                                        "Klicken Sie <a href=\"?action=main&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["pics"]["upd_true"]  = "<b>Die Einstellungen wurden übernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["upd_false"] = "<b>Fehler!</b><br>".
                                    "Überprüfen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=settings&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["picupd_true"]  = "<b>Die Änderungen wurden übernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=edit&init=listgroup&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["picupd_false"] = "<b>Fehler!</b><br>".
                                    "Überprüfen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=edit&init=listgroup&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
                                    
$messages["pics"]["grpupd_true"]  = "<b>Die Änderungen wurden übernommen</b><br><br>".
                                    "Klicken Sie <a href=\"?action=edit&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["grpupd_false"] = "<b>Fehler!</b><br>".
                                    "Überprüfen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=edit&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
									
$messages["pics"]["attrupd_true"]  = "<b>Die Änderungen wurden übernommen</b><br><br>".
                                    "Klicken Sie <a href=\"?action=attributes&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["attrupd_false"] = "<b>Fehler!</b><br>".
                                    "Überprüfen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=attributes&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Gruppen */

$messages["pics"]["delgrp_true"]  = "<b>Löschen der Gruppe erfolgreich</b><br><br>".
                                    "Klicken Sie <a href=\"?action=delete&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["delgrp_false"] = "<b>Fehler!</b><br>".
                                    "Überprüfen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=delete&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
                                    
$messages["pics"]["delpic_true"]  = "<b>Löschen des Bildes erfolgreich</b><br><br>".
                                    "Klicken Sie <a href=\"?action=delete&init=listgroup&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["delpic_false"] = "<b>Fehler!</b><br>".
                                    "Überprüfen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=delete&init=listgroup&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>
