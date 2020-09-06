<?

/*
   BESCHREIBUNG:

   Zeigt das passende Formular
   um Menüs bearbeiten zu kommen
   
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
	$tpl->path .= "edit/";                                        # Template pfad (für den inhalt)
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten
	
	//Variablen definieren
	if ( !preg_match("=^([0-9]*)$=",$_GET['id']) ) { header("Location: index.php?SID=".$SID); }

	/*--- defaults --- ende --- */



	/*--- back from preview check --- start ---*/

	if ( $_SESSION['title'] )
	{
		$item["title"]    = $_SESSION['title'];
		$item["text"]     = base64_decode($_SESSION['text']);
		$item["priority"] = $_SESSION['priority'];
		$item["type"]     = $_SESSION['type'];
		$_GET["id"]       = $_SESSION['id'];

		unset($_SESSION['title']);
		unset($_SESSION['text']);
		unset($_SESSION['priority']);
		unset($_SESSION['type']);
		unset($_SESSION['id']);
	}
	
	/*--- back from preview check --- ende ---*/
	


	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);

	if ( $_GET['id'] ) {
	    $item = ( !$item ) ? $sql->load_item_data($_GET['id']) : $item;

	    if ( $sql->redirect_check($_GET['id']) )
	    {
	        $type = "2";
	    }
	    else if ( $sql->plugin_check($_GET['id']) ) {
			$type = "4";
	    } else $type = "1";

	    //Passendes Formular laden
	    $tpl->load(($type == "1") ? "form_text" : (($type == "4") ? "form_plugin" : "form_redirect"),1);
    
	    //Plugins laden
	    if ( $type == "4" ) {
			$plugins   = $sql->load_plugins();            # Pluginliste laden
			$curPlugin = $sql->plugin_check($_GET['id']); # Aktuelle Plugin ID
	
			//Einfgen
			if ( sizeof($plugins) > 0 ) {
			    foreach ( $plugins as $plugin ) {
					$plugin["selected"] = ( $plugin["id"] == $curPlugin ) ? " selected" : "";
					$tpl->insertArray($plugin,array("plugin"));
			    }
			}
    	}
    
    	//Passende Werte einfügen
	    if ( $type == "1" ) {
	        $txtval = (!$item["text"]) ? stripslashes(base64_decode($sql->load_content($_GET['id'],"1"))) : stripslashes($item["text"]);
	    }
	
	    if ( $type == "2" ) {
			$tpl->insertVar("redirect",stripslashes(base64_decode($sql->load_redirect($_GET['id'],"1"))));
		}
    
    	//Standard Informationen einsetzen
	    $tpl->insertVar("path",$path);
	    $tpl->insertVar("SID",$SID);
	    $tpl->insertVar("id",$_GET['id']);
	    $tpl->insertVar("title",base64_decode($item['title']));
	    $tpl->insertVar("priority",$item['priority']);
	    $tpl->insertVar("type",$type);
    
	    //Text einbauen
		$tpl->insertVar("content",$txtval);	

	    //Ergebnis
	    $text = $tpl->getResult();
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