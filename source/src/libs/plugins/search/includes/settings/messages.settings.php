<?

/*
   BESCHREIBUNG:

   Enthält die Standard Meldungen für den Admin Bereich.
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zusätzliche Templates für simple Meldungen.

*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallieren */

$messages["search"]["uninstall_true"] = "<b>Die Suche wurde deinstalliert</b><br>".
                                         "Der Menüpunkt zu dieser Suche  muss von Ihnen<br>".
                                         "über die Hauptverwaltung gelöscht werden<br><br>".
                                         "Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["search"]["uninstall_false"] = "<b>Die Suche konnte nicht deinstalliert werden</b><br>".
                                          "Überprüfen Sie das eingegebene Passwort<br><br>".
                                          "Klicken Sie <a href=\"?action=uninstall&plugin=search&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>