<?

//Wie der Name schon sagt, gefunden auf PHP.net
function xml2array($xml)
{
   $xmlary = array ();

   if ((strlen ($xml) < 256) && is_file ($xml))
     $xml = file_get_contents ($xml);
 
   $ReElements = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*?)<(\/\s*\1\s*)>)/s';
   $ReAttributes = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';
 
   preg_match_all ($ReElements, $xml, $elements);
   foreach ($elements[1] as $ie => $xx) {
   $xmlary[$xx]["name"] = $elements[1][$ie];
     if ( $attributes = trim($elements[2][$ie])) {
         preg_match_all ($ReAttributes, $attributes, $att);
         foreach ($att[1] as $ia => $yy)
           // all the attributes for current element are added here
           $xmlary[$xx]["attributes"][$att[1][$ia]] = $att[2][$ia];
     } // if $attributes
    
     // get text if it's combined with sub elements
   $cdend = strpos($elements[3][$ie],"<");
   if ($cdend > 0) {
           $xmlary[$xx]["text"] = substr($elements[3][$ie],0,$cdend -1);
       } // if cdend
      
     if (preg_match ($ReElements, $elements[3][$ie])){       
         $xmlary[$xx]["elements"] = xml2array ($elements[3][$ie]);
         }
     else if (isset($elements[3][$ie])){
         $xmlary[$xx]["text"] = $elements[3][$ie];
         }
     $xmlary[$xx]["closetag"] = $elements[4][$ie];
   }//foreach ?
   return $xmlary;
}

//Löscht die Inhalte der Tabellen (bis auf Kontakt da es verschwindet mit Base)
function clear_database($xml,$sql,$settings)
{
	//Base löschen
	if ( $xml["backup"]["elements"]["base"]["attributes"]["name"] == "base" )
	{
		//Tabellen leeren
		$query = "DELETE FROM ".$sql->prefix."contents";
		mysql_query($query);
		$query = "DELETE FROM ".$sql->prefix."items";
		mysql_query($query);
		$query = "DELETE FROM ".$sql->prefix."plugins";
		mysql_query($query);
		$query = "DELETE FROM ".$sql->prefix."redirects";
		mysql_query($query);
		$query = "DELETE FROM ".$sql->prefix."settings";
		mysql_query($query);
		
		//Auto Increment zurücksetzen
		$query = "ALTER TABLE `".$sql->prefix."contents` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."items` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."plugins` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."redirects` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."settings` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
	}
	
	//Tabellen leeren wenn plugin installiert, ansonsten tabellen löschen
	if ( ($xml["backup"]["elements"]["plugin_gbook"]["attributes"]["installed"] == "1") && ($settings["gbook"]["name"] == "gbook") )
	{
		$query = "DELETE FROM ".$sql->prefix."gbook";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."gbook` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
	}
	else
	{
		$query = "DROP TABLE IF EXISTS `".$sql->prefix."gbook`";
		mysql_query($query);
	}
	
	//Tabellen leeren wenn plugin installiert, ansonsten tabellen löschen
	if ( ($xml["backup"]["elements"]["plugin_news"]["attributes"]["installed"] == "1") && ($settings["news"]["name"] == "news") )
	{
		$query = "DELETE FROM ".$sql->prefix."news";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."news` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
	}
	else
	{
		$query = "DROP TABLE IF EXISTS `".$sql->prefix."news`";
		mysql_query($query);
	}
	
	//Tabellen leeren wenn plugin installiert, ansonsten tabellen löschen
	if ( ($xml["backup"]["elements"]["plugin_time"]["attributes"]["installed"] == "1") && ($settings["time"]["name"] == "time") )
	{
		$query = "DELETE FROM ".$sql->prefix."time";
		mysql_query($query);
		$query = "DELETE FROM ".$sql->prefix."time_desc";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."time` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."time_desc` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
	}
	else
	{
		$query = "DROP TABLE IF EXISTS `".$sql->prefix."time`";
		mysql_query($query);
		$query = "DROP TABLE IF EXISTS `".$sql->prefix."time_desc`";
		mysql_query($query);
	}

	//Tabellen leeren wenn plugin installiert, ansonsten tabellen löschen
	if ( ($xml["backup"]["elements"]["plugin_pics"]["attributes"]["installed"] == "1") && ($settings["pics"]["name"] == "pics") )
	{
		//Bildpfad herausfinden
		require(USER_PATH."/settings/plugins/pics/other.settings.php");
	
		$query = "DELETE FROM ".$sql->prefix."pics_items";
		mysql_query($query);
		$query = "DELETE FROM ".$sql->prefix."pics_groups";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."pics_items` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
		$query = "ALTER TABLE `".$sql->prefix."pics_groups` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =0";
		mysql_query($query);
		
		//Alle nicht benötigten Bilder löschen
		foreach ( $xml["backup"]["elements"]["plugin_pics"]["elements"]["items"]["elements"] as $item )
		{
			$IDs[$item["elements"]["id"]["text"]] = $item["elements"]["id"]["text"];
		}
		
		$pdir = dir($settings["pics"]["img_path"]);
		
		while ( $entry = $pdir->read() )
		{
			if ( ($entry != ".") && ($entry != "..") )
			{
				if ( preg_match("/([0-9]*)s\.jpg/siU",$entry,$result) )
				{				
					if ( strlen($IDs[$result[1]]) == 0 )
					{
						@unlink($settings["pics"]["img_path"]."/".$result[1]."s.jpg");
						@unlink($settings["pics"]["img_path"]."/".$result[1]."b.jpg");
					}
				}
			}
		}
	}
	else
	{
		$query = "DROP TABLE IF EXISTS `".$sql->prefix."pics_items`";
		mysql_query($query);
		$query = "DROP TABLE IF EXISTS `".$sql->prefix."pics_groups`";
		mysql_query($query);
		
		if ( is_dir($settings["pics"]["img_path"]) )
		{ 
			//Bilder löschen im Verzeichnis
			$pdir = dir($settings["pics"]["img_path"]);
		
			while ( $entry = $pdir->read() )
			{
				if ( ($entry != ".") && ($entry != "..") && (preg_match("/(.*)\.jpg/siU",$entry)) )
				{
					@unlink($settings["pics"]["img_path"]."/".$entry);
				}
			}
		}
	}
}

