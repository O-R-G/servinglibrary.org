<?
$body = trim($item['body']);
$deck = trim($item['deck']);
$media = $oo->media($item['id']);
if(count($media) > 0) {
	$cover = $media[0];
	$cover_img = m_url($cover);
} else
	$cover = false;

?><div class="mainContainer">
        <div class="wordsContainer body"><?
    		echo $deck;
            if($cover) {
                ?><img class="cover" src="<? echo $cover_img; ?>"><?
            }
            echo $body;
	        if (isset($showsubscribe))
                require_once("views/join.php");
            ?><a href="<? echo $back_url; ?>">Go back</a><?
        ?><div>
</div>
