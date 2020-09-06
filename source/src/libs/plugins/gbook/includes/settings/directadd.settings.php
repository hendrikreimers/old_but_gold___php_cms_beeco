<?

$directadd_code = '

    $dtpl = new template_class;
    $dtpl->path = USER_PATH."/templates/plugins/gbook/";
    $dtpl->load("directadd");

    if ( strtolower($params) == "last" )
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."gbook ORDER BY CONCAT(date,time) DESC LIMIT 0,1";
    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
    		$res = base64_decode(@mysql_result($result,"0","email"));
			$email      = ( $res ) ? "<a href=\"mailto:".$res."\"><img src=\"".REL_USER_PATH."/gfx/gbook/mail.gif\" alt=\"E-Mail\" class=\"gbookImg\"></a>" : "";
			
			$res = base64_decode(@mysql_result($result,"0","icq"));
			$icq    = ( $res ) ? "<a href=\"http://www.icq.com/whitepages/cmd.php?uin=".$res."&amp;action=message\"><img src=\"".REL_USER_PATH."/gfx/gbook/icq.gif\" alt=\"ICQ\" class=\"gbookImg\"></a>" : "";
			
			$res = base64_decode(@mysql_result($result,"0","homepage"));
			$homepage = ( ($res) && ($res != "http://") ) ? "<a href=\"".$res."\" onclick=\"window.open(this.href); return false;\"><img src=\"".REL_USER_PATH."/gfx/gbook/home.gif\" alt=\"Homepage\" class=\"gbookImg\"></a>" : "";

    		$dtpl->insertVar("name",base64_decode(@mysql_result($result,"0","name")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));
    		$dtpl->insertVar("time",substr(@mysql_result($result,"0","time"),0,5));
    		$dtpl->insertVar("icq",$icq);
    		$dtpl->insertVar("email",$email);
    		$dtpl->insertVar("homepage",$homepage);
    		$dtpl->insertVar("text",auto_nl2br(base64_decode(@mysql_result($result,"0","text")),$nl2br_modus,$tagfilter));
    		$dtpl->insertVar("content",base64_decode(@mysql_result($result,"0","content")));
    		
    		$done = ( @mysql_result($result,"0","text") ) ? 1 : 0; 
        }
        
        @mysql_free_result($result);
    }
    else if ( preg_match("/^last-[0-9]*$/i",$params) )
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."gbook ORDER BY CONCAT(date,time) DESC LIMIT ".(substr(($params),5,sizeof($params))-1).",1";
    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
    		$res = base64_decode(@mysql_result($result,"0","email"));
			$email      = ( $res ) ? "<a href=\"mailto:".$res."\"><img src=\"".REL_USER_PATH."/gfx/gbook/mail.gif\" alt=\"E-Mail\" class=\"gbookImg\"></a>" : "";
			
			$res = base64_decode(@mysql_result($result,"0","icq"));
			$icq    = ( $res ) ? "<a href=\"http://www.icq.com/whitepages/cmd.php?uin=".$res."&amp;action=message\"><img src=\"".REL_USER_PATH."/gfx/gbook/icq.gif\" alt=\"ICQ\" class=\"gbookImg\"></a>" : "";
			
			$res = base64_decode(@mysql_result($result,"0","homepage"));
			$homepage = ( ($res) && ($res != "http://") ) ? "<a href=\"".$res."\" onclick=\"window.open(this.href); return false;\"><img src=\"".REL_USER_PATH."/gfx/gbook/home.gif\" alt=\"Homepage\" class=\"gbookImg\"></a>" : "";

    		$dtpl->insertVar("name",base64_decode(@mysql_result($result,"0","name")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));
    		$dtpl->insertVar("time",substr(@mysql_result($result,"0","time"),0,5));
    		$dtpl->insertVar("icq",$icq);
    		$dtpl->insertVar("email",$email);
    		$dtpl->insertVar("homepage",$homepage);
    		$dtpl->insertVar("text",auto_nl2br(base64_decode(@mysql_result($result,"0","text")),$nl2br_modus,$tagfilter));
    		$dtpl->insertVar("content",base64_decode(@mysql_result($result,"0","content")));
    			
    		$done = ( @mysql_result($result,"0","text") ) ? 1 : 0; 
        }
        
        @mysql_free_result($result);
    }
    else
    {
    	$query  = "SELECT * FROM ".$settings["mysql"]["table_prefix"]."gbook ORDER BY CONCAT(date,time) LIMIT ".($params-1).",1";
    	$result = mysql_query($query);
    	
    	if ( $result )
    	{
    		$res = base64_decode(@mysql_result($result,"0","email"));
			$email      = ( $res ) ? "<a href=\"mailto:".$res."\"><img src=\"".REL_USER_PATH."/gfx/gbook/mail.gif\" alt=\"E-Mail\" class=\"gbookImg\"></a>" : "";
			
			$res = base64_decode(@mysql_result($result,"0","icq"));
			$icq    = ( $res ) ? "<a href=\"http://www.icq.com/whitepages/cmd.php?uin=".$res."&amp;action=message\"><img src=\"".REL_USER_PATH."/gfx/gbook/icq.gif\" alt=\"ICQ\" class=\"gbookImg\"></a>" : "";
			
			$res = base64_decode(@mysql_result($result,"0","homepage"));
			$homepage = ( ($res) && ($res != "http://") ) ? "<a href=\"".$res."\" onclick=\"window.open(this.href); return false;\"><img src=\"".REL_USER_PATH."/gfx/gbook/home.gif\" alt=\"Homepage\" class=\"gbookImg\"></a>" : "";

    		$dtpl->insertVar("name",base64_decode(@mysql_result($result,"0","name")));
    		$dtpl->insertVar("date",preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\\3.\\\2.\\\1",@mysql_result($result,"0","date")));
    		$dtpl->insertVar("time",substr(@mysql_result($result,"0","time"),0,5));
    		$dtpl->insertVar("icq",$icq);
    		$dtpl->insertVar("email",$email);
    		$dtpl->insertVar("homepage",$homepage);
    		$dtpl->insertVar("text",auto_nl2br(base64_decode(@mysql_result($result,"0","text")),$nl2br_modus,$tagfilter));
    		$dtpl->insertVar("content",base64_decode(@mysql_result($result,"0","content")));
    		
    		$done = ( @mysql_result($result,"0","text") ) ? 1 : 0; 
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