function imp2settings ($plugin,$settings,$sql)
{
	//Plugin Informationen speichern
	$query = "INSERT INTO ".$sql->prefix."plugins VALUES ('".$plugin["id"]."','".$plugin["name"]."','".$plugin["title"]."')";
	mysql_query($query);
	
	//einstellungen hinzufügen
	foreach ( $settings as $setting )
	{
		$query = "INSERT INTO ".$sql->prefix."settings VALUES ('','".$plugin["id"]."','".$setting["name"]."','".$setting["text"]."')";
		mysql_query($query);
	}
}

function imp2base ($xml,$sql)
{
	//Menüpunkte importieren
    foreach ( $xml as $item )
    {
       	$id         = $item["elements"]["id"]["text"];
		$plugin_id  = $item["elements"]["plugin_id"]["text"];
    	$parent_id  = $item["elements"]["parent"]["text"];
    	$priority   = $item["elements"]["priority"]["text"];
    	$is_visible = $item["elements"]["visible"]["text"];
    	$is_active  = $item["elements"]["active"]["text"];
    	$title      = $item["elements"]["title"]["text"];

		//Für alte Datensätze die plugin_id prüfen
		$plugin_id = ( strlen($plugin_id) > 0 ) ? $plugin_id : "0";

    	$query = "INSERT INTO ".$sql->prefix."items VALUES ('".$id."','".$plugin_id."','".$parent_id."','".$priority."','".$is_visible."','".$is_active."','".$title."')";
    	mysql_query($query);
    	
    	//Menüpunkt oder Weiterleitung hinzufügen
    	if ( strlen($item["elements"]["redirect"]["text"]) > 0 )
    	{
    		$query = "INSERT INTO ".$sql->prefix."redirects VALUES ('','".$id."','".$item["elements"]["redirect"]["text"]."','".$item["elements"]["manual"]["text"]."')";
    		mysql_query($query);
    	}
    	else
    	{
    		$query = "INSERT INTO ".$sql->prefix."contents VALUES ('','".$id."','".$item["elements"]["content"]["text"]."')";
    		mysql_query($query);
    	}
    }
}

function imp2gbook ($xml,$sql)
{
    foreach ( $xml as $item )
    {
		$id       = $item["elements"]["id"]["text"];
		$ip       = $item["elements"]["ip"]["text"];
		$host     = $item["elements"]["host"]["text"];
		$date     = $item["elements"]["date"]["text"];
		$time     = $item["elements"]["time"]["text"];
		$name     = $item["elements"]["name"]["text"];
		$email    = $item["elements"]["email"]["text"];
		$icq      = $item["elements"]["icq"]["text"];
		$homepage = $item["elements"]["homepage"]["text"];
		$text     = $item["elements"]["text"]["text"];
		$comment  = $item["elements"]["comment"]["text"];
		
		$query = "INSERT INTO ".$sql->prefix."gbook VALUES ('".$id."','".$ip."','".$host."','".$date."','".$time."','".$name."','".$email."','".$icq."','".$homepage."','".$text."','".$comment."')";	
    	mysql_query($query);
	}
}

function imp2news ($xml,$sql)
{
    foreach ( $xml as $item )
    {
		$id    = $item["elements"]["id"]["text"];
		$date  = $item["elements"]["date"]["text"];
		$time  = $item["elements"]["time"]["text"];
		$title = $item["elements"]["title"]["text"];
		$text  = $item["elements"]["text"]["text"];
		
		$query = "INSERT INTO ".$sql->prefix."news VALUES ('".$id."','".$date."','".$time."','".$title."','".$text."')";	
    	mysql_query($query);
	}
}

