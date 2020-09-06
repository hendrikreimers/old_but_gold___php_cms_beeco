<?

/*
   BESCHREIBUNG:

   Enth�lt die Standard Meldungen f�r den Admin Bereich.
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zus�tzliche Templates f�r simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Mail versand */

//Statusmeldungen extra einbinden, damit es bei jeder Seite alternativ sein kann
require(USER_PATH."/settings/plugins/kontakt/status.settings.php");

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Einstellungen */

$messages["kontakt"]["upd_true"] = "<b>Ihre Einstellungen wurden �bernommen</b><br><br>".
                                   "Klicken Sie <a href=\"?action=settings&plugin=kontakt&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
                                   
$messages["kontakt"]["upd_false"] = "<b>Sie m�ssen einen E-Mail Empf�nger eintragen</b><br><br>".
                                    "Klicken Sie <a href=\"?action=settings&plugin=kontakt&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["kontakt"]["uninstall_true"] = "<b>Das Kontaktformular wurde deinstalliert</b><br>".
                                         "Der Men�punkt zu diesem Kontaktformular muss von Ihnen<br>".
                                         "�ber die Hauptverwaltung gel�scht werden<br><br>".
                                         "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["kontakt"]["uninstall_false"] = "<b>Das Kontakt-Formular konnte nicht deinstalliert werden</b><br>".
                                          "�berpr�fen Sie das eingegebene Passwort<br><br>".
                                          "Klicken Sie <a href=\"?action=uninstall&plugin=kontakt&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>