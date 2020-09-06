<?

$directadd_code = '
	require(USER_PATH."/settings/plugins/news/other.settings.php");

    $dtpl = new template_class;
    $dtpl->path = USER_PATH."/templates/plugins/news/";
    $dtpl->load("directadd");

    if ( strtolower($params) == "last" )
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."news ORDER BY CONCAT(date,time) DESC LIMIT 0,1";
    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
			if ( $settings["display"]["rewrite"] == "0" ) {
    			$dtpl->insertVar("url","?init=more&amp;id=".$_GET["id"]."&amp;newsid=".@mysql_result($result,"0","id")."&amp;dad=news".(( $settings["base"]["template_override"] == "1" ) ? "&amp;style=".$_GET["style"] : ""));
			} else {
				$query  = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
				$iResult = mysql_query($query);
				$url = REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode(mysql_result($iResult,0,"title")))))."/n/n".@mysql_result($result,"0","id").".htm";

				$dtpl->insertVar("url",$url);

				unset($url);
				unset($query);
				mysql_free_result($iResult);
			}
    		
    		$dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));
    		$dtpl->insertVar("time",substr(@mysql_result($result,"0","time"),0,5));
    		$dtpl->insertVar("content",base64_decode(@mysql_result($result,"0","content")));
    		
            $dtpl->insertVar("path",$path);
    		$text = base64_decode(@mysql_result($result,"0","text"));
    		
    		$tmp = Explode(" ",( ($settings["news"]["preview_strip_tags"] == "1") ? strip_tags($text) : $text ));
				  	  
			for ( $i = 0; $i < $settings["news"]["max_words"]; $i++ )
			{
				$new_text .= $tmp[$i]." ";
			}
				  	  
			$text = $new_text;
			$dtpl->insertVar("text",( ($settings["news"]["preview_strip_tags"] == "1") ? strip_tags($text) : $text ));
				  	  
			unset($new_text);
			unset($tmp);

    		$done = ( @mysql_result($result,"0","text") ) ? 1 : 0; 
        }
        
        @mysql_free_result($result);
    }
    else if ( preg_match("/^last-[0-9]*$/i",$params) )
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."news ORDER BY CONCAT(date,time) DESC LIMIT ".(substr(($params),5,sizeof($params))-1).",1";
    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
			if ( $settings["display"]["rewrite"] == "0" ) {
    			$dtpl->insertVar("url","?init=more&amp;id=".$_GET["id"]."&amp;newsid=".@mysql_result($result,"0","id")."&amp;dad=news".(( $settings["base"]["template_override"] == "1" ) ? "&amp;style=".$_GET["style"] : ""));
			} else {
				$query  = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
				$iResult = @mysql_query($query);
				$url = REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode(mysql_result($iResult,0,"title")))))."/n/n".@mysql_result($result,"0","id").".htm";

				$dtpl->insertVar("url",$url);

				unset($url);
				unset($query);
				mysql_free_result($iResult);
			}

    		$dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));
    		$dtpl->insertVar("time",substr(@mysql_result($result,"0","time"),0,5));
    		$dtpl->insertVar("content",base64_decode(@mysql_result($result,"0","content")));
    		
    		$dtpl->insertVar("path",$path);
    		
    		$text = base64_decode(@mysql_result($result,"0","text"));

    		$tmp = Explode(" ",( ($settings["news"]["preview_strip_tags"] == "1") ? strip_tags($text) : $text ));

			for ( $i = 0; $i < $settings["news"]["max_words"]; $i++ )
			{
				$new_text .= $tmp[$i]." ";
			}
				  	  
			$text = $new_text;
			$dtpl->insertVar("text",( ($settings["news"]["preview_strip_tags"] == "1") ? strip_tags($text) : $text ));
				  	  
			unset($new_text);
			unset($tmp);
    		
    		$done = ( @mysql_result($result,"0","text") ) ? 1 : 0; 
        }
        
        @mysql_free_result($result);
    }
    else
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."news ORDER BY CONCAT(date,time) LIMIT ".($params-1).",1";
    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
			if ( $settings["display"]["rewrite"] == "0" ) {
    			$dtpl->insertVar("url","?init=more&amp;id=".$_GET["id"]."&amp;newsid=".@mysql_result($result,"0","id")."&amp;dad=news".(( $settings["base"]["template_override"] == "1" ) ? "&amp;style=".$_GET["style"] : ""));
			} else {
				$query  = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
				$iResult = mysql_query($query);
				$url = REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode(mysql_result($iResult,0,"title")))))."/n/n".@mysql_result($result,"0","id").".htm";

				$dtpl->insertVar("url",$url);

				unset($url);
				unset($query);
				mysql_free_result($iResult);
			}

    		$dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));
    		$dtpl->insertVar("time",substr(@mysql_result($result,"0","time"),0,5));
    		$dtpl->insertVar("content",base64_decode(@mysql_result($result,"0","content")));
    		
    		$dtpl->insertVar("path",$path);
    		
    		$text = base64_decode(@mysql_result($result,"0","text"));
    		
    		$tmp = Explode(" ",( ($settings["news"]["preview_strip_tags"] == "1") ? strip_tags($text) : $text ));
				  	  
			for ( $i = 0; $i < $settings["news"]["max_words"]; $i++ )
			{
				$new_text .= $tmp[$i]." ";
			}
				  	  
			$text = $new_text;
			$dtpl->insertVar("text",$text);
				  	  
			unset($new_text);
			unset($tmp);
    		
    		$done = ( @mysql_result($result,"0","text") ) ? 1 : 0; 
        }
        
        @mysql_free_result($result);
    }
    
    if ( $done === 1 )
    {
        $retval = $dtpl->getResult();
        $dtpl->clear();
        unset($dtpl);
    
		$retval = preg_replace("/^(.*)(\{.*\})(.*)$/siU","$1$3",$retval);
	
        return $retval;
    }

';

?>
