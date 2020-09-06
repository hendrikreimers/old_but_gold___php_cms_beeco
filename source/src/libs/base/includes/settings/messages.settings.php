<?

/*
   BESCHREIBUNG:
   
   Enthält die Standard Meldungen für den Admin Bereich.
   Diese Meldungen werden auf Ereignisse in das Grund Template eingesetzt.
   Das spart zusätzliche Templates für simple Meldungen.
   
*/

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Konfigurationsmenü */

    //Alles erledigt
    $messages["settings"]["true"] = "<b>Ihre Änderungen wurden übernommen!</b><br>".
                                    "Sollten Sie den Benutzernamen und/oder das Kennwort ge&auml;ndert haben,<br>".
                                    "müssen Sie sich neu Anmelden.<br><br>".
                                    "Klicken Sie <a href=\"?SID=%SID%&action=settings\"><b>hier</b></a> um fortzufahren!";

    //Konfigurationsmenü - Leere Felder
    $messages["settings"]["empty"] = "<b>Fehler!</b><br><br>".
                                     "Es dürfen keine leeren Felder vorhanden sein, ausgenommen die Passwort Felder.<br>".
                                     "Diese nur bei Passwort änderungen ausfüllen!<br><br>".
                                     "Klicken Sie <a href=\"?SID=%SID%&action=settings\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

    //Kennwort Mindestlänge
    $messages["settings"]["minpwsize"] = "<b>Fehler!</b><br><br>".
                                         "Die eingegebenen Passwörter sind ungültig!<br>".
                                         "Die Mindestlänge von Passwörtern beträgt 4 Zeichen.<br>".
                                         "<br>".
                                         "Klicken Sie <a href=\"?SID=%SID%&action=settings\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

     //Ungültiges Kennwort
     $messages["settings"]["wrongpw"] = "<b>Fehler!</b><br><br>".
                                        "Die eingegebenen Passwörter sind ungültig!<br>".
                                        "Dies kann durch eine ungültige Eingabe des alten Passwortes<br>".
                                        "oder durch fehlerhafte Eingabe des neuen Passwortes geschehen.<br><br>".
                                        "Klicken Sie <a href=\"?SID=%SID%&action=settings\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Login */

    //Kein JavaScript aktiviert
    $messages["login"]["nojava"]  = "Für die Administration wird JavaScript benötigt.<br>".
                                    "Bitte aktivieren Sie JavaScript in Ihrem Browser.<br><br>".
                                    "Klicken Sie <a href=\"../index.html\"><b>hier</b></a>,<br>".
                                    "wenn Sie JavaScript aktiviert haben";

    //Login fehlgeschlagen
    $messages["login"]["false"]  = "<b>Anmeldung fehlgeschlagen!</b><br><br>".
                                   "Klicken Sie <a href=\"index.php\"><b>hier</b></a> um es erneut zu versuchen.";
                                  
    //Login fehlgeschlagen
    $messages["login"]["logged"] = "<b>Anmeldung fehlgeschlagen!</b><br>".
                                   "Es ist noch ein Benutzer angemeldet.<br>".
                                   "Sollte dies nicht der Fall sein, wiederholen Sie bitte den Vorgang<br>".
                                   "in einigen Minuten erneut.<br><br>".
                                   "Klicken Sie <a href=\"index.php\"><b>hier</b></a> um zurück zu gelangen.";

    //Login fehlgeschlagen
    $messages["login"]["unlogged"] = "<b>Anmeldung fehlgeschlagen!</b><br>".
                                     "Ein Benutzer war zu lange inaktiv und wurde abgemeldet.<br>".
                                     "Sie können sich nun wieder wie gewohnt fortfahren<br><br>".
                                     "Klicken Sie <a href=\"index.php?SID=%SID%&action=main\"><b>hier</b></a> um weiter zu gelangen.";
                                 
