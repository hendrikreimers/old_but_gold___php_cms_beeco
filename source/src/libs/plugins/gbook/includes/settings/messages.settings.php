<?

/*
   BESCHREIBUNG:

   Enth�lt die Standard Meldungen
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zus�tzliche Templates f�r simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* hinzuf�gen */

$messages["gbook"]["false"] = "<center><b>Bitte �berpr�fen Sie Ihre Eingabe.</b></center><br><br>";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["gbook"]["uninstall_true"] = "<b>Das G�stebuch wurde deinstalliert</b><br>".
                                       "Der Men�punkt zu diesem G�stebuch muss von Ihnen<br>".
                                       "�ber die Hauptverwaltung gel�scht werden<br><br>".
                                       "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["gbook"]["uninstall_false"] = "<b>Das G�stebuch konnte nicht deinstalliert werden</b><br>".
                                        "�berpr�fen Sie das eingegebene Passwort<br><br>".
                                        "Klicken Sie <a href=\"?action=uninstall&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["gbook"]["upd_true"]  = "<b>Die �nderungen wurden �bernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["gbook"]["upd_false"] = "<b>Fehler!</b><br>".
                                  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["gbook"]["updsets_true"]  = "<b>Die Einstellungen wurden �bernommen</b><br><br>".
                               		  "Klicken Sie <a href=\"?action=settings&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["gbook"]["updsets_false"] = "<b>Fehler!</b><br>".
                                	  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                	  "Klicken Sie <a href=\"?action=settings&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
								  
/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["gbook"]["del_true"]  = "<b>L�schen des Eintrages erfolgreich</b><br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["gbook"]["del_false"] = "<b>Fehler!</b><br>".
                                  "�berpr�fen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>