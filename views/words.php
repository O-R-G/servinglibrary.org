<?
$body = isset($item) ? trim($item['body']) : '';
$deck = isset($item) ? trim($item['deck']) : '';
$media = isset($item) ? $oo->media($item['id']) : array();
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
            ?><br><a href="/">Go back</a><?
        ?><div>
</div>
