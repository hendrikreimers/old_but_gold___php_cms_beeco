<html>
<head>
<title>Beeco - Manual</title>
<style type="text/css" rel="Stylesheet">
a { color: #000000; text-decoration: none; }
a:hover { color: #000000; text-decoration: underline; }
</style>
</head>
<body>
<font face="verdana" size="2">
<?

//Das Verzeichniss durchsuchen nach Anleitungen
$dir = dir("./") or die("Kein Zugriff auf das Manual Verzeichniss,<br>bitte CHMOD durchführen per FTP Software");
$i = 0;

//alle dateien
while ( $file = $dir->read() )
{
    //Nur HTML akzeptieren
    if ( preg_match("/^([a-z0-9\-_\(\)\.]*)(\.)(htm|html)$/i",$file) )
    {
        //Dateinamen speichern
        $items[$i]["file"] = $file;

        //Überhaupt etwas gefunden?
        $found = "1";
        
        //Informationen aus der Datei
        $fp = fopen("./".$file,"r");
        
        //Titel
        $line = fgets($fp,"1024");
        preg_match("/^(<!--\{Title:)(.*)(\}-->)/i",$line,$matches);
        $items[$i]["title"] = htmlentities($matches[2]);
        
        //Beschreibung
        $line = fgets($fp,"1024");
        preg_match("/^(<!--\{Description:)(.*)(\}-->)/i",$line,$matches);
        $items[$i]["desc"] = $matches[2];
        
        //Dateizugriff beenden
        fclose($fp);
        
        $i++;
    }
}

if ( $found != "1" )
{
    echo "Keine Anleitung verfügbar!";
}
else
{
	//Sortieren nach Titel
	usort($items,create_function('$a,$b','if ($a["title"] == $b["title"]) return 0; return ($a["title"] < $b["title"]) ? -1 : 1;'));

    //Platz
    echo "<br>";

	//Ausgabe
	foreach ( $items as $item )
	{
	    if ( !preg_match("/^(frame-|index)(.*)(htm|php|html)$/i",$item["file"]) )
	    {
            //Ausgabe
            echo "<a href=\"".$item["file"]."\"><b><font size=\"3\">".$item["title"]."</font></b><br>".$item["desc"]."</a>";

            //Platz
            echo "<br><br><br>";
	    }
	}
}

$dir->Close();

?>
</font>
</body>
</html>