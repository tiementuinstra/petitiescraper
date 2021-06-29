<style>
div#anchor-scroll{display:none;}
div.petition-signature-list{
	display:table-row;
	width:100%;
	clear:both;
}
div.petition-signature-list div,
div.petition-signature-list span{
	display:table-cell;
	width:25%;
}
div.petition-signature-list div.petition-signature-occupation,
div.petition-signature-list div.petition-signature-actions,
div.petition-signature-list span.signature-time{
	display:none;
}
</style>
<?php
require 'simple_html_dom.php';
//$url = 'geen-nieuwbouw-in-de-bossen-bij-paleis-soestdijk';
$url = $_REQUEST['petitie'];
$totalpages = (int) $_REQUEST['pages'];
echo $totalpages;
$url = 'https://petities.nl/petitions/'.$_REQUEST['petitie'];
$html = file_get_html($url);
$title = $html->find('h1', 0);
echo '<a href="'.$url.'"><h1>'.$title->plaintext.'</h1></a>';
echo '<table border="1">';
echo '<tr>
<th>Onderschrijver</th>
<th>Plaats</th>
<th>Beroep</th>
<th>Tijd</th>
<th>Pagina</th>
</tr>';
for($page=1;$page<=$totalpages;$page++){
	$signatures = scraping_petitie($url,$page);
	foreach($signatures as $signature){
		echo '<tr>';
		echo '<td>'.$signature['name'].'</td>';
		echo '<td>'.$signature['location'].'</td>';
		echo '<td><span>'.$signature['occupation'].'</span></td>';
		echo '<td>'.$signature['time'].'</td>';
		echo '<td><a href="'.$signature['url'].'" target="_blank">'.$page.'</a></td>';
		echo '</tr>';
	}
	
}
echo '</table>';

function scraping_petitie($url,$page) {
    // create HTML DOM
    $html = file_get_html($url.'/signatures?locale=nl&page='.$page);

    // get news block
    foreach($html->find('div.petition-signature-list') as $signature) {
        $item['name'] = trim($signature->find('div.petition-signature-name', 0)->plaintext);
        $item['location'] = trim($signature->find('div.petition-signature-location', 0)->plaintext);
        $item['occupation'] = trim($signature->find('div.petition-signature-occupation', 0)->plaintext);
        $item['time'] = trim($signature->find('span.signature-time', 0)->plaintext);
        $item['url'] = $url.'/signatures?locale=nl&page='.$page;

        $ret[] = $item;
    }
    
    // clean up memory
    $html->clear();
    unset($html);

    return $ret;
}


// -----------------------------------------------------------------------------
// test it!

// "http://digg.com" will check user_agent header...
ini_set('user_agent', 'My-Application/2.5');

$ret = scraping_petitie($url);

?>
