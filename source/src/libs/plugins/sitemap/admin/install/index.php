<?

function initialize()
{
	header("Location: index.php?SID=".$_GET['SID']."&action=main&plugin=sitemap");
}

?>