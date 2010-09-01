<?
if(!isset($_GET["q"])) {
	showForm();
} else {
	
ini_set('display_errors', 0);

$url = "http://www.google.pt/search?hl=pt-PT&source=hp&q=imdb+".strtr($_GET['q'],array(" "=>"+"))."&aq=f&aqi=g3&aql=&oq=&gs_rfai=&btnI=1";
//$url = "http://www.imdb.com/title/tt0421715/";

//if (!file_exists("imdb.html")) {
$ch = curl_init();
$useragent="Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-us) AppleWebKit/533.17.8 (KHTML, like Gecko) Version/5.0.1 Safari/533.17.8";
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$page = curl_exec($ch);
curl_close($ch);

$doc = new DomDocument();
$doc->loadHTML($page);

$xml = new domxpath($doc);
$items = $xml->query("/html/body/a");

$url = $items->item(0)->getAttribute("href");
$imdbURL=$url;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$page = curl_exec($ch);
curl_close($ch);

$fp = fopen("imdb.html","w+");
fwrite($fp, $page);
fclose($fp);
/*
} else {
	echo "cached";
	$fp = fopen("imdb.html","r");
	$page = fread($fp, filesize("imdb.html"));
	fclose($fp);
}
*/

$doc = new DomDocument();
$doc->loadHTML($page);

$xml = new domxpath($doc);
$items = $xml->query("//h1");


$title = trim($items->item(0)->firstChild->nodeValue);		// title
$fn = normaliza($title).".jpg";
$dfn ="images/".$fn;


$items = $xml->query("//div[attribute::class='starbar-meta']/b");

$rating = $items->item(0)->nodeValue;
$rating = substr($rating, 0, strlen($rating)-strpos($rating, "/"));


$url .= "plotsummary";
###################### cinema.sapo.pt ######################

function strip2sapo($t) {
	$strip = array(" "=>"-","/"=>"-","&"=>"and");
	$t = strtr($t, $strip);
	$t = preg_replace("/[^a-zA-Z0-9\s-]/", "", $t);
	return $t;
}

if (isset($_GET["sapo"]) && $_GET["sapo"]!="") {
	$url = "http://cinema.sapo.pt/ajax-get-movie.php?movieSlug=".strip2sapo($_GET["sapo"]);
} else {
	$url = "http://cinema.sapo.pt/ajax-get-movie.php?movieSlug=".strip2sapo($title);
}
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);

$obj = json_decode($data);

$sapo = false;

if(!$obj->success || $obj->item->title == "") {
	$url = "http://cinema.sapo.pt/ajax-get-movie.php?movieSlug=".strip2sapo($_GET["q"]);
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	
	$obj = json_decode($data);
	
	if(!$obj->success || $obj->item->title == "") { showForm($title); die();}
	else { $sapo = $obj->item;}
} else {
	$sapo = $obj;
}

if ($sapo) {
	$plot = $sapo->item->screenplay;				// plot pt
	$titlept = $sapo->item->title;
	$year = $sapo->item->year;

	$poster = (array) json_decode($sapo->item->posterFiles);
	foreach($poster as $v) {
		$poster = $v;
		break;
	}

	$genres = $sapo->related->genre->items;
	$genre = "";
	foreach($genres as $k => $v) {
		$genre .= $v->title." ";
	}

if (strlen($poster) == 0) { showForm($title); die();}
$ch = curl_init($poster);
$fp = fopen("poster.jpg", "w+");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);


//echo "\nSAPO\n$title\n$titlept\n$plot\n$year\n$poster\n$genre\n$rating";


require("generatepdf.php");

}
}


function normaliza ($string){
    $a = 'ÀÁÇÈÉÊËÌÍÑÒÓÔÕÖÙÚÛÜàáâãäåçèéêëìíîïðñòóôõöøùúû/:';
    $b = 'aaceeeeiinooooouuuuaaaaaaceeeeiiiidnoooooouuu--';
    $string = utf8_decode($string);
    $string = strtr($string, utf8_decode($a), $b);
    $string = strtr($string, array(" "=>"_"));
    $string = strtolower(trim($string));
    return utf8_encode($string);
} 

function showForm($title = null) {
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	if ($title!="") {
		echo "<p>A procura no Sapo falhou com o titulo (IMDB) <b>$title</b>. <a href='http://cinema.sapo.pt/pesquisa/?terms=".urlencode($title)."' target='_blank'>Procure o filme em cinema.sapo.pt</a> e coloque o URL no campo Sapo ID em baixo.</p>";
	} else { ?>
		<p><b>Gerador de PDFs com informações sobre filmes.</b> Exemplo: <a href="The-Shawshank-Redemption.pdf">The Shawshank Redemption</a></p>
<?	}
?>
	<form action="index.php" method="get">
	<label for="ot">Titulo Original: </label><input id="ot" name="q" size="30" value="<? echo $title==""?$title:"" ?>"/><br>
	<label for="sapo">Sapo ID<? echo $title==""?" (opcional):":":"; ?> </label><input id="sapo" name="sapo" size="30"/><br>
	<input type="submit" value="Gerar"/>
	</form>
	
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-6001242-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?
}
?>