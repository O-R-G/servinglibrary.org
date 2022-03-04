<?
// namespace stuff
use \Michelf\Markdown;

$r = $oo->get($uu->id);
$body = Markdown::defaultTransform($r['body']);
$deck = $r['deck'];
$media = $oo->media($r['id']);
if(count($media) > 0)
	$cover = $media[0];
else
	$cover = false;

if($cover)
	$cover_img = m_url($cover);

// only show back button on internal references
$internal = (substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) === $host);
$back_url = "javascript:self.history.back();";

?><div class="mainContainer">
	<div class="wordsContainer body"><?
		echo nl2br($deck);
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
