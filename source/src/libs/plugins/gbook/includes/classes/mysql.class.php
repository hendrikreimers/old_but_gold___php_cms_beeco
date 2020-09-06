<?

/* MySQL Funktionen um die Eintr�ge zu laden, einzuf�gen, usw. */

class gbook_mysql_class
{
	//Tabellen prefix
	var $prefix;

    /* --- AUSLESEN --- START --- */
	
	  //L�dt einen einzelnen Eintrag
	  function load_entry($id)
	  {
	  	  //Abfrage
	  	  $query = "SELECT * FROM ".$this->prefix."gbook WHERE id = '".$id."'";
	  	  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Auswahl des Eintrags",mysql_error()));
	  	  
	  	  if ( $result )
	  	  {
	  	  	  $retval["id"]       = @mysql_result($result,"0","id");
	  	  	  $retval["ip"]       = @mysql_result($result,"0","ip");
	  	  	  $retval["host"]     = @mysql_result($result,"0","host");
	  	  	  $retval["date"]     = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\3.\\2.\\1",@mysql_result($result,"0","date"));
	  	  	  $retval["time"]     = substr(@mysql_result($result,"0","time"),0,5);
	  	  	  $retval["name"]     = base64_decode(@mysql_result($result,"0","name"));
	  	  	  $retval["email"]    = base64_decode(@mysql_result($result,"0","email"));
	  	  	  $retval["icq"]      = base64_decode(@mysql_result($result,"0","icq"));
	  	  	  $retval["homepage"] = base64_decode(@mysql_result($result,"0","homepage"));
	  	  	  $retval["text"]     = base64_decode(@mysql_result($result,"0","text"));
	  	  	  $retval["comment"]  = base64_decode(@mysql_result($result,"0","comment"));
	  	  	  
	  	  	  #$retval["homepage"] = ( $retval["homepage"] == "http://" ) ? "" : $retval["homepage"];
	  	  	  
	  	  	  @mysql_free_result($result);
	  	  	  return $retval;
	  	  }
		  else
		  {
			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	
	  //L�dt die Eintr�ge einer bestimmten Seite
  	  function gbook_load_page($page,$max_entries,$nl2br,$tagfilter,$enable_html,$ignore_icq = 0, $ignore_homepage = 0, $ignore_email = 0)
	  {
		  //Berechnen der auszuw�hlenden LIMITs
		  $position = ($page*$max_entries)-$max_entries;
		
		  //Setzen des Z�hlers f�r die darstellung
		  $count = $this->get_max_entries()-$position;
		
		  //Abfrage
		  $query  = "SELECT * FROM ".$this->prefix."gbook ORDER BY CONCAT(date,time) DESC LIMIT ".$position.",".$max_entries;
		  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Auswahl der Eintr�ge",mysql_error()));  
		
		  //Ergebnis?
		  if ( $result )
		  {
			  //Verarbeiten
			  while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
			  {
			  	  //Text entschl�sseln
  				  $row["name"]     = stripslashes(base64_decode($row["name"]));
				  $row["email"]    = stripslashes(base64_decode($row["email"]));
				  $row["icq"]      = base64_decode($row["icq"]);
				  $row["homepage"] = stripslashes(base64_decode($row["homepage"]));
				  $row["text"]     = stripslashes(base64_decode($row["text"]));
				  $row["comment"]  = ( $row["comment"] ) ? "<b>Kommentar:</b> ".stripslashes(base64_decode($row["comment"])) : "";
	
  				  //Datum versch�nern
		          $row["date"] = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\3.\\2.\\1",$row["date"]);
		
        		  //Uhrzeit verk�rzen
		          $row["time"] = substr($row["time"],0,5);
		        
		          //Gegebenfalls HTML abschalten
				  if ( $enable_html == "0" )
				  {
					  $row["text"] = htmlentities($row["text"]);
				  }
				
				  //Links anpassen
				  if ( $ignore_email == 0 ) {
					  $row["email"]    = ( $row["email"] ) ? "<a href=\"mailto:".$row["email"]."\"><img src=\"".REL_USER_PATH."/gfx/gbook/mail.gif\" alt=\"E-Mail\" class=\"gbookImg\" /></a>" : "";
				  }

				  if ( $ignore_icq == 0 ) {
					  $row["icq"]      = ( $row["icq"] ) ? "<a href=\"http://www.icq.com/whitepages/cmd.php?uin=".$row["icq"]."&amp;action=message\"><img src=\"".REL_USER_PATH."/gfx/gbook/icq.gif\" alt=\"ICQ\" class=\"gbookImg\" /></a>" : "";
				  }
				  
				  if ( $ignore_homepage == 0 ) {
					  $row["homepage"] = ( ($row["homepage"]) && ($row["homepage"] != "http://") ) ? "<a href=\"".$row["homepage"]."\" onclick=\"window.open(this.href); return false;\"><img src=\"".REL_USER_PATH."/gfx/gbook/home.gif\" alt=\"Homepage\" class=\"gbookImg\" /></a>" : "";
				  }

				  //Textumbruch
				  $row["text"] = auto_nl2br($row["text"],$nl2br,base64_decode($tagfilter));
  		
                  //Ver�ndertes Ergebnis zum zur�ckliefern verarbeiten
				  $row["count"] = $count; # Z�hler anh�ngen
				  $retval[]     = $row;   # Speichern
				  $count--;               # Z�hler verkleinern
			  }
			
			  @mysql_free_result($result);
			  return $retval;
		  }
		  else
		  {
			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	
	  //Zeigt wieviele Seite verf�gbar sind
	  function get_max_pages($max_entries)
  	  {
		  //Eintr�ge z�hlen
		  $query  = "SELECT COUNT(id) as `count` FROM ".$this->prefix."gbook";
		  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim z�hlen",mysql_error()));
		
		  if ( $result )
		  {
			  $count = @mysql_result($result,"0","count");
			  @mysql_free_result($result);
			
			  return ceil($count/$max_entries);
		  }
		  else
		  {
			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	
	  //Zeigt wieviele Seite verf�gbar sind
	  function get_max_entries()
	  {
		  //Eintr�ge z�hlen
		  $query  = "SELECT COUNT(id) as `count` FROM ".$this->prefix."gbook";
		  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim z�hlen",mysql_error()));
		
		  if ( $result )
		  {
			  $count = @mysql_result($result,"0","count");
			  @mysql_free_result($result);
			
			  return $count;
		  }
		  else
		  {
  			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	
	/* --- AUSLESEN --- ENDE --- */
	
	
	
	/* --- HINZUF�GEN --- START --- */
	
	  function add_entry($name,$email,$icq,$homepage,$text,$send_notice,$notice_email)
	  {
	  	  //Zus�tzliche Infos
	  	  $ip   = $_SERVER["REMOTE_ADDR"];
	  	  $host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
	  	  $date = date("Y-m-d");
	  	  $time = date("H:i:s");
	  	  
	  	  //Hinzuf�gen
	  	  $query = "INSERT INTO ".$this->prefix."gbook VALUES ('','".$ip."','".$host."','".$date."','".$time."','".$name."','".$email."','".$icq."','".$homepage."','".$text."','')";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzuf�gen",mysql_error()));
	  	  
	  	  if ( $send_notice == "1" )
	  	  {
	  	  	  $to      = base64_decode($notice_email);
	  	  	  $subject = "Neuer G�stebuch Eintrag";
	  	  	  $message = "IP: ".$ip."\n".
	  	  	             "HOST: ".$host."\n".
	  	  	             "\n".
	  	  	             "DATUM: ".$date."\n".
	  	  	             "ZEIT: ".$time."\n".
	  	  	             "\n".
	  	  	             "NAME: ".base64_decode($name)."\n".
	  	  	             "E-MAIL: ".base64_decode($email)."\n".
	  	  	             "ICQ: ".base64_decode($icq)."\n".
	  	  	             "WWW: ".base64_decode($homepage)."\n".
	  	  	             "TEXT: \n\n".base64_decode($text);
	  	  	             
	  	  	  @mail($to,$subject,$message);
	  	  	  
	  	  	  return $message;
	  	  }
	  }
	
	/* --- HINZUF�GEN --- ENDE --- */
	
	
	
	/* --- L�SCHEN --- START --- */
	
	  //L�scht einen Eintrag
	  function delete_entry($id)
	  {
	  	  //ID check
	  	  if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
	  	      return false;
	  	  }

	  	  //L�schen
	  	  $query = "DELETE FROM ".$this->prefix."gbook WHERE id = '".$id."'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim l�schen des Eintrages",mysql_error()));
	  	  return true;
	  }
	
	/* --- L�SCHEN --- ENDE --- */
	
	
	
	/* --- UPDATE --- START --- */
	
	  //Aktualisiert einen Eintrag
	  function update_entry($id,$name,$email,$icq,$homepage,$text,$comment)
	  {
	  	  //ID check
	  	  if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
	  	      return false;
	  	  }

	  	  $name     = base64_encode($name);
	  	  $email    = base64_encode($email);
	  	  $icq      = base64_encode(str_replace("-","",$icq));
	  	  $text     = base64_encode($text);
	  	  $comment  = base64_encode($comment);
		  
		  if ( $GLOBALS['settings']["gbook"]["ignore_homepage"] == "0" ) {
	  	  	  $homepage = ( preg_match("=^(http://)(.*)$=i",$homepage) ) ? base64_encode($homepage) : base64_encode("http://".$homepage);
		  } else $homepage = base64_encode($homepage);
  	
	  	  $query = "UPDATE ".$this->prefix."gbook SET name = '$name',email = '$email',icq = '$icq',homepage = '$homepage',text = '$text',comment = '$comment' WHERE id = '$id'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim �ndern des Eintrages",mysql_error()));
	  	  return true;
	  }
	
	/* --- UPDATE --- ENDE --- */
}

?>