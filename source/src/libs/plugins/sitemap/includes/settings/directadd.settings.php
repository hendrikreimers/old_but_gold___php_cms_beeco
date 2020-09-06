<? 

$directadd_code = '

	function loadMenueItems ($settings,$parent = 0,$retval = array())
	{
		$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."items WHERE parent_id = \'".$parent."\' AND is_active = \'1\' AND is_visible = \'1\' ORDER BY priority";
		$result = @mysql_query($query) or die(mysql_error());

		if ( mysql_num_rows($result) > 0 )
		{
			while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
			{
				$retval[$row["priority"]][] = $row;
			}
			
			foreach ( $retval as $priority )
			{
				$cmp = create_function(\'$a, $b\',\'return strcmp(base64_decode($a["title"]), base64_decode($b["fruit"]));\');
				usort($priority,$cmp);
				unset($cmp);
				
				foreach ( $priority as $items )
				{
					$tmp = loadMenueItems($settings,$items["id"]);
					$items["sub"] = $tmp;
					$res[] = $items;
				}
			}
		}

		mysql_free_result($result);

		return $res;
	}
	
	function createSitemap ($arMap,$settings,$retval = "")
	{
		$retval .= "<ul class=\"sitemap\">\r\n";
		
		if ( sizeof($arMap) > 0 )
		{
			foreach ( $arMap as $item )
			{
				$query  = "SELECT id,redirect FROM ".$settings["mysql"]["table_prefix"]."redirects WHERE item_id = \'".$item["id"]."\' AND is_manual = \'1\'";
				$result = @mysql_query($query) or die(mysql_error());

				if ( @mysql_result($result,0,"id") > 0 ) {
					$url = base64_decode(@mysql_result($result,0,"redirect"));
				} else {
					if ( $settings["display"]["rewrite"] == "0" ) {
						$url = "?id=".$item["id"];
					} else {
						$url = REL_PATH."/";
						$url .= strtolower(umlRewEncode(trim(base64_decode($item["title"])))).".html";
					}
				}

				mysql_free_result($result);
				
				$retval .= "<li class=\"sitemap\"><a href=\"".$url."\">".base64_decode($item["title"])."</a>";
				
				if ( $item["sub"][0]["id"] > 0 ) {
					$retval .= "\r\n";
				   	$retval = createSitemap($item["sub"],$settings,$retval);
           		}

				$retval .= "</li>\r\n";
			}
    	}

    	    $retval .= "</ul>\r\n";
    	    return $retval;
	}
    
    $arMap  = loadMenueItems($settings);
    $result = createSitemap($arMap,$settings);
    return $result;
';

?>