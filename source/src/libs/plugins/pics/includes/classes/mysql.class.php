<?

/* MySQL Funktionen um die Eintr�ge zu laden, einzuf�gen, usw. */

class pics_mysql_class
{
	//Tabellen prefix
	var $prefix;

    /* --- AUSLESEN --- START --- */
    
      //L�dt die Gruppen
      function load_groups($order)
      {
	      //Gruppen abfragen
          $query  = "SELECT * FROM ".$this->prefix."pics_groups";
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Abfrage",mysql_error()));
          
          if ( $result )
          {
              //Verarbeiten
              while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
              {
                  //Anzahl der Bilder
                  $query   = "SELECT COUNT(id) AS `count` FROM ".$this->prefix."pics_items WHERE group_id = '".$row["id"]."'";
                  $cresult = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Biler z�hlen",mysql_error()));
                  $count   = @mysql_result($cresult,0,"count");
                  @mysql_free_result($cresult);

                  $retval[] = array("id"    => $row["id"],
                                    "mode"  => $row["mode"],
                                    "title" => base64_decode($row["title"]),
                                    "desc"  => base64_decode($row["desc"]),
                                    "count" => (($count > "0") ? $count : "0"));
              }
              
              if ( sizeof($retval) > 0 )
              {
                  //Array sortieren
                  $cmp = create_function('$a,$b','if (strtolower($a["title"]) == strtolower($b["title"])) return 0; return (strtolower($a["title"]) > strtolower($b["title"])) ? -1 : 1;');
                  usort($retval,$cmp);
                  unset($cmp);
                  
				  //Sortierung anpassen
				  if ( $order == "ASC" )
				  {
				      $retval = array_reverse($retval);
				  }
              }
              
              mysql_free_result($result);
              return $retval;
          }
          else
          {
              @mysql_free_result($result);
              return false;
          }
      }
      
      //L�dt die infos zu einer Gruppe
      function load_group($id)
      {
          //Query
          $query  = "SELECT * FROM ".$this->prefix."pics_groups WHERE id = '".$id."'";
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Abfrage",mysql_error()));

          if ( $result )
          {
              //Verarbeiten
              $retval["id"]    = $id;
              $retval["mode"]  = mysql_result($result,"0","mode");
              $retval["title"] = mysql_result($result,"0","title");
              $retval["desc"]  = mysql_result($result,"0","desc");

              mysql_free_result($result);
              return $retval;
          }
          else
          {
              @mysql_free_result($result);
              return false;
          }
      }
      
      //L�dt alle bidler zu einer gruppe
      function load_items($group_id,$order = "ASC")
      {
          $query  = "SELECT items.id    AS id,
                            items.title AS title,
                            items.desc  AS `desc`,
                            groups.mode AS mode,
                            items.group_id AS `group`
                     FROM ".$this->prefix."pics_items        AS items
                     INNER JOIN ".$this->prefix."pics_groups AS groups
                             ON groups.id = items.group_id
                     WHERE group_id = '".$group_id."' ORDER BY id";
                            
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Abfrage",mysql_error()));
          
          while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
          {
              $retval[] = array("id"    => $row["id"],
                                "title" => base64_decode($row["title"]),
                                "desc"  => base64_decode($row["desc"]),
                                "group" => $row["group"],
                                "mode"  => $row["mode"]);
          }
          
          if ( sizeof($retval) > 0 )
          {
              //Array sortieren
              $cmp = create_function('$a,$b','if ( strtolower( ((strlen($a["title"]) > 0) ? $a["title"] : $a["id"]) ) == strtolower( ((strlen($b["title"]) > 0) ? $b["title"] : $b["id"]) ) ) return 0; return (strtolower( ((strlen($a["title"]) > 0) ? $a["title"] : $a["id"]) ) > strtolower( ((strlen($b["title"]) > 0) ? $b["title"] : $b["id"]) )) ? -1 : 1; ');
              usort($retval,$cmp);
              unset($cmp);
			  
			  if ( $order == "ASC" )
			  {
                  $retval = array_reverse($retval);
			  }
          }

          mysql_free_result($result);
          return $retval;
      }
      
