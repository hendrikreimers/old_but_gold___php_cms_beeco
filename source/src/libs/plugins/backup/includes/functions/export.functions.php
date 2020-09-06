<?

function createXML($tpl,$sql,$settings)
{
	$tpl->path = PLUGIN_PATH."/templates/export/";             # Template pfad (für den inhalt)
	$tpl->fsuffix = ".temp.xml";

	//Templates laden
	$tpl->load("export",1);

	//Grunddaten einfügen
	$tpl->insertVar("backup_date",date("Y-m-d"));

	//Daten für die Plugins und Base laden und einfügen
	$query  = "SELECT * FROM ".$sql->prefix."plugins WHERE name <> 'backup'";
	$result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Plugin Auswahl",mysql_error()));

    //Informationen einfügen
	while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
	{
	    //Plugin Daten speichern
		$tpl->insertVar($row["name"]."_id",$row["id"]);
		$tpl->insertVar($row["name"]."_title",$row["title"]);
		$tpl->insertVar($row["name"]."_installed",1);
	
	    //Wenn Plugin außer Kontakt, Pics oder Time (mit mehreren Tabellen), Einstellungen sichern!
		if ( ($row["name"] != "kontakt") && ($row["name"] != "pics") && ($row["name"] != "time") && ($row["name"] != "base") && ($row["name"] != "search") )
		{
		    $q = "SELECT * FROM ".$sql->prefix.$row["name"];
			$res = mysql_query($q) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Daten Auswahl von ".$row["name"],mysql_error()));
			
			while ( $r = mysql_fetch_array($res,MYSQL_ASSOC) )
			{
			    $tpl->insertArray($r,array($row["name"]."_entry"));
			}
		}
		else if ( $row["name"] == "time" )
		{
		    $q = "SELECT t.id AS id,
			             t.date AS date,
						 t.title AS title,
						 t.desc_id AS descid,
						 d.text AS `desc`
				  FROM ".$sql->prefix.$row["name"]." AS t
				  LEFT JOIN ".$sql->prefix."time_desc AS d 
				         ON t.desc_id = d.id";
			$res = mysql_query($q) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Daten Auswahl von ".$row["name"],mysql_error()));
			
			while ( $r = mysql_fetch_array($res,MYSQL_ASSOC) )
			{
			    $tpl->insertArray($r,array($row["name"]."_entry"));
			}
		}
		else if ( $row["name"] == "pics" )
		{
		    $q = "SELECT * FROM ".$sql->prefix."pics_groups";
			$res = mysql_query($q) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Daten Auswahl von ".$row["name"],mysql_error()));
			
			while ( $r = mysql_fetch_array($res,MYSQL_ASSOC) )
			{
			    $tpl->insertArray($r,array($row["name"]."_group"));
			}
			
			$q = "SELECT * FROM ".$sql->prefix."pics_items";
			$res = mysql_query($q) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Daten Auswahl von ".$row["name"],mysql_error()));
			
			while ( $r = mysql_fetch_array($res,MYSQL_ASSOC) )
			{
			    $tpl->insertArray($r,array($row["name"]."_item"));
			}
		}
	}

	mysql_free_result($result);

	//Einstellungen einfügen
	$query = "SELECT s.id AS id,
	                 p.name AS name,
					 s.key AS `key`,
					 s.value AS value 
			   FROM ".$sql->prefix."settings AS s
			   INNER JOIN ".$sql->prefix."plugins AS p
			   ON s.plugin_id = p.id";
			   
	$result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Settings Auswahl",mysql_error()));
	
	//Inhalt der einzelnen Menüpunkte speichern
	while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
	{
	    $tpl->insertArray($row,array($row["name"]."_settings"));
	}
	
	//Menü einträge
	$query = "SELECT i.id         AS id, 
	       			 i.parent_id  AS parent,
					 i.plugin_id  AS plugin_id,
					 i.priority   AS priority,
	                 i.title      AS title,
	                 i.is_visible AS visible,
	                 i.is_active  AS active,
	                 c.content    AS content,
	                 r.is_manual  AS manual,
	                 r.redirect   AS redirect
	          FROM ".$sql->prefix."items AS i
	          LEFT JOIN ".$sql->prefix."contents AS c
	            ON c.item_id = i.id
	          LEFT JOIN ".$sql->prefix."redirects AS r
	            ON r.item_id = i.id";
			
	$result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Menü Auswahl",mysql_error()));

	while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
	{
	    $tpl->insertArray($row,array("base_item"));
	}

	return $tpl->getResult();
}

?>