/*------------------------------------------------------------------------------------------------------------------------------------*/
/* DeInstallation */

    //Erfolgreich
    $messages["uninstall"]["true"] = "<b>DeInstallation erfolgreich abgeschlossen</b></br></br>".
                                     "Sollten Sie Beeco erneut installieren wollen,<br>".
                                     "klicken Sie bitte <a href=\"../install/\">hier</a>";
                                     
    //Fehler gemacht...
    $messages["uninstall"]["false"] = "<b>Fehler!</b><br><br>".
                                      "Achten Sie auf die korrekte Schreibweise Ihres Passwortes<br>".
                                      "und klicken Sie auf \"Ja\".<br><br>".
                                      "Klicken Sie <a href=\"?SID=%SID%&action=main\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

    //Fehler gemacht...
    $messages["uninstall"]["fplug"] = "<b>Fehler!</b><br><br>".
                                      "Es sind noch Plugins installiert.<br>".
                                      "Deinstallieren Sie die Plugins, bevor Sie Beeco deinstallieren<br><br>".
                                      "Klicken Sie <a href=\"?SID=%SID%&action=main\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Hinzufügen */

	//Kein Titel
	$messages["add"]["false"] = "<b>Kein Inhalt</b><br><br>".
								"Sie müssen einen Inhalt definieren um einen<br>".
								"neuen Menüpunkt hinzufügen zu können.<br><br>".
								"Klicken Sie <a href=\"?SID=%SID%&action=add\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

	//OK
	$messages["add"]["true"] = "<b>Hinzuf&uuml;gen erfolgreich!</b><br><br>".
							   "Ihr Menüpunkt wurde erfolgreich hinzugef&uuml;gt<br><br>".
							   "Klicken Sie <a href=\"?SID=%SID%&action=add\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
							   
    //Kein Titel
	$messages["add"]["notitle"] = "<b>Kein Titel</b><br><br>".
							      "Sie müssen einen Titel eingeben um einen<br>".
							      "Menüpunkt hinzufügen zu können.<br><br>".
							      "Klicken Sie <a href=\"?SID=%SID%&action=add\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Löschen */

	//OK
	$messages["delete"]["true"] = "<b>Löschen erfolgreich!</b><br><br>".
							      "Ihr Menüpunkt wurde erfolgreich gel&ouml;scht<br><br>".
							      "Klicken Sie <a href=\"?SID=%SID%&action=delete\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
							
    //Fehler
	$messages["delete"]["false"] = "<b>Fehler</b><br><br>".
							       "Ihr Men&uuml;punkt konnte aufgrund ung&uuml;ltiger ID<br>".
							       "nicht gel&ouml;scht werden.<br><br>".
							       "Klicken Sie <a href=\"?SID=%SID%&action=delete\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Bearbeiten */

	//OK
	$messages["edit"]["true"] = "<b>Bearbeiten erfolgreich!</b><br><br>".
							      "Ihr Menüpunkt wurde erfolgreich bearbeitet<br><br>".
							      "Klicken Sie <a href=\"?SID=%SID%&action=edit\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
							
    //Fehler
	$messages["edit"]["false"] = "<b>Fehler</b><br><br>".
							     "Ihr Men&uuml;punkt konnte nicht bearbeitet werden<br>".
							     "M&ouml;glicherweise wurden nicht alle Felder ausgef&uuml;llt<br><br>".
							     "Klicken Sie <a href=\"?SID=%SID%&action=edit\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Bearbeiten */

	//OK
	$messages["attributes"]["true"] = "<b>&Uuml;bernehmen erfolgreich!</b><br><br>".
							          "Die Eigenschaften wurden ge&auml;ndert<br><br>".
							          "Klicken Sie <a href=\"?SID=%SID%&action=attributes\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

    //Fehler
	$messages["attributes"]["false"] = "<b>Fehler</b><br><br>".
							           "Die Einstellungen konnten nicht übernommen werden,<br>".
							           "da keine Men&uuml;punkte verfügbar sind.<br><br>".
							           "Klicken Sie <a href=\"?SID=%SID%&action=attributes\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";
							     
/*------------------------------------------------------------------------------------------------------------------------------------*/
/* Bearbeiten */

	//OK
	$messages["move"]["cut_true"] = "<b>Ausschneiden erfolgreich!</b><br><br>".
							         "Der Menüpunkt wurde ausgeschnitten.<br>".
							         "Sie können ihn jetzt an einer anderen Stelle einfügen<br><br>".
							         "Klicken Sie <a href=\"?SID=%SID%&action=move\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

    //Fehler
	$messages["move"]["cut_false"] = "<b>Fehler</b><br><br>".
							         "Ausschneiden schlug fehl.<br>".
							         "Wahrscheinlich aufgrund einer ungültigen Eingabe<br><br>".
							         "Klicken Sie <a href=\"?SID=%SID%&action=move\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

    //OK
	$messages["move"]["cancel_true"] = "<b>Abbruch erfolgt!</b><br><br>".
							           "Der Menüpunkt sitzt wieder an seiner alten Position<br>".
							           "<br>".
							           "Klicken Sie <a href=\"?SID=%SID%&action=move\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

    //Fehler
	$messages["move"]["cancel_false"] = "<b>Abbruch nicht erfolgt!</b><br><br>".
							            "Es wurde kein Menüpunkt ausgeschnitten, der<br>".
							            "an seine alte Position wieder gesetzt werden kann<br><br>".
							            "Klicken Sie <a href=\"?SID=%SID%&action=move\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

    //Fehler
	$messages["move"]["insert_true"] = "<b>Einfügen erfolgt</b><br><br>".
							            "Der Menüpunkt sitzt nun an seiner neuen Position<br>".
							            "<br>".
							            "Klicken Sie <a href=\"?SID=%SID%&action=move\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

    //Fehler
	$messages["move"]["insert_false"] = "<b>Fehler</b><br><br>".
							            "Einfügen schlug fehl.<br>".
							            "Wahrscheinlich aufgrund einer ungültigen Eingabe<br><br>".
							            "Klicken Sie <a href=\"?SID=%SID%&action=move\"><b>hier</b></a> um zur&uuml;ck zu gelangen!";

/*------------------------------------------------------------------------------------------------------------------------------------*/

?>
