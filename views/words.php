<?
// namespace stuff
// use \Michelf\Markdown;

$displayHTML = isset($_GET['html']);

$body = trim($item['body']);
$deck = trim($item['deck']);
$media = $oo->media($item['id']);
if(count($media) > 0)
	$cover = $media[0];
else
	$cover = false;

if($cover)
	$cover_img = m_url($cover);

if($displayHTML)
{
	
	$hyperlink_pattern = '/\[([^\]]*?)\]\((.*?)\)/';
	$body_html = file_get_contents('temp.txt');
	preg_match_all($hyperlink_pattern, $body_html, $temp);
	$outputfile = fopen("output.txt", "w") or die("Unable to open file!");
	foreach($temp[0] as $key => $original)
	{
		// echo $original . '<br>';
		$replacement = "<a href=\'" . $temp[2][$key] . "\'>" . $temp[1][$key] . "</a>";
		if($temp[1][$key] == '...')
		{
			echo $temp[2][$key] . '</br>';
		}
		if( $temp[1][$key] !== 'price' &&
			$temp[1][$key] !== 'donation' && 
			$temp[1][$key] !== 'usd' && 
			$temp[1][$key] !== 'gbp' && 
			$temp[1][$key] !== 'eur' && 
			$temp[1][$key] !== 'paypal' &&
			$temp[1][$key] !== '...'
		) $body_html = str_replace($original, $replacement, $body_html);
		// echo $match[1] . '<br>';
		// echo htmlspecialchars("<a href=\'" . $temp[2][$key] . "\'>" . $temp[1][$key] . "</a>");
		// echo preg_replace($hyperlink_pattern, , $temp[1][$key]);
		// echo '<br><br>';
	}
	fwrite($outputfile, $body_html);
	// $deck_html = htmlspecialchars($deck_html);
	// $body_html = htmlspecialchars($body_html);

}

// only show back button on internal references
$internal = isset($_SERVER['HTTP_REFERER']) && (substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) === $host);
$back_url = "javascript:self.history.back();";

if(!$isShop && !$displayHTML){ ?> 
<div class="mainContainer">
	<div class="wordsContainer body"><?
		// echo nl2br($deck);
		echo $deck;
		if($cover)
		{
		?><img class="cover" src="<? echo $cover_img; ?>"><?
		}
		echo $body;
	         if (isset($showsubscribe))
	                require_once("views/subscribe.php");
		if($internal && !isset($showsubscribe))
		{
		?><a href="<? echo $back_url; ?>">Go back</a><?
		} ?>
	<div>
</div>
<? } 

if($displayHTML){ 
	echo '<br><br><br><br>';
	// echo $body_html;
	?>
	
<? }?>
<style>
	#formmattedBody,
	#formmattedDeck
	{
		color: purple;
		font-family: sans-serif;
		white-space: pre-wrap;
	}
	#formmattedDeck
	{
		margin-bottom: 30px;
	}
</style>

<script>
	<? if($displayHTML){ ?>
		var linebreak_pattern = /\n/g;
		var sformmattedDeck = document.getElementById('formmattedDeck');
		var sformmattedBody = document.getElementById('formmattedBody');
		sformmattedDeck.innerHTML = sformmattedDeck.innerHTML.replace(linebreak_pattern, '&lt;br&gt;');
		sformmattedBody.innerHTML = sformmattedBody.innerHTML.replace(linebreak_pattern, '&lt;br&gt;');

		// var button_pattern = /\<\/span\>\<br\>\<span class="green\-hi"\>/;
		var button_pattern = /\<\/span\>\<br\>\<span/g;
		sformmattedBody.innerText = sformmattedBody.innerText.replace(button_pattern, '</span>&nbsp;<span');
		var form_pattern = /form\>\<br\>\<br\>\<form/g;
		sformmattedBody.innerText = sformmattedBody.innerText.replace(form_pattern, 'form><form');
		var form_pattern_2 = /\<br\>\<\/form\>/g;
		sformmattedBody.innerText = sformmattedBody.innerText.replace(form_pattern_2, '</form>');
		var form_pattern_3 = /\<\/span\>\<br\>\<br\>\<form/g;
		sformmattedBody.innerText = sformmattedBody.innerText.replace(form_pattern_3, '</span><form');
		var input_pattern = /"\>\<br\>\<input type="hidden"/g;
		sformmattedBody.innerText = sformmattedBody.innerText.replace(input_pattern, '"><input type="hidden"');
		
	<? }?>
</script>