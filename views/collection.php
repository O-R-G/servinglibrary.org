<?
// get all objects

$objects = $oo->children($uu->id);
$n = count($children);

// ajax (turned off for now)
// $n = 10;
// require($root."views/home-ajax.php");
// $n--;
// require($root."views/home-ajax.php");

// randomise the array
shuffle($objects);

foreach($objects as $o)
{
	// style caption
	$title = strtoupper($o['name1']);
	$caption = $title;
	
	// get cover image
	$all_media = $oo->media($o['id']);
	$j = 0;
	while($all_media[$j]['type'] == "pdf")
		$j++;
	
	// get cover image url
	$m_url = m_url($all_media[$j]);
	
	// build object url
	$o_url = $uu->url."/".$o['url'];
	
	// display object and caption
	?><div class="thumbsContainer object">
		<a href="<? echo $o_url; ?>">
			<div class="coverContainer"><?
				?><img src="<? echo $m_url; ?>"><?
			?></div>
		</a>
		<div class="captionContainer caption"><? 
			echo $caption; 
		?></div>
	</div><?
}
	
?><div id="Menu" class="TSLContainer body">
        <a href="about/the-serving-library">About</a> /
        <a href="contact">Contact</a> /
        <a href="journal">Journal</a> /
        <a href="space">Space</a> /
        <a href="events">Events</a> / 
        <!-- <a href="collection">Collection</a> / -->
        <!-- <a href="teaching">Teaching</a> / -->
        <a href="shop">Shop</a>  
</div>
