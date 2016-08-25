<?
// namespace stuff
use \Michelf\Markdown;

$r = $oo->get($uu->id);
$body = Markdown::defaultTransform($r['body']);
$deck = $r['deck'];
$cover = $oo->media($r['id'])[0];
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
		?><img src="<? echo $cover_img; ?>"><?
		}
		echo $body;
                if ($showsubscribe)
                       require_once("views/subscribe.php");
		if($internal && !$showsubscribe)
		{
		?><a href="<? echo $back_url; ?>">Go back</a><?
		}
	?><div>
</div>