function imp2time ($xml,$sql)
{
    foreach ( $xml as $item )
    {
		$id      = $item["elements"]["id"]["text"];
		$date    = $item["elements"]["date"]["text"];
		$title   = $item["elements"]["title"]["text"];
		$desc_id = $item["elements"]["descid"]["text"];
		$desc    = $item["elements"]["desc"]["text"];
		
		$query = "INSERT INTO ".$sql->prefix."time VALUES ('".$id."','".$date."','".$title."','".$desc_id."')";	
    	mysql_query($query);
    	
    	if ( $desc_id > 0 )
    	{
    		$query = "INSERT INTO ".$sql->prefix."time_desc VALUES ('".$desc_id."','".$desc."')";
    		mysql_query($query);
    	}
	}
}

function imp2pics ($groups,$items,$sql)
{
    foreach ( $groups as $item )
    {
		$id    = $item["elements"]["id"]["text"];
		$title = $item["elements"]["title"]["text"];
		$mode  = $item["elements"]["mode"]["text"];
		$desc  = $item["elements"]["desc"]["text"];
		
		$query = "INSERT INTO ".$sql->prefix."pics_groups VALUES ('".$id."','".$mode."','".$title."','".$desc."')";	
    	mysql_query($query);
	}
	
	foreach ( $items as $item )
    {
		$id      = $item["elements"]["id"]["text"];
		$groupid = $item["elements"]["groupid"]["text"];
		$title   = $item["elements"]["title"]["text"];
		$desc    = $item["elements"]["desc"]["text"];
		
		$query = "INSERT INTO ".$sql->prefix."pics_items VALUES ('".$id."','".$groupid."','".$title."','".$desc."')";	
    	mysql_query($query);
	}
}

function importXML($file,$sql,$settings) {
    //Datei in Speicher laden und löschen
	$fp = fopen($file,"r");
	unset($xml_file);
	
	while ( $line = fgets($fp,1024) )
	{
	    $xml_file .= $line;
	}
	
	fclose($fp);
	unlink($file);
	
	//Decodieren
	$xml = xml2array(base64_decode($xml_file));

	//Check ob es CMS Backup ist
	if ( $xml["backup"]["attributes"]["name"] == "CMS_SE_BACKUP" )
	{
	    //Alles aufräumen (leeren)
	    clear_database($xml,$sql,$settings);
	    
	    //Backup einspielen
	    if ( $xml["backup"]["elements"]["base"]["name"] == "base" ) {
	    	imp2settings ($xml["backup"]["elements"]["base"]["attributes"],$xml["backup"]["elements"]["base"]["elements"]["settings"]["elements"],$sql);
	    	imp2base ($xml["backup"]["elements"]["base"]["elements"]["data"]["elements"],$sql);
	    }
	    
	    if ( ($xml["backup"]["elements"]["plugin_kontakt"]["attributes"]["installed"] == "1") && ($settings["kontakt"]["name"] == "kontakt") ) {
	    	imp2settings ($xml["backup"]["elements"]["plugin_kontakt"]["attributes"],$xml["backup"]["elements"]["plugin_kontakt"]["elements"]["settings"]["elements"],$sql);
	    }
	    
	    if ( ($xml["backup"]["elements"]["plugin_gbook"]["attributes"]["installed"] == "1") && ($settings["gbook"]["name"] == "gbook") ) {
	    	imp2settings ($xml["backup"]["elements"]["plugin_gbook"]["attributes"],$xml["backup"]["elements"]["plugin_gbook"]["elements"]["settings"]["elements"],$sql);
	    	imp2gbook ($xml["backup"]["elements"]["plugin_gbook"]["elements"]["data"]["elements"],$sql);
	    }
	    
	    if ( ($xml["backup"]["elements"]["plugin_news"]["attributes"]["installed"] == "1") && ($settings["news"]["name"] == "news") ) {
	    	imp2settings ($xml["backup"]["elements"]["plugin_news"]["attributes"],$xml["backup"]["elements"]["plugin_news"]["elements"]["settings"]["elements"],$sql);
	    	imp2news ($xml["backup"]["elements"]["plugin_news"]["elements"]["data"]["elements"],$sql);
	    }
	    
	    if ( ($xml["backup"]["elements"]["plugin_time"]["attributes"]["installed"] == "1") && ($settings["time"]["name"] == "time") ) {
	    	imp2settings ($xml["backup"]["elements"]["plugin_time"]["attributes"],$xml["backup"]["elements"]["plugin_time"]["elements"]["settings"]["elements"],$sql);
	    	imp2time ($xml["backup"]["elements"]["plugin_time"]["elements"]["data"]["elements"],$sql);
	    }

	    if ( ($xml["backup"]["elements"]["plugin_pics"]["attributes"]["installed"] == "1" ) && ($settings["pics"]["name"] == "pics") ) {
	    	imp2settings ($xml["backup"]["elements"]["plugin_pics"]["attributes"],$xml["backup"]["elements"]["plugin_pics"]["elements"]["settings"]["elements"],$sql);
	    	imp2pics ($xml["backup"]["elements"]["plugin_pics"]["elements"]["groups"]["elements"],$xml["backup"]["elements"]["plugin_pics"]["elements"]["items"]["elements"],$sql);
	    }
		
		return true;
	}
	else
	{
	    return false;
	}
}

?>