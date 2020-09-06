<?

/*
   BESCHREIBUNG:

   Enth�lt die Standard Meldungen
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zus�tzliche Templates f�r simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* hinzuf�gen */

$messages["pics"]["false"] = "<b>Sie m�ssen einen Titel eingeben</b><br><br>".
                             "Klicken Sie <a href=\"?action=add&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["true"]  = "<b>Eintrag wurde hinzugef�gt</b><br><br>".
                             "Klicken Sie <a href=\"?action=add&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["picfalse"] = "<b>Sie haben kein(e) Bild(er) angeben.</b><br><br>".
                                "Bedenken Sie dass nur Jpeg (JPG) Bilder akzeptiert werden<br><br>".
                                "Klicken Sie <a href=\"?action=add&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["pictrue"]  = "<b>Eintrag wurde hinzugef�gt</b><br><br>".
                                "Klicken Sie <a href=\"?action=add&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";


/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["pics"]["uninstall_true"] = "<b>Die Galerien wurden deinstalliert</b><br>".
                                       "Der Men�punkt zu diesem Plugin muss von Ihnen<br>".
                                       "�ber die Hauptverwaltung gel�scht werden<br><br>".
                                       "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["uninstall_false"] = "<b>Die Galerien konnten nicht deinstalliert werden</b><br>".
                                        "�berpr�fen Sie das eingegebene Passwort<br><br>".
                                        "Klicken Sie <a href=\"?action=main&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["pics"]["upd_true"]  = "<b>Die Einstellungen wurden �bernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=settings&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["upd_false"] = "<b>Fehler!</b><br>".
                                    "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=settings&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["picupd_true"]  = "<b>Die �nderungen wurden �bernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=edit&init=listgroup&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["picupd_false"] = "<b>Fehler!</b><br>".
                                    "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=edit&init=listgroup&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
                                    
$messages["pics"]["grpupd_true"]  = "<b>Die �nderungen wurden �bernommen</b><br><br>".
                                    "Klicken Sie <a href=\"?action=edit&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["grpupd_false"] = "<b>Fehler!</b><br>".
                                    "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=edit&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
									
$messages["pics"]["attrupd_true"]  = "<b>Die �nderungen wurden �bernommen</b><br><br>".
                                    "Klicken Sie <a href=\"?action=attributes&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["attrupd_false"] = "<b>Fehler!</b><br>".
                                    "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=attributes&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Gruppen */

$messages["pics"]["delgrp_true"]  = "<b>L�schen der Gruppe erfolgreich</b><br><br>".
                                    "Klicken Sie <a href=\"?action=delete&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["delgrp_false"] = "<b>Fehler!</b><br>".
                                    "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=delete&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
                                    
$messages["pics"]["delpic_true"]  = "<b>L�schen des Bildes erfolgreich</b><br><br>".
                                    "Klicken Sie <a href=\"?action=delete&init=listgroup&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["pics"]["delpic_false"] = "<b>Fehler!</b><br>".
                                    "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                    "Klicken Sie <a href=\"?action=delete&init=listgroup&plugin=pics&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>
