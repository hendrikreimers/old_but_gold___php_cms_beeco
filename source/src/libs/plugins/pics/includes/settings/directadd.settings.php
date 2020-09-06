<?

$directadd_code = '
    
    require(USER_PATH."/settings/plugins/pics/other.settings.php");
    $imgpath = $settings["pics"]["img_path"];

    $dtpl = new template_class;
    $dtpl->path = USER_PATH."/templates/plugins/pics/";

    if ( preg_match("/^(big|small),([0-9]*)(,short|)$/i",$params,$matches) )
    {
    	$dtpl->load((($matches[3] == ",short") ? "directadd_short" : ( ($settings["pics"]["view"] == "1") ? "directadd_popup" : ( ($settings["pics"]["view"] == "2") ? "directadd_ajax" : "directadd") )));
    	
    	$query  = "SELECT items.id    AS id,
                          items.title AS title,
                          items.desc  AS `desc`
                   FROM ".$settings["mysql"]["table_prefix"]."pics_items AS items
                   INNER JOIN ".$settings["mysql"]["table_prefix"]."pics_groups AS groups
                           ON groups.id = items.group_id
                   WHERE items.id = \'".$matches[2]."\' AND groups.mode < \'2\'";
                   
    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
    	    $id   = @mysql_result($result,0,"id");
    	    $size = ((strtolower($matches[1]) == "big") ? "b" : "s");
    	    
    		if ( file_exists($imgpath."/".$id.$size.".jpg") )
    		{
    		    $dtpl->insertVar("path",USER_PATH);

				if ( $settings["display"]["rewrite"] == "1" )
				{
					$iQuery  = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
					$iResult = mysql_query($iQuery);
					$iTitle  = strtolower(umlRewEncode(trim(base64_decode(@mysql_result($iResult,0,"title")))));

					$dtpl->insertVar("img_url",REL_PATH."/".$iTitle."/p/".(($size == "s") ? "small" : "big").$id.".jpg");
					$dtpl->insertVar("url",REL_PATH."/".$iTitle."/p/details".$id.".html");
					
					if ( $settings["pics"]["view"] == 2 ) {
						$dtpl->insertVar("ajaxUrl",REL_PATH."/".$iTitle."/p/ajaxDetails".$id.".html");
					}

					unset($iQuery);
					unset($iTitle);
					mysql_free_result($iResult);
				}
				else
				{
					$dtpl->insertVar("img_url","?init=loadimg&amp;dad=pics&amp;id=".$_GET["id"]."&amp;pid=".$id."&amp;size=".$size);
					$dtpl->insertVar("url","?init=details&amp;dad=pics&amp;style=".$_GET["style"]."&amp;id=".$_GET["id"]."&amp;pic=".$id);
					
					if ( $settings["pics"]["view"] == 2 ) {
						$dtpl->insertVar("ajaxUrl","?init=details&amp;ajax=true&amp;dad=pics&amp;style=".$_GET["style"]."&amp;id=".$_GET["id"]."&amp;pic=".$id);
					}
				}
                	
    		    $dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		    $dtpl->insertVar("desc",auto_nl2br(base64_decode(@mysql_result($result,"0","desc")),$nl2br_modus,$tagfilter));
    		    
    		    $done = 1;
    		}
        }
        
        @mysql_free_result($result);
    }
    else if ( preg_match("/^(big|small)(,rand)(,short|)$/i",$params,$matches) )
    {
    	$dtpl->load((($matches[3] == ",short") ? "directadd_short" : ( ($settings["pics"]["view"] == "1") ? "directadd_popup" : ( ($settings["pics"]["view"] == "2") ? "directadd_ajax" : "directadd") )));
    	
    	$query  = "SELECT items.id    AS id,
                          items.title AS title,
                          items.desc  AS `desc`
                   FROM ".$settings["mysql"]["table_prefix"]."pics_items AS items
                   INNER JOIN ".$settings["mysql"]["table_prefix"]."pics_groups AS groups
                           ON groups.id = items.group_id
                   WHERE groups.mode = \'0\' ORDER BY RAND()";

    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
    	    $id   = @mysql_result($result,0,"id");
    	    $size = ((strtolower($matches[1]) == "big") ? "b" : "s");
    	
    	    if ( file_exists($imgpath."/".$id.$size.".jpg") )
    		{
    		    $dtpl->insertVar("path",USER_PATH);

				if ( $settings["display"]["rewrite"] == "1" )
				{
					$iQuery  = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
					$iResult = mysql_query($iQuery);
					$iTitle  = strtolower(umlRewEncode(trim(base64_decode(@mysql_result($iResult,0,"title")))));

					$dtpl->insertVar("img_url",REL_PATH."/".$iTitle."/p/".(($size == "s") ? "small" : "big").$id.".jpg");
					$dtpl->insertVar("url",REL_PATH."/".$iTitle."/p/details".$id.".html");
					
					if ( $settings["pics"]["view"] == 2 ) {
						$dtpl->insertVar("ajaxUrl",REL_PATH."/".$iTitle."/p/ajaxDetails".$id.".html");
					}

					unset($iQuery);
					unset($iTitle);
					mysql_free_result($iResult);
				}
				else
				{
					$dtpl->insertVar("img_url","?init=loadimg&amp;dad=pics&amp;id=".$_GET["id"]."&amp;pid=".$id."&amp;size=".$size);
					$dtpl->insertVar("url","?init=details&amp;dad=pics&amp;style=".$_GET["style"]."&amp;id=".$_GET["id"]."&amp;pic=".$id);
					
					if ( $settings["pics"]["view"] == 2 ) {
						$dtpl->insertVar("ajaxUrl","?init=details&amp;ajax=true&amp;dad=pics&amp;style=".$_GET["style"]."&amp;id=".$_GET["id"]."&amp;pic=".$id);
					}
				}

    		    $dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		    $dtpl->insertVar("desc",auto_nl2br(base64_decode(@mysql_result($result,"0","desc")),$nl2br_modus,$tagfilter));
    		    
    		    $done = 1;
    		}
        }

        @mysql_free_result($result);
    }
    else if ( preg_match("/^(big|small)(,last)(,short|)$/i",$params,$matches) )
    {
    	$dtpl->load((($matches[3] == ",short") ? "directadd_short" : ( ($settings["pics"]["view"] == "1") ? "directadd_popup" : ( ($settings["pics"]["view"] == "2") ? "directadd_ajax" : "directadd") )));
    	
    	$query  = "SELECT items.id    AS id,
                          items.title AS title,
                          items.desc  AS `desc`
                   FROM ".$settings["mysql"]["table_prefix"]."pics_items AS items
                   INNER JOIN ".$settings["mysql"]["table_prefix"]."pics_groups AS groups
                           ON groups.id = items.group_id
                   WHERE groups.mode = \'0\' ORDER BY id DESC LIMIT 0,1";
    	
        $result = mysql_query($query);
    	
    	if ( $result )
    	{
    	    $id   = @mysql_result($result,0,"id");
    	    $size = ((strtolower($matches[1]) == "big") ? "b" : "s");

    	    if ( file_exists($imgpath."/".$id.$size.".jpg") )
    		{
    		    $dtpl->insertVar("path",USER_PATH);

				if ( $settings["display"]["rewrite"] == "1" )
				{
					$iQuery  = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
					$iResult = mysql_query($iQuery);
					$iTitle  = strtolower(umlRewEncode(trim(base64_decode(@mysql_result($iResult,0,"title")))));

					$dtpl->insertVar("img_url",REL_PATH."/".$iTitle."/p/".(($size == "s") ? "small" : "big").$id.".jpg");
					$dtpl->insertVar("url",REL_PATH."/".$iTitle."/p/details".$id.".html");
					
					if ( $settings["pics"]["view"] == 2 ) {
						$dtpl->insertVar("ajaxUrl",REL_PATH."/".$iTitle."/p/ajaxDetails".$id.".html");
					}

					unset($iQuery);
					unset($iTitle);
					mysql_free_result($iResult);
				}
				else
				{
					$dtpl->insertVar("img_url","?init=loadimg&amp;dad=pics&amp;id=".$_GET["id"]."&amp;pid=".$id."&amp;size=".$size);
					$dtpl->insertVar("url","?init=details&amp;dad=pics&amp;style=".$_GET["style"]."&amp;id=".$_GET["id"]."&amp;pic=".$id);
					
					if ( $settings["pics"]["view"] == 2 ) {
						$dtpl->insertVar("ajaxUrl","?init=details&amp;ajax=true&amp;dad=pics&amp;style=".$_GET["style"]."&amp;id=".$_GET["id"]."&amp;pic=".$id);
					}
				}

    		    $dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		    $dtpl->insertVar("desc",auto_nl2br(base64_decode(@mysql_result($result,"0","desc")),$nl2br_modus,$tagfilter));
    		
    		    $done = 1;
    		}
        }

        @mysql_free_result($result);
    }
	else if ( preg_match("/^ajax$/i",$params,$matches) )
    {
    	$dtpl->load("directadd_ajaxscr");
		$dtpl->insertVar("path",REL_PATH);

		$done = 1;
    }
    
    if ( $done === 1 )
    {
        $dtpl->insertVar("style",( $settings["base"]["template_override"] == "1" ) ? $_GET["style"] : "");
        $retval = $dtpl->getResult();
        $dtpl->clear();
        unset($dtpl);
    
        return $retval;
    }
';

?>
