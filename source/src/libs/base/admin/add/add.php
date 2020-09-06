<?

/*
   BESCHREIBUNG:

   Fügt den Menüpunkt hinzu.
   Gegebenfalls wird auch die Vorschau angezeigt

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
	$tpl->path = USER_PATH."/templates/base/";                                        # Template pfad (für den inhalt)
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten
	
	if ( !preg_match("/^[0-9]*$/i",$_GET['priority']) ) { $_GET['priority'] = "0"; }
	if ( !preg_match("/^[0-9]*$/i",$_GET['parent']) ) { $_GET['parent'] = "0"; }
	
	/*--- defaults --- ende --- */
	


	/*--- Ausgabe --- start --- */
	
	//Templates laden
	$ground_tpl->load("ground");

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);
	
	//Fehler Ausgabe (wird bei erfolg überschrieben
	$text = message(BASE_PATH,"add","false",$SID);

	if ( $_POST['redirect'] )
	{
		$sql->add_redirect($_POST['parent'],$_POST['title'],base64_encode($_POST['redirect']),$_POST['priority']);
		$text = message(BASE_PATH,"add","true",$SID);
	
		//Alles ausgeben
		$ground_tpl->insertVar("text",$text);
		$retVal = $ground_tpl->getResult();
	}

	if ( $_POST['manual'] )
	{
		$sql->add_manual($_POST['parent'],$_POST['title'],base64_encode($_POST['manual']),$_POST['priority']);
		$text = message(BASE_PATH,"add","true",$SID);
			
		//Alles ausgeben
		$ground_tpl->insertVar("text",$text);
		$retVal = $ground_tpl->getResult();
	}

	if ( $_POST['pluginID'] )
	{
		$sql->add_plugin($_POST['pluginID'],$_POST['parent'],$_POST['title'],$_POST['priority']);
		$text = message(BASE_PATH,"add","true",$SID);
		
		//Alles ausgeben
		$ground_tpl->insertVar("text",$text);
		$retVal = $ground_tpl->getResult();
	}

	if ( $_POST['text'] )
	{
		//Kontrolle ob gespeichert weden soll oder eine vorschau
		if ( $_POST['pressed'] )
		{
		    $sql->add_content($_POST['parent'],$_POST['title'],base64_encode($_POST['text']),$_POST['priority']);
		    $text = message(BASE_PATH,"add","true",$SID);
		    
			//Alles ausgeben
			$ground_tpl->insertVar("text",$text);
			$retVal = $ground_tpl->getResult();
		}
		else
		{
		    //Vorschau ausgeben
			require(BASE_PATH."/includes/functions/text.functions.php");
	
			//Template laden und auftrennen
			$tpl->path = USER_PATH."/templates/base/";
    		$tpl->load("index",1);
	    
		    //Das Menue in das Template laden
	        $menues = $sql->load_rek_menues($sql->get_default_id());               # Alle Menüeinträge und Position lade
			if ( $menues )
			{
				$refTpl = &$tpl;
			    insert_menue($settings,$refTpl,$menues["items"],$menues["position"],"1"); # Alles in das Template
				unset($refTpl);
			}

			//Backbutton und Text einsetzen
			$tpl->insertVar("path",REL_PATH."/user");
			$content = stripslashes(auto_nl2br($_POST['text'],$settings["base"]["nl2br"],base64_decode($settings["base"]["tagfilter"])));	

		    //Ausgabe
		    $tpl->insertVar("text",directadd($content,LIB_PATH,$settings["base"]["nl2br"],$settings["base"]["tagfilter"],$settings));

			//Ausgabe und Back Button einfügen
			$preview_link = '<div style="position: fixed; top: 0px; left: 0px; width:100%; margin: auto; display: block; background-color: #FF0000; padding: 10px;"><span style="text-align: left; color: #FFFFFF; font-weight: bold; margin: auto;"><a href="?action=add&amp;init=form&amp;SID='.$SID.'" style="text-decoration: none; color: #FFFFFF;">VORSCHAU MODUS&nbsp;|&nbsp;Zur&uuml;ck</a></span></div>';	
		    echo insertAfterBody($tpl->getResult(),$preview_link);
			unset($preview_link);

			//Speicher freigeben
			$tpl->clear();
		
			//Daten in der Session zwischenspeichern
			$_SESSION['title']    = $_POST['title'];
		    $_SESSION['text']     = base64_encode($_POST['text']);
		    $_SESSION['parent']   = $_POST['parent'];
		    $_SESSION['priority'] = $_POST['priority'];
		    $_SESSION['type']     = $_POST['type'];
		}
	}

	if ( (!$_POST['redirect']) && (!$_POST['text']) )
	{
		$ground_tpl->insertVar("text",$text);
		$retVal = $ground_tpl->getResult();
	}

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */
	
	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>