<?

/*
   BESCHREIBUNG:

   Enthält die Standard Meldungen für den Admin Bereich.
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zusätzliche Templates für simple Meldungen.

*/

/* Status */

$messages["backup"]["true"] = "<b>Import erfolgreich!</b><br><br>".
                              "Klicken Sie <a href=\"?action=main&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["backup"]["false"] = "<b>Import schlug fehl!</b><br><br>".
                               "Klicken Sie <a href=\"?plugin=backup&action=import&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

$messages["backup"]["uninstall_true"] = "<b>Deinstallation erfolgreich!</b><br><br>".
                              			"Klicken Sie <a href=\"?action=pluginlist&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

$messages["backup"]["uninstall_false"] = "<b>Deinstallation schlug fehl!</b><br><br>".
                               			 "Klicken Sie <a href=\"?plugin=backup&action=uninstall&SID=%SID%\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/


?>