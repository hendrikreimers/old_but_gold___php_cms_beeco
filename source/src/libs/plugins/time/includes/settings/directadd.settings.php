<?

$directadd_code = '

    $dtpl = new template_class;
    $dtpl->path = USER_PATH."/templates/plugins/time/";
    $dtpl->load("directadd");

    if ( strtolower($params) == "last" )
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."time WHERE date < \'".date("Y-m-d")."\' ORDER BY date DESC LIMIT 0,1";
    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
    		$dtpl->insertVar("id",$_GET[\'id\']);
    		$dtpl->insertVar("newsid",@mysql_result($result,"0","id"));
    		
    		$dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));

            $date = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date"));
            $dtpl->insertVar("color",( mktime(0,0,0,substr($date,3,2),substr($date,0,2),substr($date,6,4)) < mktime(0,0,0,date("m"),date("d"),date("Y")) ) ? base64_decode($settings["time"]["color_past"]) : base64_decode($settings["time"]["color_present"]));

            $desc_id = @mysql_result($result,"0","desc_id");
            if ( $desc_id > 0 )
            {
				if ( $settings["display"]["rewrite"] == "1" ) {
					$iQuery = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
					$iResult = mysql_query($iQuery);

					$iTitle = strtolower(umlRewEncode(trim(base64_decode(@mysql_result($iResult,0,"title")))));
					$dtpl->insertVar("url","<a href=\"".REL_PATH."/".$iTitle."/t/t".@mysql_result($result,0,"id").".htm\">Info</a>");

					mysql_free_result($iResult);
					unset($iQuery);
					unset($iTitle);
				} else $dtpl->insertVar("url","<a href=\"?init=more&amp;dad=time&amp;id=".$_GET[\'id\']."&amp;timeid=".@mysql_result($result,0,"id").( ( $settings["base"]["template_override"] ) ? "&amp;style=".$_GET[\'style\'] : "" )."\">Info</a>");
            }

    		$done = ( @mysql_result($result,"0","id") ) ? 1 : 0;
        }
        
        @mysql_free_result($result);
    }
    else if ( preg_match("/^last-[0-9]*$/i",$params) )
    {
    	$query = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."time WHERE date < \'".date("Y-m-d")."\' ORDER BY date DESC LIMIT ".(substr($params,5,sizeof($params))-1).",1";
		$result = mysql_query($query);
    	
    	if ( $result )
    	{
    		$dtpl->insertVar("id",$_GET[\'id\']);
    		$dtpl->insertVar("newsid",@mysql_result($result,"0","id"));
    		
    		$dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));

            $date = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date"));
            $dtpl->insertVar("color",( mktime(0,0,0,substr($date,3,2),substr($date,0,2),substr($date,6,4)) < mktime(0,0,0,date("m"),date("d"),date("Y")) ) ? base64_decode($settings["time"]["color_past"]) : base64_decode($settings["time"]["color_present"]));

            $desc_id = @mysql_result($result,"0","desc_id");
            if ( $desc_id > 0 )
            {
                if ( $settings["display"]["rewrite"] == "1" ) {
					$iQuery = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
					$iResult = mysql_query($iQuery);

					$iTitle = strtolower(umlRewEncode(trim(base64_decode(@mysql_result($iResult,0,"title")))));
					$dtpl->insertVar("url","<a href=\"".REL_PATH."/".$iTitle."/t/t".@mysql_result($result,0,"id").".htm\">Info</a>");

					mysql_free_result($iResult);
					unset($iQuery);
					unset($iTitle);
				} else $dtpl->insertVar("url","<a href=\"?init=more&amp;dad=time&amp;id=".$_GET[\'id\']."&amp;timeid=".@mysql_result($result,0,"id").( ( $settings["base"]["template_override"] ) ? "&amp;style=".$_GET[\'style\'] : "" )."\">Info</a>");
            }

    		$done = ( @mysql_result($result,"0","id") ) ? 1 : 0;
        }
        
        @mysql_free_result($result);
    }
    else if ( preg_match("/^next\+[0-9]*$/i",$params) )
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."time WHERE date >= \'".date("Y-m-d")."\' ORDER BY date ASC LIMIT ".(substr(($params),5,sizeof($params))-1).",1";
    	$result = mysql_query($query);

    	if ( $result )
    	{
    		$dtpl->insertVar("id",$_GET[\'id\']);
    		$dtpl->insertVar("newsid",@mysql_result($result,"0","id"));
    		
    		$dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));

            $date = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date"));
            $dtpl->insertVar("color",( mktime(0,0,0,substr($date,3,2),substr($date,0,2),substr($date,6,4)) < mktime(0,0,0,date("m"),date("d"),date("Y")) ) ? base64_decode($settings["time"]["color_past"]) : base64_decode($settings["time"]["color_present"]));

            $desc_id = @mysql_result($result,"0","desc_id");
            if ( $desc_id > 0 )
            {
                if ( $settings["display"]["rewrite"] == "1" ) {
					$iQuery = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
					$iResult = mysql_query($iQuery);

					$iTitle = strtolower(umlRewEncode(trim(base64_decode(@mysql_result($iResult,0,"title")))));
					$dtpl->insertVar("url","<a href=\"".REL_PATH."/".$iTitle."/t/t".@mysql_result($result,0,"id").".htm\">Info</a>");

					mysql_free_result($iResult);
					unset($iQuery);
					unset($iTitle);
				} else $dtpl->insertVar("url","<a href=\"?init=more&amp;dad=time&amp;id=".$_GET[\'id\']."&amp;timeid=".@mysql_result($result,0,"id").( ( $settings["base"]["template_override"] ) ? "&amp;style=".$_GET[\'style\'] : "" )."\">Info</a>");
            }

    		$done = ( @mysql_result($result,"0","id") ) ? 1 : 0;
        }

        @mysql_free_result($result);
    }
    else if ( strtolower($params) == "next" )
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."time WHERE date >= \'".date("Y-m-d")."\' ORDER BY date ASC LIMIT 0,1";
    	$result = mysql_query($query);

    	if ( $result )
    	{
    		$dtpl->insertVar("id",$_GET[\'id\']);
    		$dtpl->insertVar("newsid",@mysql_result($result,"0","id"));
    		
    		$dtpl->insertVar("title",base64_decode(@mysql_result($result,"0","title")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));

            $date = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date"));
            $dtpl->insertVar("color",( mktime(0,0,0,substr($date,3,2),substr($date,0,2),substr($date,6,4)) < mktime(0,0,0,date("m"),date("d"),date("Y")) ) ? base64_decode($settings["time"]["color_past"]) : base64_decode($settings["time"]["color_present"]));

            $desc_id = @mysql_result($result,"0","desc_id");
            if ( $desc_id > 0 )
            {
                if ( $settings["display"]["rewrite"] == "1" ) {
					$iQuery = "SELECT title FROM ".$settings["mysql"]["table_prefix"]."items WHERE id = \'".$_GET["id"]."\'";
					$iResult = mysql_query($iQuery);

					$iTitle = strtolower(umlRewEncode(trim(base64_decode(@mysql_result($iResult,0,"title")))));
					$dtpl->insertVar("url","<a href=\"".REL_PATH."/".$iTitle."/t/t".@mysql_result($result,0,"id").".htm\">Info</a>");

					mysql_free_result($iResult);
					unset($iQuery);
					unset($iTitle);
				} else $dtpl->insertVar("url","<a href=\"?init=more&amp;dad=time&amp;id=".$_GET[\'id\']."&amp;timeid=".@mysql_result($result,0,"id").( ( $settings["base"]["template_override"] ) ? "&amp;style=".$_GET[\'style\'] : "" )."\">Info</a>");
            }

    		$done = ( @mysql_result($result,"0","id") ) ? 1 : 0;
        }

        @mysql_free_result($result);
    }
    
    if ( $done === 1 )
    {
        $retval = $dtpl->getResult();
        $dtpl->clear();
        unset($dtpl);
    
        return $retval;
    }

';

?>
