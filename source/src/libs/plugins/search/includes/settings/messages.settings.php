<?

/*
   BESCHREIBUNG:

   Enth�lt die Standard Meldungen f�r den Admin Bereich.
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zus�tzliche Templates f�r simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["search"]["uninstall_true"] = "<b>Die Suche wurde deinstalliert</b><br>".
                                         "Der Men�punkt zu dieser Suche  muss von Ihnen<br>".
                                         "�ber die Hauptverwaltung gel�scht werden<br><br>".
                                         "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["search"]["uninstall_false"] = "<b>Die Suche konnte nicht deinstalliert werden</b><br>".
                                          "�berpr�fen Sie das eingegebene Passwort<br><br>".
                                          "Klicken Sie <a href=\"?action=uninstall&plugin=search&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>