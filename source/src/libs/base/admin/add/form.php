<?

/*
   BESCHREIBUNG:

   Zeigt das passende Formular
   um Menüs hinzufügen zu kommen
   
   Gegebenfalls wird aus der Vorschau auch der Inhalt
   wieder eingefügt.

*/
function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	/*--- defaults --- start --- */

	//Standard Aktionen laden
	$tpl->path .= "add/";                                        # Template pfad (für den inhalt)
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten

	//Variablen definieren
	if ( !preg_match("=^([0-9]*)$=",$_GET['id']) ) { $_GET['id'] = "0"; }
	if ( !$_GET['id'] ) { $_GET['id'] = "0"; }
	
	/*--- defaults --- ende --- */



	/*--- back from preview check --- start ---*/

	if ( $_SESSION['title'] )
	{
		$_POST['title']    = base64_decode($_SESSION['title']);
		$_POST['text']     = stripslashes(base64_decode($_SESSION['text']));
		$_POST['parent']   = $_SESSION['parent'];
		$_POST['priority'] = $_SESSION['priority'];
		$_POST['type']     = $_SESSION['type'];

		unset($_SESSION['title']);
		unset($_SESSION['text']);
		unset($_SESSION['parent']);
		unset($_SESSION['priority']);
		unset($_SESSION['type']);
	}

	/*--- back from preview check --- ende ---*/



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");
	
	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);

	if ( $_POST['title'] )
	{
	    //Passendes Formular laden
	    $tpl->load(($_POST['type'] == "1") ? "form_text" : (($_POST['type'] == "2") ? "form_redirect" : (($_POST['type'] == "3") ? "form_manual" : "form_plugin") ),1);

	    //Pluginliste bei Bedarf anzeigen
	    if ( $_POST['type'] == "4" ) {
			$plugins = $sql->load_plugins();
	
			if ( strlen($plugins[0]["title"]) > 0 ) {
			    foreach ( $plugins as $plugin ) {
					$tpl->insertArray($plugin,array("plugin"));
			    }
			}
		} else {
			$tpl->insertVar("text",htmlspecialchars(stripslashes($_POST['text'])));
		}

		//Standard Informationen einsetzen
	    $tpl->insertVar("path",$path);
	    $tpl->insertVar("SID",$SID);
	    $tpl->insertVar("title",base64_encode($_POST['title']));
	    $tpl->insertVar("parent",$_POST['parent']);
	    $tpl->insertVar("parent",$parent);
	    $tpl->insertVar("priority",$_POST['priority']);
	    $tpl->insertVar("type",$_POST['type']);
    
	    //Text einbauen
		$tpl->insertVar("content",$_POST['text']);

	    //Ergebnis
	    $text = $tpl->getResult();
	}
	else
	{
		//Fehler Ausgabe
		$text = message(BASE_PATH,"add","notitle",$SID);
	}

	$ground_tpl->insertVar("text",$text);
	$ground_tpl->insertVar("SID",$SID);
	$ground_tpl->insertVar("isplugin","no");

	//Alles ausgeben
	$retVal = $ground_tpl->getResult();

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();

	return $retVal;

	/*--- abschluss --- ende --- */
}

?>