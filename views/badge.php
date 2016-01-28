<?
// if on home page, point to words,
// else, point to home page
if($uu->id)
	$badge_url = "/";
else
	$badge_url = "/about/the-serving-library";

$badge_src = "/media/gif/TSL-red.gif";

?><div id="badge-container">
	<a href='<? echo $badge_url; ?>'>
		<img id="badge" src='<? echo $badge_src; ?>'>
	</a>
</div>