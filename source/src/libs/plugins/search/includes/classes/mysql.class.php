<?

class search_mysql_class extends mysql_class
{
	function search_base($regexp,$searchType,$settings)
	{
		//Anzahl der Suchwörter
		if ( $searchType == "2" )
		{
			$wordCount = sizeof(Explode("|",$regexp));
		}
		
		//Überprüfen ob das Plugin installiert ist
		if ( strlen($settings["base"]["name"]) <= 0 ) {
			return;
		}
		
		//Alle Menütitel laden
		$query  = "SELECT id,title FROM ".$this->prefix."items WHERE is_visible = '1' AND is_active = '1'";
		$result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Men&uuml; laden",mysql_error()));
		
		while ( $row = @mysql_fetch_assoc($result) ) 
		{
			//Suche
			preg_match_all($regexp,strip_tags(base64_decode($this->load_content($row["id"]))),$matches["text"]); # Text
			preg_match_all($regexp,base64_decode($row["title"]),$matches["title"]); # Menütitel
			$sResult = array_unique(array_merge($matches["title"][0],$matches["text"][0]));

			//Kontrolle ob es ein Suchergebnis ist
			if ( sizeof($sResult) > 0 ) 
			{
				//Testen ob Weiterleitung
				if ( $this->redirect_check($row["id"]) ) 
				{	
					//Weiterleitung laden
    				$redirect = base64_decode($this->load_redirect($row['id']));

    				///Kontrolle ob manueller link
    				$query = "SELECT is_manual FROM ".$this->prefix."redirects WHERE item_id = '".$row["id"]."'";
					$result2 = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Redirect Manual Check",mysql_error()));

					if ( @mysql_result($result2,0,"is_manual") == "1" )
					{
						$url = $redirect;
					}
					else if ( $settings["display"]["rewrite"] == "1" ) {
						$url = REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode($row["title"])))).".html";
					} else $url = "?id=".$row["id"];
					
					$text = "";
					@mysql_free_result($result2);
					
				} 
				else
				{
					if ( $settings["display"]["rewrite"] == "1" ) {
						$url = REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode($row["title"])))).".html";
					} else $url  = "?id=".$row["id"];
					$text = $this->load_content($row["id"]); 
				}
				
				//Abschließende Überprüfung je nach Suchkriterium
				if ( $searchType == "2" ) {
					$saveResult = ( sizeof($sResult) == $wordCount ) ? "true" : "false";
				} else $saveResult = "true";

				if ( $saveResult == "true" )
				{				
					//Ergebnis speichern
					$retval[] = array("id"    => $row["id"],
					                  "title" => $row["title"],
					                  "text"  => $text,
									  "url"   => $url);
				}	
			}
		}
		
		@mysql_free_result($result);
		return $retval;
	}
	
	function search_news($regexp,$searchType,$settings)
	{
		//Anzahl der Suchwörter
		if ( $searchType == "2" )
		{
			$wordCount = sizeof(Explode("|",$regexp));
		}
		
		//Überprüfen ob das Plugin installiert ist
		if ( strlen($settings["news"]["name"]) <= 0 ) {
			return;
		}
		
		$query  = "SELECT * FROM ".$this->prefix."news ORDER BY CONCAT('date','time') DESC";
		$result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Daten laden",mysql_error()));
		
		while ( $row = @mysql_fetch_assoc($result) ) 
		{
			preg_match_all($regexp,base64_decode($row["title"]),$matches["title"]);
			preg_match_all($regexp,strip_tags(base64_decode($row["text"])),$matches["text"]);
			$sResult = array_unique(array_merge($matches["title"][0],$matches["text"][0]));
		
			//Kontrolle ob es ein Suchergebnis ist
			if ( sizeof($sResult) > 0 ) 
			{
				if ( $settings["display"]["rewrite"] == "1" ) {
					$iData = $this->load_item_data($_GET['id']);
					$iTitle = $iData["title"];
					unset($iData);

					$url = REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode($iTitle))))."/n/n".$row["id"].".htm";

					unset($iTitle);
				} else $url  = "?init=more&amp;dad=news&amp;id=".$_GET['id']."&amp;newsid=".$row["id"];
				$text = $row["text"]; 
				
				//Abschließende Überprüfung je nach Suchkriterium
				if ( $searchType == "2" ) {
					$saveResult = ( sizeof($sResult) == $wordCount ) ? "true" : "false";
				} else $saveResult = "true";
				
				if ( $saveResult == "true" )
				{
					//Ergebnis speichern
					$retval[] = array("id"    => $row["id"],
					                  "title" => $row["title"],
					                  "text"  => $text,
									  "url"   => $url);
				}	
			}
		}
		
		@mysql_free_result($result);
		return $retval;
	}
	
	function search_time($regexp,$searchType,$settings)
	{
		//Anzahl der Suchwörter
		if ( $searchType == "2" )
		{
			$wordCount = sizeof(Explode("|",$regexp));
		}
		
		//Überprüfen ob das Plugin installiert ist
		if ( strlen($settings["time"]["name"]) <= 0 ) {
			return;
		}

		$query  = "SELECT time.id AS id,time.date AS date,time.title AS title,descr.text AS text FROM ".$this->prefix."time AS time LEFT JOIN ".$this->prefix."time_desc AS descr ON descr.id = time.desc_id ORDER BY date DESC";
		$result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Daten laden",mysql_error()));

		while ( $row = @mysql_fetch_assoc($result) ) 
		{
			preg_match_all($regexp,base64_decode($row["title"]),$matches["title"]);
			preg_match_all($regexp,strip_tags(base64_decode($row["text"])),$matches["text"]);
			$sResult = array_unique(array_merge($matches["title"][0],$matches["text"][0]));
		
			//Kontrolle ob es ein Suchergebnis ist
			if ( sizeof($sResult) > 0 ) 
			{
				if ( $settings["display"]["rewrite"] == "1" ) {
					$iData = $this->load_item_data($_GET['id']);
					$iTitle = $iData["title"];
					unset($iData);

					$url = REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode($iTitle))))."/t/t".$row["id"].".htm";

					unset($iTitle);
				} else $url  = ( strlen($row["text"]) > 0 ) ? "?init=more&amp;dad=time&amp;id=".$_GET['id']."&amp;timeid=".$row["id"] : "#";
				$text = $row["text"]; 
				
				//Abschließende Überprüfung je nach Suchkriterium
				if ( $searchType == "2" ) {
					$saveResult = ( sizeof($sResult) == $wordCount ) ? "true" : "false";
				} else $saveResult = "true";
				
				if ( $saveResult == "true" )
				{
					//Ergebnis speichern
					$retval[] = array("id"    => $row["id"],
					                  "title" => $row["title"],
					                  "text"  => $text,
									  "url"   => $url);
				}
			}
		}
		
		@mysql_free_result($result);
		return $retval;
	}
	
	function search_picgroups($regexp,$searchType,$settings)
	{
		//Anzahl der Suchwörter
		if ( $searchType == "2" )
		{
			$wordCount = sizeof(Explode("|",$regexp));
		}
		
		//Überprüfen ob das Plugin installiert ist
		if ( strlen($settings["pics"]["name"]) <= 0 ) {
			return;
		}
		
		$query  = "SELECT id,title,`desc` AS text FROM ".$this->prefix."pics_groups WHERE mode = '0'";
		$result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Daten laden",mysql_error()));

		while ( $row = @mysql_fetch_assoc($result) ) 
		{
			preg_match_all($regexp,base64_decode($row["title"]),$matches["title"]);
			preg_match_all($regexp,strip_tags(base64_decode($row["text"])),$matches["text"]);
			$sResult = array_unique(array_merge($matches["title"][0],$matches["text"][0]));
		
			//Kontrolle ob es ein Suchergebnis ist
			if ( sizeof($sResult) > 0 ) 
			{
				if ( $settings["display"]["rewrite"] == "1" ) {
					$iData = $this->load_item_data($_GET['id']);
					$iTitle = $iData["title"];
					unset($iData);

					$url = REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode($iTitle))))."/p/group".$row["id"].".htm";

					unset($iTitle);
				} else $url  = "?init=listgroup&amp;dad=pics&amp;id=".$_GET['id']."&amp;group=".$row["id"];
				$text = $row["text"]; 
				
				//Abschließende Überprüfung je nach Suchkriterium
				if ( $searchType == "2" ) {
					$saveResult = ( sizeof($sResult) == $wordCount ) ? "true" : "false";
				} else $saveResult = "true";
				
				if ( $saveResult == "true" )
				{
					//Ergebnis speichern
					$retval[] = array("id"    => $row["id"],
					                  "title" => $row["title"],
					                  "text"  => $text,
									  "url"   => $url);
				}
			}
		}
		
		@mysql_free_result($result);
		return $retval;
	}	
}

?>