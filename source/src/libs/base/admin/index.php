<?

/* BESCHREIBUNG:
   Initiert den gewünschten Bereich
*/

function init()
{
	//Aktion festlegen und anschließend prüfen
	$action = ( $_POST['action'] ) ? $_POST['action'] : $_GET['action'];
	$action = ( strlen($action) > 0 ) ? $action : "login";

	//Welches Script soll initiert werden
	$init = ( $_POST['init'] ) ? $_POST['init'] : $_GET['init'];
	$init = ( strlen($init) > 0 ) ? $init : "index";

	//Soll ein Plugin aufgerufen werden?
	$plugin = ( $_POST['plugin'] ) ? $_POST['plugin'] : $_GET['plugin'];
	$plugin = ( strlen($plugin) > 0 ) ? $plugin : "";

	//Sicherheitscheck
	if ( (!preg_match("=^([a-z]*)$=siU",$action)) || (!preg_match("=^([a-z_]*)$=siU",$init)) || (!preg_match("=^([a-z]*)$=siU",$plugin)) ) {
		die("ERROR: Wrong Parameters");
	}

	//Aktion ausführen
	if ( $plugin == "" ) {
		if ( ($action != "login") && ($action != "install") ) {
			//Admin Bereich initalisieren
			require(BASE_PATH."/includes/actions/adminarea.actions.php");
			$dObj = init_admin();
		}

		//Login anzeigen
		if ( strlen($init) > 0 ) {
			if ( file_exists(BASE_PATH."/admin/".$action."/".$init.".php") ) {
				require(BASE_PATH."/admin/".$action."/".$init.".php");
				$content = initialize($dObj);
			}
		}
	} else {
		//Admin Bereich initalisieren
		require(BASE_PATH."/includes/actions/adminarea.actions.php");
		$dObj = init_admin();

		//Login anzeigen
		if ( strlen($init) > 0 ) {
			if ( file_exists(LIB_PATH."/plugins/".$plugin."/admin/".$action."/".$init.".php") ) {
				define("PLUGIN_PATH",LIB_PATH."/plugins/".$plugin);
				require(LIB_PATH."/plugins/".$plugin."/admin/".$action."/".$init.".php");
				$content = initialize($dObj);
			}
		}
	}
	
	// Ausgabe
	echo $content;
}

?>