<?
// namespace stuff
// use \Michelf\Markdown;

$displayHTML = isset($_GET['html']);

$body = $item['body'];
$deck = $item['deck'];
$media = $oo->media($item['id']);
if(count($media) > 0)
	$cover = $media[0];
else
	$cover = false;

if($cover)
	$cover_img = m_url($cover);

if($displayHTML)
{
	$body_html = $item['body'];
	$deck_html = $item['deck'];
	$italic_pattern = '/\*(.*?)\*/';
	$deck_html = preg_replace($italic_pattern, '<i>$1</i>', $deck_html);
	$body_html = preg_replace($italic_pattern, '<i>$1</i>', $body_html);
	$hyperlink_pattern = '/\[(.*?)\]\((.*?)\)/';
	$body_html = preg_replace($hyperlink_pattern, '<a href="$2">$1</a>', $body_html);

	$deck_html = htmlspecialchars($deck_html);
	$body_html = htmlspecialchars($body_html);

}

// only show back button on internal references
$internal = isset($_SERVER['HTTP_REFERER']) && (substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) === $host);
$back_url = "javascript:self.history.back();";

?><div class="mainContainer">
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
		}
	?><div>
</div>
<? if($displayHTML){ ?>
	<div id="formmattedDeck" style="color:purple"><?= $deck_html; ?></div>
	<pre id="formmattedBody" style="font-family: sans-serif;"><?= $body_html; ?></pre>
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