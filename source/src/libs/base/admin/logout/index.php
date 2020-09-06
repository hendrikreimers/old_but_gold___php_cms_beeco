<?

/*
   BESCHREIBUNG:

   Beendet die aktuelle Sitzung und optimiert
   die Tabellen. Anschließend wird der Benutzer zurück
   zum Login geleitet

*/

function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	//Unnötigen Speicher freigeben
	unset($tpl);

	//Benötigte Dateien einbinden
	require(USER_PATH."/settings/base/mysql.settings.php");
	require(USER_PATH."/settings/base/other.settings.php");

	$sql->unregister_SID($SID);

	$sql->optimize($settings["mysql"]["db"]);
	$sql->close();

	//Session beenden
	session_open($settings["tmp_dir"],$SID);
	Session_Close();

	//Startseite
	header("Location: ./");
	die();
}

?>