      //L�dt ein einzelnes Bild
      function load_item($id)
      {
          $query = "SELECT items.id    AS id,
                           items.title AS title,
                           items.desc  AS `desc`,
                           groups.mode AS mode,
                           items.group_id AS `group`
                    FROM ".$this->prefix."pics_items        AS items
                    INNER JOIN ".$this->prefix."pics_groups AS groups
                            ON groups.id = items.group_id
                    WHERE items.id = '".$id."'";
                    
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Abfrage",mysql_error()));

          if ( $result )
          {
              $retval["id"]    = $id;
              $retval["mode"]  = mysql_result($result,0,"mode");
              $retval["title"] = base64_decode(mysql_result($result,0,"title"));
              $retval["desc"]  = base64_decode(mysql_result($result,0,"desc"));
              $retval["group"] = mysql_result($result,0,"group");
              
              mysql_free_result($result);
              return $retval;
          }
          else return false;
      }
    
      //Z�hlt die Eintr�ge einer gruppe
      function get_item_count($id)
      {
          $query  = "SELECT COUNT(id) AS `count` FROM ".$this->prefix."pics_items WHERE group_id = '".$id."'";
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Z�hlen fehlgeschlagen",mysql_error()));
          $count  = @mysql_result($result,"0","count");
          @mysql_free_result($result);
          return ( $count > 0 ) ? $count : 0;
      }
    
    /* --- AUSLESEN --- ENDE --- */
    
    
    /* --- HINZUF�GEN --- START --- */
    
      //F�gt eine neue Gruppe hinzu
      function add_group($title,$desc)
      {
          $query = "INSERT INTO ".$this->prefix."pics_groups VALUES ('','','".base64_encode($title)."','".base64_encode($desc)."')";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Hinzuf�gen fehlgeschlagen",mysql_error()));
          
          return true;
      }
      
      //F�gt ein Bild hinzu
      function add_item($group_id,$title,$desc)
      {
          $query = "INSERT INTO ".$this->prefix."pics_items VALUES ('','".$group_id."','".base64_encode($title)."','".base64_encode($desc)."')";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Hinzuf�gen fehlgeschlagen",mysql_error()));
          
          return mysql_insert_id();
      }

    /* --- HINZUF�GEN --- ENDE --- */
    
    
    /* --- L�SCHEN --- START --- */
    
      //L�scht eine Gruppe aus der DB
      function del_group($id)
      {
          $query = "DELETE FROM ".$this->prefix."pics_groups WHERE id = '".$id."'";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"L�schen fehlgeschlagen",mysql_error()));
      }
      
      //L�scht ein Bild aus der DB
      function del_item($id)
      {
          $query = "DELETE FROM ".$this->prefix."pics_items WHERE id = '".$id."'";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"L�schen fehlgeschlagen",mysql_error()));
      }
    
    /* --- L�SCHEN --- ENDE --- */
    
    
    /* --- UPDATE --- START --- */
    
      //Aktualisiert ein Item (bild)
      function upd_item($id,$group_id,$title,$desc)
      {
          $query = "UPDATE ".$this->prefix."pics_items SET group_id = '".$group_id."',title = '".base64_encode($title)."',`desc` = '".base64_encode($desc)."' WHERE id = '".$id."'";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Update fehlgeschlagen",mysql_error()));
      }
      
      //Aktualisiert eine Gruppe
      function upd_group($id,$title,$desc)
      {
          $query = "UPDATE ".$this->prefix."pics_groups SET title = '".base64_encode($title)."',`desc` = '".base64_encode($desc)."' WHERE id = '".$id."'";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Update fehlgeschlagen",mysql_error()));
      }
      
      //Aktualisiert die Eigenschaften einer Gruppe
      function upd_attributes($id,$mode)
      {
      	  $query = "UPDATE ".$this->prefix."pics_groups SET mode = '".$mode."' WHERE id = '".$id."'";
      	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Update fehlgeschlagen",mysql_error()));
      }
    
    /* --- UPDATE --- ENDE --- */
}

?>
