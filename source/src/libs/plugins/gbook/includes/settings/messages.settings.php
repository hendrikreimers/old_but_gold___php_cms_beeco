<?

/*
   BESCHREIBUNG:

   Enthält die Standard Meldungen
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zusätzliche Templates für simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* hinzufügen */

$messages["gbook"]["false"] = "<center><b>Bitte überprüfen Sie Ihre Eingabe.</b></center><br><br>";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["gbook"]["uninstall_true"] = "<b>Das Gästebuch wurde deinstalliert</b><br>".
                                       "Der Menüpunkt zu diesem Gästebuch muss von Ihnen<br>".
                                       "über die Hauptverwaltung gelöscht werden<br><br>".
                                       "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["gbook"]["uninstall_false"] = "<b>Das Gästebuch konnte nicht deinstalliert werden</b><br>".
                                        "Überprüfen Sie das eingegebene Passwort<br><br>".
                                        "Klicken Sie <a href=\"?action=uninstall&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["gbook"]["upd_true"]  = "<b>Die Änderungen wurden übernommen</b><br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["gbook"]["upd_false"] = "<b>Fehler!</b><br>".
                                  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=edit&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["gbook"]["updsets_true"]  = "<b>Die Einstellungen wurden übernommen</b><br><br>".
                               		  "Klicken Sie <a href=\"?action=settings&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["gbook"]["updsets_false"] = "<b>Fehler!</b><br>".
                                	  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                	  "Klicken Sie <a href=\"?action=settings&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
								  
/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["gbook"]["del_true"]  = "<b>Löschen des Eintrages erfolgreich</b><br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["gbook"]["del_false"] = "<b>Fehler!</b><br>".
                                  "Überprüfen Sie Ihre Eingaben.<br><br>".
                                  "Klicken Sie <a href=\"?action=delete&plugin=gbook&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>